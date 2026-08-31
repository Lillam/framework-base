<?php

namespace App\Http\Controllers\Web;

use Vyui\Foundation\Http\Controller;

class TestController extends Controller
{
    public function test(string $slug)
    {
        dd($slug);
    }
}
