<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'icon'];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
