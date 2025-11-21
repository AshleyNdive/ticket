<?php

namespace App\Filament\Pages;

use App\Models\Ticket;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;

class TicketsKanbanBoard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.tickets-kanban-board';
    protected static ?string $title = 'Ticket Board';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public array $ticketsByStatus = [];
    public ?Ticket $editingTicket = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadTickets();
    }

    public function loadTickets(): void
    {
        $this->ticketsByStatus = Ticket::with('assignees')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('status')
            ->toArray();
    }

    public function updateTicketStatus($ticketId, $newStatus): void
    {
        if ($ticket = Ticket::find($ticketId)) {
            if ($ticket->status !== $newStatus) {
                $ticket->update(['status' => $newStatus]);
            }
        }

        $this->loadTickets();
    }

    public function openEditModal($id)
    {
        $this->editingTicket = Ticket::findOrFail($id);

        $this->form->fill(
            $this->editingTicket->toArray()
        );

        $this->dispatch('open-modal', id: 'edit-ticket-modal');
    }

    public function saveTicket(): void
    {
        if ($this->editingTicket) {
            $this->editingTicket->update(
                $this->form->getState()
            );
        }

        $this->dispatch('close-modal', id: 'edit-ticket-modal');
        $this->loadTickets();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description'),
            ])
            ->statePath('data');
    }
}
