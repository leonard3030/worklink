<?php

namespace app\middlewares;

use app\models\AdminWritter;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class WriterAuthMiddleware implements IMiddleware
{

    public function handle(Request $request): void
    {
        $user = new AdminWritter();
        if ($user->getLoggedUser() == false) {
            $response = [
                'status' => 'unauthorized',
                'message' => 'Please login to continue...',
            ];
            responce(json_encode($response), 200);
        }
    }
}