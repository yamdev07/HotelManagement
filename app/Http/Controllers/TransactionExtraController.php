<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionExtra;
use Illuminate\Http\Request;

class TransactionExtraController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'category'    => 'required|in:minibar,laundry,service,other',
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:1',
        ]);

        $extra = $transaction->extras()->create([
            'user_id'     => auth()->id(),
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'amount'      => $validated['amount'],
            'quantity'    => $validated['quantity'],
        ]);

        // Mettre à jour le total de la transaction
        $transaction->update(['total_price' => $transaction->getTotalPrice()]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Extra ajouté avec succès',
                'extra'   => [
                    'id'          => $extra->id,
                    'category'    => $extra->category_label,
                    'description' => $extra->description,
                    'amount'      => $extra->amount,
                    'quantity'    => $extra->quantity,
                    'subtotal'    => $extra->subtotal,
                ],
                'new_total' => $transaction->fresh()->getTotalPrice(),
            ]);
        }

        return back()->with('success', 'Extra "' . $extra->description . '" ajouté à la facture.');
    }

    public function destroy(Transaction $transaction, TransactionExtra $extra)
    {
        abort_if($extra->transaction_id !== $transaction->id, 403);

        $extra->delete();

        $transaction->update(['total_price' => $transaction->getTotalPrice()]);

        if (request()->expectsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Extra supprimé',
                'new_total' => $transaction->fresh()->getTotalPrice(),
            ]);
        }

        return back()->with('success', 'Extra supprimé de la facture.');
    }
}
