<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    // Perbaiki nama properti dari $tabel menjadi $table
    protected $table = "todo";

    protected $fillable = [
        'task',
        'is_done'
    ];
}