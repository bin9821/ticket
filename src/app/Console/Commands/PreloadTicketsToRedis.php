<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class PreloadTicketsToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:preload-tickets-to-redis {ticket_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load tickets from DB to redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticket_id = $this->argument('ticket_id');
        $ticket = Ticket::find($ticket_id);
        if($ticket){
            Redis::hmset("ticket:" . $ticket_id, [
                        "ticket_name" => $ticket->name, 
                        "ticket_remainder" => $ticket->total_number - $ticket->sold]);
            $this->info("preload tickets successful");
            return Command::SUCCESS;
        } else {
            $this->error("preload tickets failure ( ticket id = {$ticket_id} not found )");
            return Command::FAILURE;
        }
    }
}
