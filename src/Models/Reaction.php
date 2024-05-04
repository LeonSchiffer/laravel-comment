<?php

namespace BishalGurung\Comment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ["comment_id", "model_type" , "model_id", "user_type", "user_id", "reaction_type_id"];

    public function user()
    {
        return $this->morphTo("user");
    }
}
