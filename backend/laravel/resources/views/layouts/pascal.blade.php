@extends('layouts.base')

@php
    $social_image = 'img/pascalsommerphotography.jpg';
    if(request()->has('meta_img')){
        $id = request()->query('meta_img');

        if($photo = \App\Photo::find($id)){
            $social_image = $photo->imgPath();
        }
    }
@endphp

@section('social_image', asset($social_image))

@section('content-base')

@yield('content')

<br><br><br><br>
<footer class="alignright">
    <p>Copyright &copy;2013-{{ date('Y') }} Pascal Sommer<br>
    All Rights Reserved</p>
</footer>

@endsection