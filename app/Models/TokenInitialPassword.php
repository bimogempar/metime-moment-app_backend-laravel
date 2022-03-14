<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenInitialPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_initial_password',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
