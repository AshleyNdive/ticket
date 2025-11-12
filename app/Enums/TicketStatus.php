<?php

namespace App\Enums;



enum TicketStatus: string // The ': string' makes it a string-backed enum
{
   // use IsKanbanStatus;

    // The right side is the value stored in the database
    case Open = 'open';
    case IN_PROGRESS = 'in_progress';
    case Closed = 'closed';


    // Optional: Add a method to get a Filament color for badges/UI
    public function label(): string
    {
        return match ($this) {
            self::Open => 'danger',
            self::IN_PROGRESS => 'warning',
            self::Closed => 'success',
        };
    }
}
