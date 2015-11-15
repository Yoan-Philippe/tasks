<?php
/*
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-03-10
Description : Modèle correspondant à la table 'project' dans la bd. 
Elle gère les projets de l'utilisateur connecté contenant leurs tâches.
*/

class Project extends Eloquent {

	protected $table = 'projects';
    public $timestamps = false;

    //Un projet appartient à un utilisateur
	public function user()
    {
        return $this->belongsTo('User');
    }

    //Un projet contient plusieurs tâches
    public function tasks()
    {
        return $this->hasMany('Task');
    }

}