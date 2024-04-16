<?php

namespace BishalGurung\Comment\Services;

use BishalGurung\Comment\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use BishalGurung\Comment\Models\CommentReaction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use BishalGurung\Comment\Exceptions\InvalidUserException;

class CommentService
{
    // public function getCommentWithReactionCount($model, int $paginate = 0)
    // {
    //     $comments = $this
    //         ->commentWithReactionCountQuery($model->comments());

    //     return $this->formatCommentData($paginate ? $comments->paginate($paginate) : $comments->get());
    // }

    public function getCommentWithReactionCount(Collection | LengthAwarePaginator $comments)
    {
        $reaction_counts = $this->getReactionCounts($comments->pluck("id")->toArray());
        return $this->formatCommentData($comments, $reaction_counts);
    }

    public function getCommentRepliesWithReactionCount($comment_id, $pagination_limit = 0)
    {
        // $replies = $this->commentWithReactionCountQuery(Comment::where("parent_id", $comment_id))->get();
        $query = Comment::where("parent_id", $comment_id);
        $replies = ($pagination_limit) ? $query->paginate($pagination_limit) : $query->get();
        $paginated_data = ($replies instanceof LengthAwarePaginator) ? $replies->toArray() : null;
        $reaction_counts = $this->getReactionCounts($replies->pluck("id")->toArray());
        $formatted_replies = $this->formatCommentData($replies, $reaction_counts);
        return ($paginated_data) ? [... $paginated_data, "data" => $formatted_replies] : $formatted_replies;
    }

    private function commentWithReactionCountQuery($query): Builder
    {
        return $query->selectRaw("comments.id, min(comment) as comment, comment_reaction.reaction_id, count(comment_reaction.reaction_id) as reaction_count")
        ->leftJoin("comment_reaction", "comments.id", "comment_reaction.comment_id")
        ->groupBy("comments.id", "comment_reaction.reaction_id")
        ->orderBy("comments.id");
    }

    // private function formatCommentData(Collection | LengthAwarePaginator $collection)
    // {
    //     $pagination_info = $collection instanceof LengthAwarePaginator ? $collection->toArray() : [];
    //     $data = collect([]);
    //     foreach ($collection->groupBy("id") as $comment_id => $grouped_comments) {
    //         $data->push([
    //             ...Arr::except($grouped_comments[0]->toArray(), ["reaction_id", "reaction_count"]),
    //             "reactions" => $grouped_comments->map(function ($item) {
    //                 if (!$item["reaction_id"])
    //                     return null;
    //                 return [
    //                     "reaction_id" => $item["reaction_id"],
    //                     "reaction_count" => $item["reaction_count"]
    //                 ];
    //             })->filter()]
    //         );
    //     }
    //     return $collection instanceof LengthAwarePaginator ? [
    //         ... $pagination_info,
    //         "data" => $data
    //     ] : $data;
    // }

    private function formatCommentData($comments, $reactions)
    {
        $reactions = $reactions->groupBy("comment_id");
        $data = collect([]);
        foreach ($comments as $comment) {
            $reaction_counts = isset($reactions[$comment->id])
            ? $reactions[$comment->id]
                ->map(fn ($reaction_count) => $reaction_count->only("reaction_id", "count"))
            : [];
            $data->push([
                ...$comment->toArray(),
                "reaction_count" =>  $reaction_counts
            ]);
        }
        // return $collection instanceof LengthAwarePaginator ? [
        //     ... $pagination_info,
        //     "data" => $data
        // ] : $data;
        return $data;
    }

    private function getReactionCounts(array $comment_ids)
    {
        $reactions = CommentReaction::query()
            ->selectRaw("comment_id, reaction_id, count(reaction_id) as count")
            ->whereIn("comment_id", $comment_ids)
            ->groupBy("comment_id", "reaction_id")
            ->get();
        return $reactions;
    }

    public function reactToComment($comment_id, $reaction_id, $user = null)
    {
        if (!($user || auth()->user()))
            throw new InvalidUserException;
        return CommentReaction::updateOrCreate([
            "comment_id" => $comment_id,
            "user_type" => $user ? get_class($user) : get_class(auth()->user()),
            "user_id" => $user ? $user->id : auth()->id()
        ], [
            "reaction_id" => $reaction_id,
        ]);
    }
}
