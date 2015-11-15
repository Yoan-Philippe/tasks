<?php

/*
Auteur : Yoan Philippe
Date : 2014-05-30
Email : yoan.philippe@hotmail.com
Description : La page Route gère tout les requête URL fait par l'utilisateur,
tous les soumissions de formulaire et les requêtes ajax.
*/

//Ajoute un projet et gère les messages d'erreurs associés
Route::post('ajoutProjet', function()
{
    $messages = array(
    'required' => 'Veuillez insérer un titre de projet.',
    );

    $validator = Validator::make(
    array('name' => Input::get('titre') ),
    array('name' => 'required'),$messages);

    if ($validator->fails())
    {
        $messages = $validator->messages();
        return Redirect::to('/')->withErrors($validator);
    }
    else{
        $id = Auth::user()->id;
        $name = Auth::user()->username;
        $project = new Project();
        $project->name=Input::get('titre');
        $project->user_id=$id;
        $project->save();

        Session::flash('added', "Merci $name, votre projet à bien été ajouté");
        return Redirect::to('/');
    }
});

//Complète une tâche et renvoie le titre en Ajax
Route::get('/doneAjax', function()
{
    $id = $_GET['idTask'];

    $date = time();
    $task = Task::find($id);
    $task->done_at = $date;
    $task->isDone=1;
    $task->save();
        
    $tache = Task::where('id', '=', $id)->get();
    $titre = $tache[0]->title;

    if(Request::ajax()){
        return $titre;
    }
});

//Compte le nombre de tâche et renvoie le nombre en Ajax
Route::get('/taskCount', function()
{
    $id = Auth::user()->id;
    $idProject = $_GET['idProject'];

    $nbrTasks = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->where('tasks.project_id','=',$idProject)->count();

    if(Request::ajax()){
        return $nbrTasks;
    }
});

//Supprime un projet et renvoie le titre en Ajax
Route::get('/deleteProject', function()
{
    $id = $_GET['idProject'];

    $projet = Project::where('id', '=', $id)->get();
    $titre = $projet[0]->name;

    Project::where('id', '=', $id)->delete();

    if(Request::ajax()){
        return $titre;
    }
});

//Supprime une tâche et renvoie le titre en Ajax
Route::get('/deleteAjax', function()
{
    $id = $_GET['idTask'];
    $tache = Task::where('id', '=', $id)->get();
    $titre = $tache[0]->title;

    Task::where('id', '=', $id)->delete();

    if(Request::ajax()){
        return $titre;
    }
});

//Retourne en Ajax un champ textarea avec la veleur actuelle
Route::get('/editDesc', function()
{
    $description = $_GET['description'];
    $str = '<textarea name="desc" class="desc" id="desc">' . $description . '</textarea>';

    if(Request::ajax()){
        return $str;
    }
});

//Renvoie en ajax un champ de saisie avec le titre de la tâche comme value
Route::get('/editinput', function()
{
    $idProjet = $_GET['project'];
    $str = "<input type=\"text\" name=\"title\" id=\"title\" maxlength=\"90\" value=\"$idProjet\" autofocus/>";

    if(Request::ajax()){
        return $str;
    }
});

//Renvoie en ajax un champ de saisie avec le titre du projet comme value
Route::get('/editproject', function()
{
    $idProjet = $_GET['project'];

    $str = '<input type="text" name="titleProject" class="titleProject" value="' . $idProjet . '" autofocus/>';

    if(Request::ajax()){
        return $str;
    }
});

//Sauvegarde dans la bd la description de la tâche et renvoie le nom en ajax
Route::get('/saveDesc', function()
{
    $desc = $_GET['descTache'];
    $id = $_GET['idTache'];

    $task = Task::find($id);
    $task->description = $desc;
    $task->save();

    if(Request::ajax()){
        return $desc;
    }
});

//Sauvegarde dans la bd le titre de la tâche et renvoie le nom en ajax
Route::get('/saveTitle', function()
{
    $titre = $_GET['titreTache'];
    $id = $_GET['idTache'];

    $task = Task::find($id);
    $task->title = $titre;
    $task->save();

    if(Request::ajax()){
        return $titre;
    }
});

//Sauvegarde dans la bd le titre du projet et renvoie le nom en ajax
Route::get('/saveProject', function()
{
    $titre = $_GET['titreProjet'];
    $id = $_GET['idProjet'];

    $projet = Project::find($id);
    $projet->name = $titre;
    $projet->save();

    if(Request::ajax()){
        return $titre;
    }
});

//Si l'utilisateur est connecté, redirige à l'Accueil, sinon affiche la vue Login
Route::get('login', function()
{
    if (Auth::check())
    return Redirect::to('/');
    else
    return View::make('login');
});

//Si l'utilisateur est connecté, redirige à l'Accueil, sinon affiche la vue Signup
Route::get('signup', function()
{
    if (Auth::check())
    return Redirect::to('/');
    else
    return View::make('signup');
});

//Vérifie les infos rentrés, si oui, connecte l'utilisateur
Route::get('/tryLogin', function()
{
    $courriel = $_GET['email'];
    $mdp = $_GET['mdp'];

    $userdata = array(
        'email' => $courriel,
        'password' => $mdp);

    if(Auth::attempt($userdata))
    return "login";
    else{
        if($courriel=="")
        $erreur = "Veuillez remplir les champs ci-dessous.";
        else{
            $inputCourriel = $courriel;
            $user = User::where('email','=',$inputCourriel)->get();
            if($user=="[]")
            $erreur = 'Aucun compte ne correspond à ce courriel';
            else
            $erreur = "Le courriel et le mot de passe ne corresponde pas.";
        }
        if(Request::ajax()){
            return $erreur;
        }
    }
});

//Enregistre un nouvel utilisateur et gère les messages d'erreurs associés
Route::post('signup', function()
{
    $messages = array(
    'required' => 'Le champ :attribute est obligatoire.',
    'min' => 'Le champ :attribute doit contenir au moins :min caractères.',
    'alpha_dash' => 'Veuillez insérer des caratères alpha numérique.',
    'confirmed' => 'Le mot de passe doit être identique.',
    'email' => 'Vérifier le format du courriel.',
    'unique' => 'Le courriel a déja été utilisé.',
    'regex' => 'Veuillez respecter le format requis.',
    );

    $validator = Validator::make(
    array(
        'prenom' => Input::get('prenom'),
        'email' => Input::get('email'),
        'password' => Input::get('password'),
        'password_confirmation' => Input::get('password_confirmation')),
    array(
        'prenom' => 'required|min:3|alpha_dash',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:4|confirmed',
        'password_confirmation' => 'required'
    ),$messages);

    if ($validator->fails())
    {
        $messages = $validator->messages();
        return Redirect::to('signup')->withErrors($validator)->withInput();
    }
    else{
        $userdata = array(
        'username' => Input::get('prenom'),
        'email' => Input::get('email'),
        'password' => Hash::make(Input::get('password')));
        $user = new User($userdata);
        $user->save();
        $name = Input::get('prenom');
        $email = Input::get('email');

        $data = array('email'=>$email,
                        'name'=>$name);

        //Envoie un courriel de confirmation
        Mail::send('emails.inscription', $data, function($m) use ($data)
        {
            $m->to($data['email'])->subject('Merci de votre inscription');
        });

        Session::flash('signup', "Merci $name, votre compte à été enregistré avec succès.");
        return Redirect::to('login');
    }
});

//Déconnecte l'utilisateur et redirige à la page login
Route::get('logout/{name}', function($name){
    Auth::logout();
    Session::flash('success', "Merci $name, votre compte à été déconnecté avec succès.");
    return Redirect::to('login');
});

//Ajoute une tâche et gère les messages d'erreurs associés
//Retourne à l'accueil avec ou sans erreurs
Route::post('ajout', function()
{
    $messages = array(
    'required' => 'Le champ :attribute est obligatoire.',
    'min' => 'Le champ :attribute doit contenir au moins :min caractères.',);

    $validator = Validator::make(
    array(
        'titre' => Input::get('titre')),
    array(
        'titre' => 'required')
    ,$messages);

    if ($validator->fails())
    {
        $value = Input::get('listeProjets');
        $messages = $validator->messages();
        Session::flash('valueProjet',"$value");
        return Redirect::to('/')->withErrors($validator)->withInput();
    }
    else{
        $task = new Task;
        $task->title = Input::get('titre');
        $task->description = Input::get('description');
        $task->priority = Input::get('amount');
        $task->dateFin = Input::get('date');
        $task->project_id = Input::get('listeProjets');
        $task->isDone = 0;
        $task->save();

        $titre = Input::get('titre');

        Session::flash('added',"La tâche <em>$titre</em> a bien été ajouté.");

        return Redirect::to('/');
    }
});

//Appel d'une fonction dans le HomeController pour deleter/compléter une tâche
Route::get('/', 'HomeController@showWelcome');
Route::get('delete/{id}','HomeController@delete');
Route::get('done/{id}','HomeController@complete');