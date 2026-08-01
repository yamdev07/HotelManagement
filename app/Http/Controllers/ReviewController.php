<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Rules\NoEmoji;
use Illuminate\Http\Request;

/**
 * Modération des avis clients côté hôtelier : approuver, rejeter, répondre,
 * supprimer. Les avis sont scopés à l'hôtel courant (BelongsToHotel), donc
 * un hôtel ne voit et ne modère que ses propres avis.
 */
class ReviewController extends Controller
{
    public function index()
    {
        $pending  = Review::pending()->latest()->get();
        $approved = Review::approved()->latest('approved_at')->get();
        $rejected = Review::where('status', Review::STATUS_REJECTED)->latest()->get();

        return view('reviews.index', compact('pending', 'approved', 'rejected'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => Review::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Avis approuvé et publié sur votre vitrine.');
    }

    public function reject(Review $review)
    {
        $review->update([
            'status' => Review::STATUS_REJECTED,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Avis rejeté : il n\'apparaîtra pas sur la vitrine.');
    }

    public function reply(Request $request, Review $review)
    {
        $data = $request->validate([
            'reply' => ['required', 'string', 'max:1000', new NoEmoji],
        ], [
            'reply.required' => 'Votre réponse ne peut pas être vide.',
        ]);

        $review->update([
            'reply' => $data['reply'],
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Votre réponse a été enregistrée.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Avis supprimé.');
    }
}
