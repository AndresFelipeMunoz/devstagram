<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class PostController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware 'auth' en todos los métodos, excepto en 'show'
        $this->middleware('auth')->except(['show', 'index']);
    }
    public function index(User $user)
    {
        
        $posts = Post::where('user_id', $user->id)->paginate(10);
     
        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
           'titulo' => 'required|max:225',
           'descripcion' => 'required',
           'imagen' => 'required'
       ]);

       //Post::create([
        //   'titulo' => $request->titulo,
        //   'descripcion' => $request->descripcion,
        //   'imagen' => $request->imagen,
        //   'user_id' => Auth::user()->id
       //]);

       $request->user()->posts()->create([
           'titulo' => $request->titulo,
           'descripcion' => $request->descripcion,
           'imagen' => $request->imagen,
           'user_id' => Auth::user()->id
       ]);

       return redirect()->route('post.index', Auth::user()->username);
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show' , [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        //Elimina imagen
        $imagen_path = public_path('uploads/'.$post->imagen);
        if (File::exists($imagen_path)) {
            unlink($imagen_path);
        }
        return redirect()->route('post.index', Auth::user()->username);
    }
}
