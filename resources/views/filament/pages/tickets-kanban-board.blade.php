<x-filament::page>
    <div class="flex gap-6 overflow-x-auto pb-8">
        @foreach (\App\Enums\TicketStatus::cases() as $status)
            <div class="w-80 bg-gray-50 dark:bg-gray-800 rounded-xl p-4 shadow shrink-0"
                 x-data
                 @dragover.prevent
                 @drop.prevent="$wire.updateTicketStatus(window.__draggedTicket, '{{ $status->value }}')">
                <h3 class="font-bold text-lg mb-4">{{ ucfirst(str_replace('_',' ',$status->value)) }}</h3>

                <div class="space-y-3 min-h-[200px]">
                    @foreach ($ticketsByStatus[$status->value] ?? [] as $ticket)
                        <div
                            class="p-3 bg-white dark:bg-gray-700 rounded-lg shadow cursor-move"
                            draggable="true"
                            wire:key="ticket-{{ $ticket['id'] }}"
                            @dragstart="window.__draggedTicket = {{ $ticket['id'] }}"
                            @click="$wire.openEditModal({{ $ticket['id'] }})"
                        >
                            <div class="font-semibold">{{ $ticket['title'] }}</div>


                            <div class="text-xs text-gray-500 mt-1">

    @if (!empty($ticket['assignees']))
        <div class="font-bold ">Assigned:</div>
        <ul class="list-disc list-inside ml-2">
            @foreach ($ticket['assignees'] as $user)
                <li class="leading-snug">{{ $user['name'] }}</li>
            @endforeach
        </ul>
    @else
        <div class="italic">Unassigned</div>
    @endif
</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Edit Ticket Modal -->
    <x-filament::modal id="edit-ticket-modal">
        <x-slot name="heading">Edit Ticket</x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <x-filament::button wire:click="saveTicket" color="primary">Save</x-filament::button>
            <x-filament::button color="secondary" x-on:click="$dispatch('close-modal', { id: 'edit-ticket-modal' })">Cancel</x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament::page>
