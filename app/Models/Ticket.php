<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Ticket extends Model
{
     protected $fillable = [
        'project_id',
        'status_id',
        'priority_id',
        'created_by', // Needed for the Hidden field in Filament form
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
    ];

     public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // 2. One-to-One (Inverse): The Status of the ticket.
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    // 3. One-to-One (Inverse): The Priority of the ticket.
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    // 4. One-to-One (Inverse): The User who created the ticket.
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by'); // Link to the 'created_by' column
    }

    // 5. Many-to-Many: The Users assigned to the ticket.
    // This is the CRITICAL method for assigning multiple users.
    public function assignees(): BelongsToMany
    {
        // Tell Laravel to use the 'ticket_user' table to connect
        return $this->belongsToMany(User::class, 'ticket_user');
    }


}
