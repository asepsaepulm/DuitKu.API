<?php

namespace App\Http\Controllers\Api;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with([
            'wallet',
            'category'
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:income,expense',
            'note' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $wallet = Wallet::findOrFail(
            $validated['wallet_id']
        );

        if ($wallet->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized wallet'
            ], 403);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            ...$validated
        ]);

        // UPDATE BALANCE
        if ($validated['type'] === 'income') {
            $wallet->balance += $validated['amount'];
        } else {
            $wallet->balance -= $validated['amount'];
        }

        $wallet->save();

        return response()->json([
            'message' => 'Transaction created',
            'data' => $transaction
        ], 201);
    }

    public function show(
        Request $request,
        Transaction $transaction
    ) {
        if (
            $transaction->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json(
            $transaction->load([
                'wallet',
                'category'
            ])
        );
    }

    public function update(
        Request $request,
        Transaction $transaction
    ) {
        if (
            $transaction->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:1',
            'note' => 'nullable|string',
            'transaction_date' => 'sometimes|date',
        ]);

        $transaction->update($validated);

        return response()->json([
            'message' => 'Transaction updated',
            'data' => $transaction
        ]);
    }

    public function destroy(
        Request $request,
        Transaction $transaction
    ) {
        if (
            $transaction->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $wallet = $transaction->wallet;

        // BALANCE ROLLBACK
        if ($transaction->type === 'income') {
            $wallet->balance -= $transaction->amount;
        } else {
            $wallet->balance += $transaction->amount;
        }

        $wallet->save();

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction deleted'
        ]);
    }
}