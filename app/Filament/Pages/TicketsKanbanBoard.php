<?php

namespace App\Filament\Pages;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use Filament\Pages\Page;
use Filament\Forms;
<<<<<<< HEAD
=======
use Filament\Forms\Form;
>>>>>>> new-feature

class TicketsKanbanBoard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.tickets-kanban-board';
    protected static ?string $title = 'Ticket Board';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public array $ticketsByStatus = [];
    public ?Ticket $editingTicket = null;

<<<<<<< HEAD
=======

    public ?array $data = [];

>>>>>>> new-feature
    public function mount(): void
    {
        $this->loadTickets();
    }

    public function loadTickets(): void
    {
<<<<<<< HEAD
        $this->ticketsByStatus = Ticket::orderBy('sort_order')->get()->groupBy('status')->toArray();
         $this->ticketsByStatus = Ticket::with('assignees')
        ->orderBy('sort_order')
        ->get()
        ->groupBy('status')
        ->toArray();


=======

        $this->ticketsByStatus = Ticket::with('assignees')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('status')
            ->toArray();
>>>>>>> new-feature
    }

    public function updateTicketStatus($ticketId, $newStatus): void
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket && $ticket->status !== $newStatus) {
            $ticket->update(['status' => $newStatus]);
        }
        $this->loadTickets();
    }

    public function openEditModal($id)
    {
        $this->editingTicket = Ticket::find($id);
<<<<<<< HEAD
        $this->form->fill($this->editingTicket->toArray());
=======


        $this->form->fill($this->editingTicket->toArray());

>>>>>>> new-feature
        $this->dispatch('open-modal', id: 'edit-ticket-modal');
    }

    public function saveTicket(): void
    {
<<<<<<< HEAD
        if ($this->editingTicket) {
            $this->editingTicket->update($this->form->getState());
        }
=======

        $state = $this->form->getState();

        if ($this->editingTicket) {
            $this->editingTicket->update($state);
        }

>>>>>>> new-feature
        $this->dispatch('close-modal', id: 'edit-ticket-modal');
        $this->loadTickets();
    }

<<<<<<< HEAD
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description'),
        ];
=======

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description'),
            ])
            ->statePath('data'); 
>>>>>>> new-feature
    }
}
