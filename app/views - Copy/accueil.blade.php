@extends('layouts.master')

@section('title')
    @parent Accueil
@stop

<!--
Auteur : Yoan Philippe
Email : yoan.philippe@hotmail.com
Date : 2014-06-02
Description : Vue principale de l'application, l'accueil, qui gère tout le contenu de
l'application à l'aide des variable qu'elle reçoit du controller. Elle affiche les
notifications, les erreurs, les projets et leurs tâches.
-->

@section('content')

<script type="text/javascript">
moment.lang('fr');
</script>

<div class="alert alert-deleted"><img alt="deleted" width="18" src="img/alert1.png" /></div>

<!-- Gère les notifications -->
@if (($message = Session::get('emailSuccess'))||($message = Session::get('deletedProject')))
        <div class="alert-added"><img alt="deleted" width="18" src="img/alert1.png" />
            {{ $message }}
        </div>
@endif
@if ($message = Session::get('added'))
        <div class="alert-added"><img alt="deleted" width="18" src="img/alert1.png" />
            {{ $message }}
        </div>
@endif
@if ($message = Session::get('completed'))
        <div class="alert-added"><img alt="completed" width="18" src="img/alert1.png" />
            {{ $message }}
        </div>
@endif

	<?php
    if($errors->has('date'))
    $class = "error";
    else
    $class = "";

    $bool = true;

    if($nbrProjet==0)
    echo "<p id='bienvenue'>Bienvenue dans votre Todo, ajoutez un projet pour débuter.</p><img alt='arrow' src='img/arrow.png' id='arrow' width='70' />"; ?>

<div id="sections">

<?php $cpt = 0; ?>
        @if ( $errors->count() > 0&&$errors->has('name') )
            @foreach( $errors->all() as $message )
            <div class='erreur'>
                <p>{{ $message }}</p>
            </div>
            @endforeach
        @endif

    @foreach($projectsList as $cle)
        <?php
        $cpt++;
        $idProjet = $cle->id;
        $id = Auth::user()->id;
        setlocale(LC_TIME, "fr_FR");
        $today = date("Y-m-d");
        $monday = date("Y-m-d",strtotime( "previous monday" ));
        $sunday = date("Y-m-d",strtotime( "next sunday" ));

        $tasksWeek = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->whereBetween('dateFin', array($monday, $sunday))->where('tasks.project_id','=',$idProjet)->orderBy('tasks.priority', 'asc')->get(array('tasks.*'));
        $tasksAll = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->whereNotBetween('dateFin', array($monday, $sunday))->where('tasks.dateFin','!=',$today)->where('tasks.project_id','=',$idProjet)->orderBy('tasks.priority', 'asc')->get(array('tasks.*'));

        $nbrTasks = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->where('tasks.project_id','=',$idProjet)->count();
            
        $nbrTasksAll = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->whereNotBetween('dateFin', array($monday, $sunday))->where('tasks.dateFin','!=',$today)->where('tasks.project_id','=',$idProjet)->count();
        $nbrTasksWeek = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',0)->whereBetween('dateFin', array($monday, $sunday))->where('tasks.project_id','=',$idProjet)->count();

        $listeFait = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('isDone', '=', 1)->where('tasks.project_id','=',$idProjet)->orderBy('done_at', 'desc')->get(array('tasks.*'));

        $nbrTasksDoneAll = Task::join('projects','tasks.project_id','=','projects.id')->where('projects.user_id','=',$id)->where('tasks.isDone','=',1)->where('tasks.project_id','=',$idProjet)->count();
        ?>
        <div class="div">
            <div class="modif3">
            <a class="iconDeleteProject tooltip4" title="Supprimer le projet" ><img width="25" src="img/icon_delete.png" alt="Delete"></a></div>
            <h2 id="{{ $cle->id }}" class="titreProjet titreProjet{{ $cle->id }}">{{ $cle->name }}</h2>
            
            <div class="modif">
                <a class="tooltip" title="Éditer le projet"><img alt="tool" src="img/tool.png" class="iconTool" /></a>
            </div>
            <?php if($nbrTasks<2)
            $strElement = "tâche";
            else
            $strElement = "tâches"; ?>
            <p class="nbrTask">{{ $nbrTasks }} {{ $strElement}}<p>

            <div class="sectionProject">
            @if ($message = Session::get('valueProjet'))
            <?php
            if($message==$cle->id){
                if($bool==true)
                { ?>
                    @if ( $errors->count() > 0 )
                    <div class="erreur">
                    <ul style="margin-bottom:0;padding-left: 20px;">
                        @foreach( $errors->all() as $message )
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    </div> 
                    @endif
                    
               <?php
               $bool=false; } 
           } ?>
            @endif 
                <div class="modif2">
                <a class="tooltip3" title="Ajouter une tâche"><img alt="add" class="addTask" src="img/add.png" /></a>
            </div>
                
                <div class="addForm" id="<?php echo $cle->id; ?>">
                    {{ Form::open(array('id' => 'formID', 'url' => 'ajout')) }}
                {{ Form::text('titre', Input::old('titre'), array('placeholder'=>'Insérer un titre','class' => $errors->has('titre') ? 'error titreTache' : 'titreTache','maxlength'=>'90','autocomplete' => 'off','autofocus'=>'on')) }} 

                <div class="container2">
                    <div class="row">
                        <div class='col-sm-6'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker5' data-date-format="DD/MM/YYYY">
                                    <input name='date' type='text' placeholder="Calendrier" autocomplete="off" class="form-control inputDate {{ $class }}" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="listeProjets" value="<?php echo $idProjet; ?>" />
                <div class='buttonPriority'>
                    <input type="radio" class="radioCacher" id="normal{{ $cpt }}" value="1" name="amount" checked="checked">
                    <label class='normalLabel' for="normal{{ $cpt }}">Normal</label>
                    <input type="radio" class="radioCacher" value="2" id="prioritaire{{ $cpt }}" name="amount">
                    <label class="prioritaireLabel" for="prioritaire{{ $cpt }}">Prioritaire</label>
                    <input type="radio" class="radioCacher" id="urgent{{ $cpt }}" value="3" name="amount">
                    <label class="urgentLabel" for="urgent{{ $cpt }}">Urgent</label>
                </div>
                {{ Form::textarea('description', null, array('id'=>'description','placeholder'=>'Insérer une description','maxlength'=>'200')) }} 
                {{ Form::submit('Ajouter',array('class'=>'btAjouter')) }}
                {{ Form::close() }}
                </div>

                <?php if($nbrTasks==0)
                {
                    echo "<h3 class='titreTh'>Mes tâches</h3>";
                    echo "<p>Aucune tâche pour le moment</p>";
                }
                else{   
                    if($nbrTasksWeek!=0)
                    { ?>
                        <h3 class="titreTh">Cette semaine</h3>
                        
                        @foreach($tasksWeek as $cle)
                        <?php
                        $dates = $cle->dateFin;
                        $date = new DateTime($dates);
                        $result = $date->format('Y-m-d');
                        $date2 = new DateTime();
                        $result2 = $date2->format('Y-m-d');
                        $time1 = strtotime($result);
                        $time2 = strtotime($result2);
                        $str = ($time1 - $time2)/60;
                        $jour = ($str / 60)/24;
                        $data_inicial = strftime("%d %B %Y", strtotime(trim($result))); ?>
                        <?php
                        if($cle->description!=null)
                        $description = '<span class="descriptionTache">' . $cle->description . '</span>';
                        else
                        $description = '<span class="descriptionTache">+ Ajouter une description</span>';

                        if($jour == 1)
                        $strJour = "Demain";
                        else
                        {
                            if($jour>0)
                            $strJour = 'Dans ' . $jour . ' jours';
                            else
                            {
                                if($jour==0)
                                $strJour = "Aujourd'hui";
                                else
                                {
                                    if($jour<=-16000)
                                    $strJour = "Quand tu veux";
                                    else
                                    $strJour = 'Il y a ' . str_replace('-', '', $jour) . ' jours';
                                }
                            }
                        }
                        echo '<p class="titreTd marginBottom"><span class="cercle' . $cle->priority . '"></span><span class="titleTask' . $cle->id . ' titreAjax">' . $cle->title . '</span><span class="rightStuff">' . $strJour . '<a id="' . $cle->id . '" class="lienDone" href="done/' . $cle->id . '"><img width="25" class="iconDone" src="img/check2.jpg" alt="Done"></a><a id="' . $cle->id . '" class="lienDelete"><img width="20" class="iconDelete2" src="img/delete.jpg" alt="Done"></a></span>' . $description . '</p>';
                        ?>
                    @endforeach
                    <?php }

                    if($nbrTasksAll!=0)
                    { 
                        if($nbrTasksWeek==0)
                        echo "<h3 class='titreTh'>Mes tâches</h3>";
                        else
                        echo "<h3 class='titreTh top'>Autres tâches</h3>";
                        ?>
                        @foreach($tasksAll as $cle)
                        <?php
                        $dates = $cle->dateFin;
                        $date = new DateTime($dates);
                        $date2 = new DateTime();
                        $result = $date->format('Y-m-d');
                        $result2 = $date2->format('Y-m-d');
                        $time1 = strtotime($result);
                        $time2 = strtotime($result2);
                        $str = ($time1 - $time2)/60;
                        $jour = ($str / 60)/24;
                        $data_inicial = strftime("%d %B %Y", strtotime(trim($result)));
                        if($cle->description!=null)
                        $description = '<span class="descriptionTache">' . $cle->description . '</span>';
                        else
                        $description = '<span class="descriptionTache">+ Ajouter une description</span>';

                      if($jour == 1)
                        $strJour = "Demain";
                        else
                        {
                            if($jour>0)
                            $strJour = 'Dans ' . $jour . ' jours';              
                            else
                            {
                                if($jour==0)
                                $strJour = "Aujourd'hui";
                                else
                                {
                                    if($jour<=-16000)
                                    $strJour = "Quand tu veux";
                                    else
                                    $strJour = 'Il y a ' . str_replace('-', '', $jour) . ' jours';
                                }
                            }
                        }
                        echo '<p class="titreTd marginBottom"><span class="cercle' . $cle->priority . '"></span><span class="titleTask' . $cle->id . ' titreAjax">' . $cle->title . '</span><span class="rightStuff">' . $strJour . '<a id="' . $cle->id . '" class="lienDone" href="done/' . $cle->id . '"><img width="25" class="iconDone" src="img/check2.jpg" alt="Done"></a><a id="' . $cle->id . '" class="lienDelete"><img width="20" class="iconDelete2" src="img/delete.jpg" alt="Done"></a></span>'  . $description . '</p>';
                        ?>
                    @endforeach
                    <?php }
                }

                if($nbrTasksDoneAll!=0)
                { ?>
                        <h3 class="titreTh top">Tâches complétées</h3>
                        
                        <?php $cpt=0; ?>
                        @foreach($listeFait as $cle)
                        <?php $time = $cle->done_at;
                        $cpt++;
                        if($cpt<4)
                        {
                            $dateFini = date('Y-m-d',$time); ?>
                            <script>
                            var date = moment("<?php echo $dateFini ?>", "YYYY-MM-DD").fromNow();
                            var date1 = moment("<?php echo $dateFini ?>").format('YYYY-MM-DD');
                            var today = moment().format('YYYY-MM-DD');

                            if(date1==today)
                            var dateAfficher = "Aujourd'hui";
                            else
                            var dateAfficher = date;
                            </script>
                            <p class='titreTd' style="height:40px"><span class="cercle4"></span><span class="titleComplete">{{ $cle->title }}</span><span class="rightStuff"><script type="text/javascript">document.write(dateAfficher)</script></span></p>
                        <?php } ?>
                        
                        @endforeach
                <?php } ?>

            </div> <!-- din fiv sectionProject -->
            <?php 
            if($nbrTasks==0&&$nbrProjet==1)
            echo "<img alt='arrow' src='img/arrow2.png' id='arrow2' width='70' />"; 
            ?>
        </div> <!-- fin div .div -->
    @endforeach

</div>  <!-- fin div sections -->
<div id="clear" style="clear:both"></div>
	
@stop
