<?php

use App\Http\Controllers\Web\TestController;

return [
    "GET" => [
        "/test/1/{slug}" => [TestController::class, "test"]
    ]
];
