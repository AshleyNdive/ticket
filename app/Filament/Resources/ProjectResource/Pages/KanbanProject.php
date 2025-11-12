<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\Page;
use App\Models\Project;
use App\Models\Ticket;

class KanbanProject extends Page
{
    protected static string $resource = ProjectResource::class;
    protected static string $view = 'filament.resources.projects.pages.kanban-project';

    public Project $record;

    public $ticketsByStatus = [];
    public $statuses = ['To Do', 'In Progress', 'Done'];

    public function mount($record)
    {
        $this->record = Project::findOrFail($record);
        $this->loadTickets();
    }

    public function loadTickets()
    {
        $this->ticketsByStatus = Ticket::where('project_id', $this->record->id)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->groupBy('status')
            ->toArray();
    }

    public function updateTicketStatus($ticketId, $newStatus)
    {
        $ticket = Ticket::find($ticketId);

        if ($ticket) {
            $ticket->update(['status' => $newStatus]);
        }

        $this->loadTickets();
    }

    public function reorderTickets($status, $orderedIds)
    {
        foreach ($orderedIds as $index => $ticketId) {
            Ticket::where('id', $ticketId)->update(['sort_order' => $index]);
        }

        $this->loadTickets();
    }
}
