<?php

namespace BishalGurung\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use BishalGurung\Comment\Traits\HasReaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use HasReaction;
    use HasUuids;

    public $incrementing = false;

    protected $fillable = ["id", "parent_id", "commentable_type", "commentable_id", "user_type", "user_id", "comment"];
}
