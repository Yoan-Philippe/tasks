@extends('layouts.master')

@section('title')
    @parent Login
@stop
<!--
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-05-31
Description : Vue de la page connexion, contient une section pour les notifications et un 
formulaire créé avec Blade.
-->

@section('content')

    @if ($message = Session::get('success'))
            <div class="alert alert-success"><img alt="succes" width="18" src="img/alert1.png" />
                {{ $message }}
            </div>
    @endif
    @if ($message = Session::get('signup'))
            <div class="alert alert-success"><img alt="succes" width="18" src="img/alert1.png" />
                {{ $message }}
            </div>
    @endif

    <div class="middle">

        <h2 class="titreLogin">Connecte-toi à ta Todo&nbsp;!</h2>    
        <div class="erreur erreur-login">
            @if ( isset($erreur) )
                	<p>{{ $erreur }}</p>
            @endif
        </div>

        <?php
        if(isset($inputCourriel)){
            $str = $inputCourriel;
        }
        else{
            $str = "";
        }

        if($errors->has('email')) echo '<div class="erreur"><p>' . $errors->first("email") . '</p></div>';
        ?>

        {{ Form::open(array('id' => 'connexion', 'url' => '/','method' => 'post','autocomplete'=>'off')) }}
        {{ Form::label('email','Votre courriel : ') }}
        {{ Form::text('email', $str , array('autofocus'=>'on','class'=>'inputWidth','id'=>'email')) }}
        <p>
        {{ Form::label('password','Mot de passe : ') }}
        {{ Form::password('password', null, array('class'=>'inputWidth','id'=>'password')) }}
        </p>

        {{ Form::submit('OK',array('class'=>'ok')) }}
        {{ Form::close() }}


    </div>
<p>Ou <a class="lienTodo" href="{{ URL::to('signup') }}">créer un compte</a></p>
@stop