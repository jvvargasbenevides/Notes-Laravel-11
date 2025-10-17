<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
