@extends('layouts.master')

@section('title')
    @parent Signup
@stop

<!--
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-05-31
Description : Vue de la page création de compte, contient un formulaire construit avec la
syntaxe de Blade.
-->

@section('content')

<div class="middle2">

    <h2 class="titreCreerCompte">Créer un compte</h2>

        {{ Form::open(array('id' => 'creerCompte', 'url' => 'signup','method' => 'post')) }}
        <p>
        {{ Form::label('prenom','Prénom : ') }}
        {{ Form::text('prenom', Input::old('prenom'), array('class' => $errors->has('prenom') ? 'error inputWidth' : 'inputWidth','id'=>'prenom')) }} 
        <?php if($errors->has('prenom')) echo '<span class="errorMessage">' . $errors->first("prenom") . '</span>'; ?>
        </p>
        <p>
        {{ Form::label('email','Courriel : ') }}
        {{ Form::text('email', Input::old('email'), array('class' => $errors->has('email') ? 'error inputWidth' : 'inputWidth','id'=>'email')) }}
        <?php if($errors->has('email')) echo '<span class="errorMessage">' . $errors->first("email") . '</span>'; ?>
        </p>
        <p>
        {{ Form::label('password','Mot de passe : ') }}
        {{ Form::password('password', array('class' => $errors->has('password') ? 'error inputWidth' : 'inputWidth')) }}
        <?php if($errors->has('password')) echo '<span class="errorMessage">' . $errors->first("password") . '</span>'; ?>
        </p>
        <p>
        {{ Form::label('password_confirmation','Confirmation : ') }}
        {{ Form::password('password_confirmation', array('class' => $errors->has('password_confirmation') ? 'error inputWidth' : 'inputWidth')) }}
        <?php if($errors->has('password_confirmation')) echo '<span class="errorMessage">' . $errors->first("password_confirmation") . '</span>'; ?>
        </p>
        {{ Form::submit('Enregistrer',array('class'=>'ok')) }}
        {{ Form::close() }}

</div>

<p>Ou <a class="lienTodo" href="{{ URL::to('login') }}">se connecter</a></p>
	  
@stop