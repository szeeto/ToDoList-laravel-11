<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
    protected $fillable = ['email', 'token', 'create_at'];

    // Nonaktifkan timestamps
    const updated_at = false;
}