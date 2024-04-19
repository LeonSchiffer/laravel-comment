<?php

namespace BishalGurung\Comment\Console;

use Illuminate\Console\Command;
use BishalGurung\Comment\Models\ReactionType;

class CommentInstallCommand extends Command
{
    protected $signature = "comment:install";

    public function handle()
    {
        $this->migrateReactions();
    }

    private function migrateReactions()
    {
        $reactions = config("comment.reaction_types");
        $existing_reactions = ReactionType::whereIn("type", $reactions)->get("type")->pluck("type");
        $reactions = collect($reactions);
        $new_reactions = $reactions->diff($existing_reactions);
        $reactions_to_add = [];
        $new_reactions->map(function ($reaction) use (&$reactions_to_add) {
            $reactions_to_add[] = ["type" => $reaction, "created_at" => now(), "updated_at" => now()];
        });
        ReactionType::insert($reactions_to_add);
    }
}
