<?php

namespace BishalGurung\Comment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    use HasFactory;

    protected $table = "comment_reaction";

    protected $fillable = ["comment_id", "user_type", "user_id", "reaction_id"];
}
