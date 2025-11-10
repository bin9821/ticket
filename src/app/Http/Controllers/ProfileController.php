<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // /**
    //  * Display the user's profile form.
    //  */
    // public function edit(Request $request): View
    // {
    //     $tickets = Ticket::all();

    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //         'tickets' => $tickets,
    //     ]);
    // }

    // /**
    //  * Handle ticket purchase (1 ticket per request).
    //  */
    // public function purchase(Request $request, Ticket $ticket): RedirectResponse
    // {
    //     $user = $request->user();

    //     try {
    //         DB::transaction(function () use ($ticket, $user) {
    //             // lock the selected ticket row to avoid race conditions
    //             $locked = Ticket::where('id', $ticket->id)->lockForUpdate()->first();

    //             $remaining = $locked->total_number - $locked->sold;
    //             if ($remaining <= 0) {
    //                 throw new \RuntimeException('Sold out');
    //             }

    //             $locked->sold = $locked->sold + 1;
    //             $locked->save();

    //             $order = new Order();
    //             $order->user()->associate($user);
    //             $order->ticket()->associate($locked);
    //             $order->number = 1;
    //             $order->save();
    //         });
    //     } catch (\Throwable $e) {
    //         return Redirect::route('profile.edit')->with('status', 'purchase-failed');
    //     }

    //     return Redirect::route('profile.edit')->with('status', 'purchase-success');
    // }



    
    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    /**
     * Delete the user's account.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current_password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }

}
