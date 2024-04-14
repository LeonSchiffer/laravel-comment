<?php

namespace BishalGurung\Comment\Http\Controllers;

use BishalGurung\Comment\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class ReactionController extends Controller
{
    public function storeCommentReaction(Request $request, Comment $comment)
    {
        $request->validate([
            "reaction_id" => ["required", Rule::exists("reactions", "id")]
        ]);
        $comment->reactions()->detach();
        $comment->reactions()->attach($request->reaction_id, [
            "user_type" => "App\\Models\\User",
            "user_id" => 1
        ]);
    }
}
