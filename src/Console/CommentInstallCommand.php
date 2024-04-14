<?php

namespace BishalGurung\Comment\Console;

use Illuminate\Console\Command;
use BishalGurung\Comment\Models\Comment;
use BishalGurung\Comment\Models\Reaction;

class CommentInstallCommand extends Command
{
    protected $signature = "comment:install";

    public function handle()
    {
        $this->migrateReactions();
    }

    private function migrateReactions()
    {
        $reactions = config("comment.reactions");
        $existing_reactions = Reaction::whereIn("type", $reactions)->get("type")->pluck("type");
        $reactions = collect($reactions);
        $new_reactions = $reactions->diff($existing_reactions);
        $reactions_to_add = [];
        $new_reactions->map(function ($reaction) use (&$reactions_to_add) {
            $reactions_to_add[] = ["type" => $reaction, "created_at" => now(), "updated_at" => now()];
        });
        Reaction::insert($reactions_to_add);
    }
}
