<?php

namespace BishalGurung\Comment\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidUserException extends Exception
{
    public function render(Request $request)
    {
        if ($request->wantsJson())
            return response()->json(["message" => "User type and id is not setup properly"], 500);
    }
}