<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ {
    Post
};


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'posts' => [
                'popular' => Post::mostPopular(4),
                'recent'  => Post::recentPosts(4)
            ]
        ]);
    }
}
