<?php

namespace App\Models;

use App\Mail\TestingMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'no_hp',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function sendEmailRegister($type_set_password, $user, $tokeninitialpassword)
    {
        Mail::send('emails.welcome', ['type_set_password' => $type_set_password, 'user' => $user, 'token_initial_password' => $tokeninitialpassword], function ($m) use ($user) {
            $m->from('admin@metimemoment.com', 'Metime Moment');
            $m->to($user->email, $user->name)->subject('Welcome to Metime Moment');
        });
    }

    public function TokenInitialPassword()
    {
        return $this->hasOne(TokenInitialPassword::class);
    }
}
