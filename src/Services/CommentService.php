<?php

namespace BishalGurung\Comment\Services;

use Illuminate\Support\Arr;
use BishalGurung\Comment\Models\Comment;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function getCommentWithReactionCount($model)
    {
        $comments = $this
            ->commentWithReactionCountQuery($model->comments())
            ->get();
        
        return $this->formatCommentData($comments);
    }

    public function getCommentRepliesWithReactionCount($comment_id)
    {
        $replies = $this->commentWithReactionCountQuery(Comment::where("parent_id", $comment_id))->get();
        return $this->formatCommentData($replies);
    }

    private function commentWithReactionCountQuery($query): Builder
    {
        return $query->selectRaw("comments.id, min(comment) as comment, comment_reaction.reaction_id, count(comment_reaction.reaction_id) as reaction_count")
        ->leftJoin("comment_reaction", "comments.id", "comment_reaction.comment_id")
        ->groupBy("comments.id", "comment_reaction.reaction_id")
        ->orderBy("comments.id");
    }

    private function formatCommentData(Collection $collection)
    {
        $data = collect();
        foreach ($collection->groupBy("id") as $comment_id => $grouped_comments) {
            $data->push([
                ...Arr::except($grouped_comments[0]->toArray(), ["reaction_id", "reaction_count"]),
                "reactions" => $grouped_comments->map(function ($item) {
                    if (!$item["reaction_id"])
                        return null;
                    return [
                        "reaction_id" => $item["reaction_id"],
                        "reaction_count" => $item["reaction_count"]
                    ];
                })->filter()]
            );
        }
        return $data;
    }
}
