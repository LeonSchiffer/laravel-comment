<?php

namespace BishalGurung\Comment\Traits;

use Illuminate\Support\Str;
use BishalGurung\Comment\Models\Comment;
use BishalGurung\Comment\Services\CommentService;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    private $user_type;
    private $user_id;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, "commentable");
    }

    public function addComment(string $comment)
    {
        return $this->comments()->create([
            "id" => Str::uuid(),
            "commentable_type" => self::class,
            "commentable_id" => $this->getKey(),
            "comment" => $comment,
            "user_type" => $this->user_type ?: get_class(auth()->user()),
            "user_id" => $this->user_id ?: auth()->user()->id,
        ]);
    }

    public function setCommentUser($user_type, $user_id): static
    {
        $this->user_type = $user_type;
        $this->user_id = $user_id;
        return $this;
    }

    public function getComments()
    {
        $comments = (new CommentService)->getCommentWithReactionCount($this);
        return $comments;
    }
}
