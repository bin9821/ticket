<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TicketRabbitMQ implements ShouldQueue
{
    use Queueable;

    protected $user_id;
    protected $ticket_name;
    protected $ticket_id;

    /**
     * Create a new job instance.
     */
    public function __construct(int $ticket_id, int $user_id) //don't transfer model to job
    {
        $this->user_id = $user_id;
        $this->ticket_name = "ticket:" . $ticket_id;
        $this->ticket_id = $ticket_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::transaction(function (){

                $locked = Ticket::where('id', $this->ticket_id)->lockForUpdate()->first();

                $locked->sold = $locked->sold + 1;
                $locked->save();

                $order = new Order();
                $order->user()->associate($this->user_id);
                $order->ticket()->associate($locked);
                $order->number = 1;
                $order->save();
            });
        } catch (\Throwable $e) {
            Redis::hincrby($this->ticket_name, "ticket_remainder", 1);
            //dd($e);
        }
    }
}
