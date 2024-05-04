<?php

namespace BishalGurung\Comment\Models;

use BishalGurung\Comment\Traits\HasComment;
use Illuminate\Database\Eloquent\Model;
use BishalGurung\Comment\Traits\HasReaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $commentable_type
 * @property string $commentable_id
 * @property string $user_type
 * @property string $user_id
 * @property string $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Comment extends Model
{
    use SoftDeletes;
    use HasReaction;
    use HasUuids;
    use HasComment;

    public $incrementing = false;

    protected $fillable = ["id", "parent_id", "commentable_type", "commentable_id", "user_type", "user_id", "comment"];
}
