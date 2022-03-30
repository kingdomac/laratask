<?php

namespace App\Models;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Priority extends Model
{
    use HasFactory;

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
