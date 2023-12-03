<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'user_id', 'value'];

    public function scopeVoted(Builder $query, int $param):void {
        $query->where([
            ['user_id','=',auth()->id()],
            ['question_id','=', $param]
        ]);
    }
}
