<?php

namespace BishalGurung\Comment\Repositories;

use BishalGurung\Comment\Models\Reaction;
use BishalGurung\Comment\Exceptions\InvalidUserException;

class ReactionRepository
{
    public function attachReaction($model, $reaction_id, $user = null)
    {
        if (!($user || auth()->user()))
            throw new InvalidUserException;
        return Reaction::updateOrCreate([
            "model_type" => get_class($model),
            "model_id" => $model->id,
            "user_type" => $user ? get_class($user) : get_class(auth()->user()),
            "user_id" => $user ? $user->id : auth()->id()
        ], [
            "reaction_type_id" => $reaction_id,
        ]);
    }
}

