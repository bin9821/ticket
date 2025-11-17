<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


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

    public function purchase(Request $request, $ticket_id): RedirectResponse
    {
        $user = $request->user();
        $ticket_name = "ticket:" . $ticket_id;
        try {
            $lua_purchase = <<< LUA
                stock = redis.call("hget", KEYS[1], "ticket_remainder")
                if not stock then
                    return 0
                end

                stock = tonumber(stock)

                if stock >= 1 then
                    stock = stock - 1
                else 
                    return 0
                end
                redis.call("hset", KEYS[1], "ticket_remainder", stock)
                return 1
            LUA;
            
            // (php artisan app:preload-tickets-to-redis $ticket_id) command can preload tickets from DB to redis
            if (!Redis::eval($lua_purchase, 1, $ticket_name))
                return Redirect::route('ticket.buy')->with('status', 'purchase-failed');
                
            DB::transaction(function () use ($ticket_id, $user) {

                $locked = Ticket::where('id', $ticket_id)->lockForUpdate()->first();

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
            Redis::hincrby($ticket_name, "ticket_remainder", -1);
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
