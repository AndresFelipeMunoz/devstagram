<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el Request
      $request->request->add(['username' => Str::slug($request->username)]);

      $request->validate([
          'username'=> ['required','unique:users,username,'.Auth::user()->id,'min:3','max:20', 'not_in:twitter,facebook,instagram,editar-perfil'],
      ]);

        if($request->imagen){
            $manager = new ImageManager(new Driver());
            $imagen = request()->file('imagen');
    
            $nombreImagen = Str::uuid().".".$imagen->extension();
    
            $imagenServidor = $manager->read($imagen);
            
            $imagenServidor->scale(1000, 1000);
    
            $imagenesPath = public_path('perfiles') . '/' . $nombreImagen;
    
            $imagenServidor->save($imagenesPath);
        }

        //Guardar el cambios
        $usuario = User::find(Auth::user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? Auth::user()->imagen ?? null;
        $usuario->save();

        //Redireccionar
        return redirect()->route('post.index', $usuario->username);
    }
}
