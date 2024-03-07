<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosterType extends Model
{
    use HasFactory;
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
