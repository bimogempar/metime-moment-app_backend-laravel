<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    // project->status (1,3) 1 = on scedule, 2 = on progress, 3 = done
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function features()
    {
        return $this->hasMany(Features::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class)->orderBy('created_at', 'desc');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
