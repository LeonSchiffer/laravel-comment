<?php

use BishalGurung\Comment\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "api", "middleware" => ["api"]], function () {
    Route::post("/comment/{comment}/react", [ReactionController::class, "storeCommentReaction"]);
});