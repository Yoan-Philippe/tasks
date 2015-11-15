<?php
/*
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-03-10
Description : Modèle correspondant à la table 'tasks' dans la bd. 
Elle gère les tâches de l'utilisateur connecté appartenant à un projet.
*/

class Task extends Eloquent {

	protected $table = 'tasks';
    protected $fillable = array('id','title','description','priority','isDone','dateFin','project_id');

    //Une tâche appartient à un projet
    public function project()
    {
        return $this->belongsTo('Project');
    }
}