<?php

namespace BishalGurung\Comment\Traits;

use BishalGurung\Comment\Exceptions\InvalidCommentException;
use Illuminate\Support\Str;
use BishalGurung\Comment\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use BishalGurung\Comment\Services\CommentService;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use BishalGurung\Comment\Exceptions\InvalidUserException;
use BishalGurung\Comment\Repositories\CommentRepository;

trait HasComment
{
    private $user_type;
    private $user_id;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, "commentable");
    }

    /**
     * Attach comment to a model
     * @param string $comment
     * @return Comment
     */
    public function addComment(string $comment)
    {
        if (!(auth()->user() || ($this->user_type && $this->user_id)))
            throw new InvalidUserException;
        if (!$comment)
            throw new InvalidCommentException;
        $user_type = $this->user_type ?: get_class(auth()->user());
        $user_id = $this->user_id ?: auth()->user()->id;
        return $this->comments()->create([
            "id" => Str::uuid(),
            "comment" => $comment,
            "user_type" => $user_type,
            "user_id" => $user_id,
        ]);
    }

    /**
     * Set who commented manually
     * @param mixed $user The person who commented
     * @param string The property to get the user_id from
     * @return static
     */
    public function setCommentUser($user, $column = "id"): static
    {
        $this->user_type = get_class($user);
        $this->user_id = $user->{$column};
        return $this;
    }

    /**
     * Get list of all comments of a model
     * @param int $pagination_limit The pagination limit value, if not provided will just all comments
     * @return array
     */
    public function getComments(int $pagination_limit = 0, bool $with_reactions = false)
    {
        // $comments = $this->comments()->paginate($pagination_limit);
        $query = $this
            ->comments()
            ->when($with_reactions, function ($q) {
                $q->with("reactionCount");
            });
        $comments = $pagination_limit ? $query->paginate($pagination_limit) : $this->query->get();
        return $comments;
    }
}
