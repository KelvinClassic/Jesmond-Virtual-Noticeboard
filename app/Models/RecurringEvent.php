<?php

namespace App\Models;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\PosterType;
use App\Models\PosterImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecurringEvent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function event(){
        return $this->belongsTo(Event::class);
    }

}
