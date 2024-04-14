<?php

namespace BishalGurung\Comment;

use BishalGurung\Comment\Console\CommentInstallCommand;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerPublishing();
    }

    private function registerPublishing()
    {
        $this->registerMigrations();
        $this->publishes([
            __DIR__ . "/config/comment.php" => config_path("comment.php")
        ], "config");
        $this->commands([CommentInstallCommand::class]);
        $this->loadRoutesFrom(__DIR__ . "/Routes/api.php");
        $this->mergeConfigFrom(__DIR__ . "/config/comment.php", "comment");
    }

    private function registerMigrations()
    {
        $migration_files = [
            "create_comments_table.php",
        ];
        $migration_paths = [];
        foreach ($migration_files as $file) {
            $migration_paths[__DIR__ . "/database/migrations/$file"] = database_path("migrations/") . now ()->format("Y_m_d_His") . "_" . $file;
        }
        $this->publishes($migration_paths, "migration");
    }
}
