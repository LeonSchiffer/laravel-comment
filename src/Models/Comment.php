<?php

namespace BishalGurung\Comment\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Model;
use BishalGurung\Comment\Traits\HasReaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use SoftDeletes;
    use HasReaction;
    use HasUuids;

    public $incrementing = false;

    protected $guarded = [];
}
