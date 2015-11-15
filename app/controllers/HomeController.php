<?php
/*
Auteur: Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-04-14
Description : Controlleur principal de l'application, gère la fonction initial 
de la page d'accueil,  la suppression et la complétion des tâches de l'utilisateur.
*/

class HomeController extends Controller {

	//Si l'utilisateur est connecté, affiche la vue Accueil avec des dates dynamique et les projets de l'utilisateur
	//Retourne une vue ou une redirection vers la page Login
	public function showWelcome()
	{
		if(Auth::check())
		{
			$id = Auth::user()->id;

			setlocale(LC_TIME, "fr_FR");
			$today = date("Y-m-d");
			$monday = date("Y-m-d",strtotime( "previous monday" ));
			$sunday = date("Y-m-d",strtotime( "next sunday" ));

			$projet = Project::where('user_id','=',$id)->first();
			$nbrProjet = count($projet);
			$projectsList = Project::where('user_id','=',$id)->get();

			return View::make('accueil',array('date'=>$today,'projectsList'=>$projectsList,'nbrProjet'=>$nbrProjet));
		}
		else{
			return Redirect::to('login');
		}
	}

	//Supprime la tâche de la bd
	//Retourne une redirection vers l'url précédent
	public function delete($id)
	{
		$tache = Task::where('id', '=', $id)->get();
		$titre = $tache[0]->title;

		Task::where('id', '=', $id)->delete();

		Session::flash('deleted',"La tâche <em>$titre</em> a bien été supprimé.");
		return Redirect::to(URL::previous());
	}

	//Complete la tâche et enregistre la date
	//Retourne une redirection vers l'url précédent
	public function complete($id)
	{
		$date = time();
		$task = Task::find($id);
		$task->done_at = $date;
		$task->isDone=1;
		$task->save();
		
		$tache = Task::where('id', '=', $id)->get();
		$titre = $tache[0]->title;

		Session::flash('completed',"La tâche <em>$titre</em> a bien été complété.");
		return Redirect::to(URL::previous());
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
}