<?php

namespace BishalGurung\Comment\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidCommentException extends Exception
{
    public function render(Request $request)
    {
        if ($request->wantsJson())
            return response()->json(["message" => "The comment parameter cannot be null or empty"], 500);
    }
}