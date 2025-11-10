<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buy Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium">剩餘票數</h3>

                    @if(session('status') === 'purchase-success')
                        <div class="mt-2 text-green-600">購票成功</div>
                    @elseif(session('status') === 'purchase-failed')
                        <div class="mt-2 text-red-600">購票失敗（可能已售完）</div>
                    @endif

                    <div class="mt-4 space-y-4">
                        @foreach($tickets as $ticket)
                            <div class="flex items-center justify-between p-3 border rounded">
                                <div>
                                    <div class="font-semibold">{{ $ticket->name }}</div>
                                    <div class="text-sm text-gray-600">剩餘: {{ $ticket->total_number - $ticket->sold }}</div>
                                </div>
                                <form method="POST" action="{{ route('ticket.purchase', $ticket) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none">購票</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
