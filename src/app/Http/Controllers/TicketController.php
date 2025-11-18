<?php

namespace App\Http\Controllers;

use App\Jobs\TicketRabbitMQ;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;
use App\Models\Order;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;


class TicketController extends Controller
{
    public function buy(Request $request)
    {
        /*
        $tickets = Ticket::all();

        return view('ticket.buy', [
            'user' => $request->user(),
            'tickets' => $tickets,
        ]);*/
        $keys = Redis::keys("ticket:*");
        $tickets = Redis::pipeline(function($pipe) use ($keys){
            foreach ($keys as $key) {
                $pipe->hgetall($key);
            }
        });
        return view('ticket.buy', [
    'tickets' => $tickets
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
                local stock = redis.call("hget", KEYS[1], "ticket_remainder")
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
            TicketRabbitMQ::dispatch($ticket_id, $user->id)->onQueue("ticket");
            // DB::transaction(function () use ($ticket_id, $user) {

            //     $locked = Ticket::where('id', $ticket_id)->lockForUpdate()->first();

            //     $locked->sold = $locked->sold + 1;
            //     $locked->save();

            //     $order = new Order();
            //     $order->user()->associate($user);
            //     $order->ticket()->associate($locked);
            //     $order->number = 1;
            //     $order->save();
            // });
        } catch (\Throwable $e) {
            Redis::hincrby($ticket_name, "ticket_remainder", 1);
            //dd($e);
            return Redirect::route('ticket.buy')->with('status', 'purchase-failed');
        }
        return Redirect::route('ticket.buy')->with('status', 'purchase-success');
    }

    public function resetAllTickets(): RedirectResponse
    {
        Order::truncate();
        Ticket::query()->update(['sold' => 0]);
        $ticket = Ticket::all();
        foreach($ticket as $t)
            Redis::hmset("ticket:" . $t->id, [
            "ticket_name" => $t->name,
            "ticket_remainder" => $t->total_number - $t->sold]);
        return Redirect::route('ticket.buy')->with('status', 'tickets-reset');
    }
}
