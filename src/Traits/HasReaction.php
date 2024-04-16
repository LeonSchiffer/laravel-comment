<?php

namespace BishalGurung\Comment\Traits;

use BishalGurung\Comment\Services\CommentService;

trait HasReaction
{
    public function react($reaction_id)
    {
        return (new CommentService)->reactToComment($this->id, $reaction_id);
    }
}