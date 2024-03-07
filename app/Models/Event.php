<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use App\Models\PosterType;
use App\Models\PosterImage;
use App\Models\RecurringEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function poster_image(){
        return $this->hasOne(PosterImage::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function poster_type(){
        return $this->belongsTo(PosterType::class);
    }

    public function recurrings(){
        return $this->hasMany(RecurringEvent::class);
    }
}
