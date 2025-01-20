<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth.register');
    }
    
    public function store( Request $request )
    {
        //dd($request->get('name'));
        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);
        //Validacion
         $request->validate([
            'name' => 'required|min:3|max:30',
            'username'=> 'required|unique:users,username|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        //Crear el usuario
        User::create([
            'name'=> $request->name,
            'username'=> $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //Autenticar al usuario
        //Auth::attempt([
        //    'email'=>$request->email,
         //   'password'=>$request->password
        //]);
        //Otra forma de autenticar
        Auth::attempt($request->only('email', 'password'));
        //Redireccionar
        return redirect()->route('post.index');
    }
}
