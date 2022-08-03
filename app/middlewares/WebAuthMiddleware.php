<?php

namespace app\middlewares;

use app\models\User;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class WebAuthMiddleware implements IMiddleware
{

    public function handle(Request $request): void
    {
        $user = new User();
        if (!$user->getLoggedUser()) {
            $response = [
                'status' => 'unauthorized',
                'message' => 'Please login to continue...',
            ];
            responce(json_encode($response), 401);
        }
    }
}