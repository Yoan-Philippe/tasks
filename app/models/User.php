<?php
/*
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-03-10
Description : Modèle correspondant à la table 'user' dans la bd. 
Elle gère l'utilisateur connecté.
*/

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';
	protected $hidden = array('password');
	protected $fillable = array('username','email','password','password_confirmation');

	/** Get the unique identifier for the user.
	 * @return mixed */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/** Get the password for the user.
	 * @return string */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**Get the e-mail address where password reminders are sent.
	 * @return string */
	public function getReminderEmail()
	{
		return $this->email;
	}
}