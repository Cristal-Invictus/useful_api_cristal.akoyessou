<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    //

    // POST /orders — créer une commande
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'quantity'   => ['required','integer','gt:0'],
        ]);


        $buyerId = $request->user()->id;


        [$order, $status] = DB::transaction(function () use ($buyerId, $data) {

            // Ici, on verrouille la ligne produit
            $product = Product::where('id', $data['product_id'])->lockForUpdate()->first();
            if (! $product) {
                return [null, 'not_found'];
            }


            // Décrément
            $product->stock = $product->stock - $data['quantity'];
            $product->save();


            $total = bcmul($product->price, $data['quantity'], 2);


            $order = Order::create([
                'buyer_id'    => $buyerId,
                'seller_id'   => $product->user_id,
                'product_id'  => $product->id,
                'quantity'    => $data['quantity'],
                'total_price' => $total,
                'status'      => 'success',
            ]);


            return [$order, 'success'];
        });


        if ($status === 'not_found') {
            return response()->json(
            [
                'message' => 'Product not found'
            ], 404);
        }
        if ($status === 'insufficient') {
            return response()->json(
                [
                    'message' => 'Insufficient stock'
                ], 400);
        }


        $product = Product::find($order->product_id);


        return response()->json([
            'order_id'   => $order->id,
            'buyer_id'   => $order->buyer_id,
            'seller_id'  => $order->seller_id,
            'product_id' => $order->product_id,
            'quantity'   => $order->quantity,
            'total_price'=> (float) $order->total_price,
            'status'     => $order->status,
            'created_at' => $order->created_at->toISOString(),
            'low_stock'  => $product->stock < $product->min_stock,
        ], 201);
    }
}
