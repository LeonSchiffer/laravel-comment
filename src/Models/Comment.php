<?php

namespace BishalGurung\Comment\Models;

use App\Models\CommentReaction;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    static function newFactory()
    {
        return CommentFactory::new();
    }

    // public function reactions(): BelongsToMany
    // {
    //     return $this->belongsToMany(Reaction::class);
    // }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }
}
