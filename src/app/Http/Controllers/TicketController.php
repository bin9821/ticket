<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function buy(Request $request)
    {
        $tickets = Ticket::all();

        return view('ticket.buy', [
            'user' => $request->user(),
            'tickets' => $tickets,
        ]);
    }

    public function manage(Request $request)
    {
        $user = $request->user();
        $userTicketsCount = $user->orders()->count();

        return view('ticket.manage', [
            'userTicketsCount' => $userTicketsCount,
        ]);
    }

    public function purchase(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = $request->user();

        try {
            DB::transaction(function () use ($ticket, $user) {

                $locked = Ticket::where('id', $ticket->id)->lockForUpdate()->first();

                $remaining = $locked->total_number - $locked->sold;
                if ($remaining <= 0) {
                    throw new \RuntimeException('Sold out');
                }

                $locked->sold = $locked->sold + 1;
                $locked->save();

                $order = new Order();
                $order->user()->associate($user);
                $order->ticket()->associate($locked);
                $order->number = 1;
                $order->save();
            });
        } catch (\Throwable $e) {
            return Redirect::route('ticket.buy')->with('status', 'purchase-failed');
        }

        return Redirect::route('ticket.buy')->with('status', 'purchase-success');
    }

    public function resetAllTickets(): RedirectResponse
    {
        Order::truncate();
        Ticket::query()->update(['sold' => 0]);

        return Redirect::route('ticket.buy')->with('status', 'tickets-reset');
    }
}
