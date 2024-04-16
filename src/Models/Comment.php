<?php

namespace BishalGurung\Comment\Models;

use App\Models\CommentReaction;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Model;
use BishalGurung\Comment\Traits\HasReaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasReaction;

    public $incrementing = false;

    protected $guarded = [];

    static function newFactory()
    {
        return CommentFactory::new();
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }
}
