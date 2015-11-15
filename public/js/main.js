$( document ).ready(function() {

	//Affiche le calendrier pour l'ajout de tâche
	var date = new Date();
	date.setDate(date.getDate());
	$('.input-group').datepicker({
       	format: "yyyy-mm-dd",
    	todayBtn: "linked",
    	autoclose: true,
    	todayHighlight: true,
     	startDate: date
       });

	//Fait apparaître le formulaire d'ajout de tâche au clic de l'icone + 
	//ou lorsqu'il y a une erreur lors de la soumission de celui-ci
	$('.modif2').on('click',function(){
		$(this).next().slideToggle();
	});
	if($('.erreur').text()!="")
	$('.erreur').next().next().fadeIn();

	//Animation des éléments d'interface
	$('#bienvenue').delay(800).fadeIn(1800);
	if ($(window).width() > 1200)
	$('#arrow').delay(1500).fadeIn(1800);

	$('.alert-added').fadeIn('slow');
	$('.alert-added').delay(4500).fadeOut('slow');

	//Anime la flèche si l'utilisateur à un seul projet
	if($("#sections .div").length==1)
	{
		if ($(window).width() > 1100) {
			$('#arrow2').delay(1500).fadeIn(1800);
		}
	}

	//Affiche et cache les icônes de complétion et de suppression de tâche au survol
	$('.titreTd').on('mouseenter',function(){	
		$(this).find('a').stop().animate({"opacity":"1"},"normal");
	});
	$('.titreTd').on('mouseleave',function(){
		$(this).find('a').stop().animate({"opacity":"0"},"normal");
	});

	//En requpete ajax, supprime la tâche cliquée et affiche une notification
	$('.lienDelete').on('click',function(){
		tache = $(this).parent().prev().text();
		nombreTache = $(this).parent().parent().parent().children('.titreTd').length;
		if (confirm("Est-tu sûr de vouloir supprimer " +tache + " ?")) {
			value = $(this).attr('id');
			$.get('deleteAjax', {idTask:value}, function(data){
				nombreTache--;
				$('#'+value).parent().parent().parent().prev().prev().html(nombreTache + ' tâches');
    			$('#'+value).parent().parent().html("");
    			$('#'+value).parent().parent().hide();
    			$('.alert-deleted').html("<img alt='deleted' width='18' src='img/alert1.png' />La tâche <em>" + data + "</em> a bien été supprimée.");
    			$('.alert').fadeIn('slow');
				$('.alert').delay(4500).fadeOut('slow');
    		});
		}
		return false;
	});

	//Renvoie vers l'url done/(id de la tâche) après confirmation de l'utilisateur
	$('.lienDone').on('click',function(){
		tache = $(this).parent().prev().text();
		if (confirm("Tu as vraiment complété " + tache + " ?")) {
			idTask = $(this).attr('id');
			window.location = "done/" + idTask;
		}
		return false;
	});

	//Supprime le projet après confirmation au clic du bouton et affiche une notification
	widthASupprimer = 0;
	$('.modif3').on('click',function(){
		if (confirm("Est-tu sûr de vouloir supprimer ce projet ? Cela supprimera aussi les tâches qui y sont associées.")) {
			idProject = $(this).next().attr('id');
			widthASupprimer = widthASupprimer + $(this).parent().width() + 10;
			nbrElement = $('.div').length;
			$.get('deleteProject', {idProject:idProject}, function(data){
				$('#'+idProject).parent().nextAll().delay(300).animate({'left':'-'+widthASupprimer},'slow');
    			$('#'+idProject).parent().html("");
    			$('.alert-deleted').html("<img alt='deleted' width='18' src='img/alert1.png' />Le projet <em>" + data + "</em> a bien été supprimé.");
    			$('.alert').fadeIn('slow');
				$('.alert').delay(4500).fadeOut('slow');

    		});
		}
		return false;
	});

	var bool = true;
	$('.div').last().css('margin-right','20px');

	//Modifie le titre d'une tâche en ajax
	$('.titreTd').find('span.titreAjax').on('click',function(){

		id = $(this).next().find('a').attr('id');

		if(bool==true)
		{
			value = $(this).text();

			$.get('editinput', {project:value}, function(data){
	    		$(".titleTask" + id).html(data);
	    	});
		}
    	bool=false;

    	//Sauvegarde le titre au clic en dehors de celui-ci
    	$(document).mousedown(function (e)
		{
		    var container = $(".titleTask" + id);
		    if (!container.is(e.target) // si l'objet cliqué n'est pas le container
		        && container.has(e.target).length === 0)
		    {
		        value2 = $('#title').val();
	    		if(value2=="")
	    		$(".titleTask" + id).html(value);
	    		else{
	    			$.get('saveTitle', {titreTache:value2,idTache:id}, function(data){
		    		$(".titleTask" +id).html(data);
		    		bool = true;
		    	});
	    		}
		    }
		});

    	//Sauvegarde le titre à l'appuie de la touche ENTER
    	$('body').keyup(function (e) {
			if (e.keyCode == 13) {
	    		value2 = $('#title').val();
	    		$.get('saveTitle', {titreTache:value2,idTache:id}, function(data){
		    		$(".titleTask" +id).html(data);
		    		bool = true;
		    	});
		    }
		});
	});

	//Modifie le titre d'un projet au clic sur bouton Edit en Ajax
	var bool2 = true;
	$('.tooltip').on('click',function(){
		id = $(this).parent().prev().attr('id');
		if(bool2==true)
		{
			value = $(this).parent().prev().text();
			$.get('editproject', {project:value}, function(data){
	    		$('.titreProjet' + id).html(data);
	    		$(".titreProjet" +id).prev().css( "visibility", "visible" );
	    	});
	    }
	    bool2=false;

	    //Sauvegarde le titre au clic en dehors de celui-ci
	    $(document).mousedown(function (e)
		{
		    var container = $("#" +id);
		    if (!container.is(e.target) // si l'objet cliqué n'est pas le container
		        && container.has(e.target).length === 0)
		    {
		        value2 = $('.titleProject').val();
	    		if(value2=="")
	    		$("#" +id).html(value);
	    		else{
	    			$.get('saveProject', {titreProjet:value2,idProjet:id}, function(data){
		    			$(".titreProjet" +id).prev().css( "visibility", "hidden" );
		    			$("#" +id).html(data);
		    			bool2=true;
		    		});
	    		}
		    }
		});

	    //Sauvegarde le titre à l'appuie de la touche ENTER
    	$('body').keyup(function (e) {
			if (e.keyCode == 13) {
	    		value2 = $('.titleProject').val();
	    		if(value2=="")
	    		$(".titreProjet" +id).html(value);
		    	else{
		    		$.get('saveProject', {titreProjet:value2,idProjet:id}, function(data){
		    			$(".titreProjet" +id).prev().css( "visibility", "hidden" );
		    			$(".titreProjet" +id).html(data);
		    			bool2=true;
		    		});
		    	}
		    }
		});
	});
	
	//Modifie le titre du projet en ajax
	$('.titreProjet').on('click',function(){

		id = $(this).attr('id');

		if(bool2==true)
		{
			value = $(this).text();
			$.get('editproject', {project:value}, function(data){
	    		$('#' + id).html(data);
	    		$(".titreProjet" +id).prev().css( "visibility", "visible" );
	    	});
		}
    	bool2=false;

    	//Sauvegarde le titre au clic en dehors de celui-ci
    	$(document).mousedown(function (e)
		{
		    var container = $("#" +id);
		    if (!container.is(e.target) // si l'objet cliqué n'est pas le container
		        && container.has(e.target).length === 0)
		    {
		        value2 = $('.titleProject').val();
	    		if(value2=="")
	    		$("#" +id).html(value);
	    		else{
	    			$.get('saveProject', {titreProjet:value2,idProjet:id}, function(data){
	    				$(".titreProjet" +id).prev().css( "visibility", "hidden" );
		    			$("#" +id).html(data);
		    			bool2=true;
		    		});
	    		}
		    }
		});	
    	
    	//Sauvegarde le titre à l'appuie de la touche ENTER
    	$('body').keyup(function (e) {
			if (e.keyCode == 13) {
	    		value2 = $('.titleProject').val();
	    		if(value2=="")
	    		$("#" +id).html(value);
	    		else{
	    			$.get('saveProject', {titreProjet:value2,idProjet:id}, function(data){
	    				$(".titreProjet" +id).prev().css( "visibility", "hidden" );
		    			$("#" +id).html(data);
		    			bool2=true;
		    		});
	    		}
		    }
		});
	});

	//Modifie la description d'une tâche en ajax
	bool3 = true;
	$('.rightStuff').next().on('click',function(){

		id = $(this).prev().find('a').attr('id');

		if(bool3==true)
		{
			if($(this).text()=="+ Ajouter une description")
			value = '';
			else
			value = $(this).text();

			$.get('editDesc', {description:value}, function(data){
    			$('.rightStuff').find('#'+id).parent().next().html(data);
    			$('textarea').focus();
    		});
		}
		bool3 = false;

		//Sauvegarde la description au clic en dehors de celui-ci
		$(document).mousedown(function (e)
		{
		    var container = $('.rightStuff').find('#'+id).parent().next();
		    if (!container.is(e.target) // si l'objet cliqué n'est pas le container
		        && container.has(e.target).length === 0)
		    {
		        value2 = $('.desc').val();
	    		if(value2=="")
	    		$('.rightStuff').find('#'+id).parent().next().html('+ Ajouter une tâche');
	    		else{
	    			$.get('saveDesc', {descTache:value2,idTache:id}, function(data){
		    			$('.rightStuff').find('#'+id).parent().next().html(data);
		    			bool3=true;
		    		});
	    		}
		    }
		});
	});

	//En requête ajax, vérifie les données entrées, si tout est beau, on redirige à l'accueil
	$('#connexion').on('submit',function(){

		email = $('#email').val();
		mdp = $('#password').val();
		$('.erreur').html("");

		$.get('tryLogin', {email:email,mdp:mdp}, function(data){
			if(data!="login")
			$('.erreur').html(data);
			else
			window.location = "/";
		});
		return false;
	});

	//Si on est sur la page d'accueil, anime le bloc #bonjour
	if (window.location.pathname === '/')
	{
    	$('#bonjour').css({'left':-200});
		$('#bonjour').animate({'left':25},1000);
	}

	//Si on est sur la page Login ou Signup
	if (window.location.pathname === '/login'||window.location.pathname === '/signup')
	{
		$('.alert').fadeIn('slow');
		$('.alert').delay(4500).fadeOut('slow');
	}
});