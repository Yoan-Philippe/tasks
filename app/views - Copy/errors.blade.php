@extends('layouts.master')

@section('title')
    @parent 404 Not Found
@stop

<!--
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-05-31
Description : Affiche cette page lors d'une erreur 404, cette vue est appelé dans
le répertoire app > start > gloabal.php à la ligne 55 
-->

@section('content')

<?php 
 if (Auth::check())
 { ?>
    <h2 id="titreNotFound">Ben voyon {{ Auth::user()->username; }}, t'as un problème ? </h2>
    <p>Ce serait une bonne idée de retourner à l'<a class="lienTodo" href="/public/">accueil</a></p>
<?php }
else{ ?>
	<h2 id='titreNotFound'>Ben voyon, t'as un problème ? </h2>
	<p>Aupire, <a class="lienTodo" href="/public/">connecte-toi</a></p>
<?php } ?>

@stop