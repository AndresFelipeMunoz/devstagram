<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function __invoke()
    {
         $ids = Auth::user()->followings->pluck('id')->toArray();
         $posts = Post::whereIn('user_id', $ids)->paginate(10);
         
         
        return view('home', [
            'posts' => $posts
        ]);
    }
    
    
}
