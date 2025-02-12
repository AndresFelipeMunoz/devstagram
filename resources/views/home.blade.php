@extends('layouts.app')

@section('title')
        pagina principal

@endsection

@section('content')
      
    <x-listar-post :posts="$posts" />
    
   
@endsection