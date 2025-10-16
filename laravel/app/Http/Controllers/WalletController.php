<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{

    // GET /wallet — solde
    public function show(Request $request)
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id], ['balance' => 0]);
        return response()->json([
            'user_id' => $wallet->user_id,
            'balance' => (float) $wallet->balance,
        ]);
    }



    // POST /wallet/topup — recharge (amount <= 10000)
    public function topup(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required','numeric','gt:0','lte:10000'],
        ]);

        $userId = $request->user()->id;

        $result = DB::transaction(function () use ($userId, $data) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
            if (! $wallet) {
                $wallet = Wallet::create(['user_id' => $userId, 'balance' => 0]);
                $wallet->refresh();
            }


            // précision monétaire
            $wallet->balance = bcadd($wallet->balance, $data['amount'], 2);
            $wallet->save();

            $tx = WalletTransaction::create([
                'sender_id'   => null,
                'receiver_id' => $userId,
                'amount'      => $data['amount'],
                'status'      => 'success',
            ]);

            return [$wallet, $tx];
        });

        [$wallet, $tx] = $result;

        return response()->json(
            [
            'user_id'      => $wallet->user_id,
            'balance'      => (float) $wallet->balance,
            'topup_amount' => (float) $tx->amount,
            'created_at'   => $tx->created_at->toISOString(),
            ], 201);
    }



    // POST /wallet/transfer — transfert entre utilisateurs
    public function transfer(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => ['required','integer','exists:users,id'],
            'amount'      => ['required','numeric','gt:0'],
        ]);

        $senderId = $request->user()->id;
        if ((int)$data['receiver_id'] === (int)$senderId) {
            return response()->json(['message' => 'Receiver cannot be the same as sender'], 422);
        }

        [$tx, $status] = DB::transaction(function () use ($senderId, $data) {
            // ordre de verrouillage déterministe pour éviter les deadlocks
            $ids = [$senderId, (int)$data['receiver_id']];
            sort($ids);

            $wallets = Wallet::whereIn('user_id', $ids)->lockForUpdate()->get()->keyBy('user_id');

            $senderWallet   = $wallets[$senderId] ?? Wallet::create(['user_id' => $senderId, 'balance' => 0]);
            $receiverWallet = $wallets[(int)$data['receiver_id']] ?? Wallet::create(['user_id' => (int)$data['receiver_id'], 'balance' => 0]);

            if ($senderWallet->balance < $data['amount']) {
                // trace l’échec
                $tx = WalletTransaction::create([
                    'sender_id'   => $senderId,
                    'receiver_id' => (int)$data['receiver_id'],
                    'amount'      => $data['amount'],
                    'status'      => 'failed',
                ]);
                return [$tx, 'failed'];
            }

            $senderWallet->balance   = bcsub($senderWallet->balance, $data['amount'], 2);
            $receiverWallet->balance = bcadd($receiverWallet->balance, $data['amount'], 2);
            $senderWallet->save();
            $receiverWallet->save();

            $tx = WalletTransaction::create([
                'sender_id'   => $senderId,
                'receiver_id' => (int)$data['receiver_id'],
                'amount'      => $data['amount'],
                'status'      => 'success',
            ]);

            return [$tx, 'success'];
        });

        if ($status === 'failed') {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        return response()->json([
            'transaction_id' => $tx->id,
            'sender_id'      => $tx->sender_id,
            'receiver_id'    => $tx->receiver_id,
            'amount'         => (float) $tx->amount,
            'status'         => $tx->status,
            'created_at'     => $tx->created_at->toISOString(),
        ], 201);
    }




    // GET /wallet/transactions — historique (là je suis en en sender OU receiver)
    public function transactions(Request $request)
    {
        $uid = $request->user()->id;
        $list = WalletTransaction::where(function ($q) use ($uid) {
                $q->where('sender_id', $uid)->orWhere('receiver_id', $uid);
            })
            ->orderByDesc('id')
            ->get(['id','sender_id','receiver_id','amount','status','created_at'])
            ->map(function ($tx) {
                return [
                    'id'         => $tx->id,
                    'sender_id'  => $tx->sender_id,
                    'receiver_id'=> $tx->receiver_id,
                    'amount'     => (float) $tx->amount,
                    'status'     => $tx->status,
                    'created_at' => $tx->created_at->toISOString(),
                ];
            });

        return response()->json($list);
    }
}
