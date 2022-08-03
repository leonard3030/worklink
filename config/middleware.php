<?php
$middleware = [
    "web" => app\middlewares\WebAuthMiddleware::class,
    "writer" => app\middlewares\WriterAuthMiddleware::class,
]

?>
