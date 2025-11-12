<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    // Optionally add $fillable if you didn't define it
    protected $fillable = ['title', 'description'];

    // One-to-Many: A project has many tickets.
   public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

}
