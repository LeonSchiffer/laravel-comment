<?php

namespace BishalGurung\Comment\Traits;

use App\Models\User;
use BishalGurung\Comment\Models\Reaction;
use BishalGurung\Comment\Repositories\CommentRepository;
use BishalGurung\Comment\Repositories\ReactionRepository;

trait HasReaction
{
    public function react($reaction_id)
    {
        return (new ReactionRepository)->attachReaction($this, $reaction_id);
    }

    public function reactions()
    {
        return $this->morphToMany(Reaction::class, "model");
    }

    public function reactionCount()
    {
        return $this->hasMany(Reaction::class, "model_id")
            ->leftJoin("reaction_types", "reaction_types.id", "reactions.reaction_type_id")
            ->where("reactions.model_type", get_class($this))
            ->selectRaw("model_id, reaction_type_id, type, count(reaction_type_id) as count")
            ->groupBy("model_id")
            ->groupBy("reaction_type_id")
            ;
    }
}
