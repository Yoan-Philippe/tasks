<?php
/*
Auteur : Yoan Philippe
Date : 2014-03-08
Email : yoan.philippe@hotmail.com
Description : Page d'une migration permettant de créer uen table dans la bd pour les users.
*/

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
	    {
	        $table->increments('id');
	        $table->string('username');
	        $table->string('email')->unique();
	        $table->string('password');
	        $table->timestamps();
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}