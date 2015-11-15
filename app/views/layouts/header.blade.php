<?php
/*
Auteur : Yoan Philippe
Date : 2014-05-01
Email : yoan.philippe@hotmail.com
Description : Entete de l'application, contenant le message "Bon matin (Nom de l'utilisateur)"
Contient aussi le formulaire d'ajout de projet et le lien Connexion/Déconnexion
*/
?>
<script type="text/javascript" src="js/moment-with-langs.js"></script>
<?php
if (Auth::check())
{
	$nom = Auth::user()->username; ?>
	<div id="entete">
        <script type="text/javascript">
            /* Calcul le moment de la journée
            Retourne String */
            function getGreetingTime (m) {
                var g = null; //return g
                
                if(!m || !m.isValid()) { return; }
                
                var split_afternoon = 12
                var split_evening = 17
                var currentHour = parseFloat(m.format("HH"));
                
                if(currentHour >= split_afternoon && currentHour <= split_evening) {
                    g = "après-midi";
                } else if(currentHour >= split_evening) {
                    g = "soir";
                } else {
                    g = "matin";
                }
                return g;
            }
            var humanizedGreeting = "Bon " + getGreetingTime(moment());
        </script>
	    <p id='bonjour'><script>document.write(humanizedGreeting);</script>, {{ $nom }}</p>
        <div id="sectionAjouterProjet">
            <div id="ajoutProjet">
                {{ Form::open(array('id' => 'formProject', 'url' => 'ajoutProjet')) }}
                {{ Form::text('titre', Input::old('titre'), array('placeholder'=>'Titre du projet','autocomplete'=>'off','class'=>'titreTacheProjet','maxlength'=>'50')) }}
                {{ Form::submit('',array('class'=>'btAjouterProjet')) }}
                {{ Form::close() }}
            </div>
        </div><!-- fin div sectionAjouterProjet -->
    	<p id='logout'><a id='decoT' href='<?php echo URL::to('logout/' . $nom); ?>'>Déconnexion&nbsp;&nbsp;<img width='20' style='position:absolute' src='img/deco.png' /></a><a id='decoM' href='<?php echo URL::to('logout/' . $nom); ?>'><img width='20' style='position:absolute' src='img/deco.png' /></a></p>
    </div><!-- fin div entete -->
<?php }?>