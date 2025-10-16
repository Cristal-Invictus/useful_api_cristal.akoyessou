<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    //

    // POST /products — créer un produit
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required','string','max:255'],
            'price'     => ['required','numeric','gt:0'],
            'stock'     => ['required','integer','min:0'],
            'min_stock' => ['required','integer','min:0'],
        ]);


        $product = Product::create([
            'user_id'   => $request->user()->id,
            'name'      => $data['name'],
            'price'     => $data['price'],
            'stock'     => $data['stock'],
            'min_stock' => $data['min_stock'],
        ]);


        return response()->json([
            'id'         => $product->id,
            'user_id'    => $product->user_id,
            'name'       => $product->name,
            'price'      => (float) $product->price,
            'stock'      => $product->stock,
            'min_stock'  => $product->min_stock,
            'created_at' => $product->created_at->toISOString(),
        ], 201);
    }


    // GET /products — liste marketplace (tous les produits visibles)
    public function index()
    {
        return Product::query()
            ->select('id','name','price','stock','min_stock')
            ->orderByDesc('id')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => (float) $p->price,
                    'stock' => $p->stock,
                    'min_stock' => $p->min_stock,
                ];
            });
    }


    // POST /products/{id}/restock — uniquement par le propriétaire
    public function restock(Request $request, int $id)
    {
        $data = $request->validate([
            'quantity' => ['required','integer','gt:0'],
        ]);


        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        if ($product->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }


        $product->increment('stock', $data['quantity']);
        $product->refresh();


        return response()->json([
            'product_id'        => $product->id,
            'new_stock'         => $product->stock,
            'restocked_quantity'=> $data['quantity'],
            'low_stock'         => $product->stock < $product->min_stock,
        ]);
    }
}
