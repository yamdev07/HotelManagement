<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRestriction
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Si l'utilisateur est Admin et essaie de supprimer une transaction
        if ($user && $user->role === 'Admin') {
            $routeName = $request->route()->getName();
            
            // Empêche la suppression de transactions
            if (str_contains($routeName, 'transaction.destroy') || 
                str_contains($request->path(), 'transaction/') && $request->isMethod('delete')) {
                
                return redirect()->back()
                    ->with('error', 'Les administrateurs ne peuvent pas supprimer les réservations.');
            }
            
            // Empêche aussi l'annulation si c'est une suppression
            if (str_contains($routeName, 'transaction.cancel') && $request->isMethod('delete')) {
                return redirect()->back()
                    ->with('error', 'Les administrateurs ne peuvent pas annuler les réservations.');
            }
        }
        
        return $next($request);
    }
}