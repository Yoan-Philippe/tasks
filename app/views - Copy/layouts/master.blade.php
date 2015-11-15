<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/datepicker.min.css">
	<link rel="icon" type="image/png" href="img/favico.png" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" rel="stylesheet">
	
	<!-- title dynamique qui va chercher l'attribut title des vues qui 'extend' la page master -->
	<title> 
		@section('title') 
		Todo | @show
	</title>
</head>
<body>
    @include('layouts.header')
	<div class="container">
	
		<!-- Ici apparaît le contenu des vues qui 'extend' la page master -->
		@yield('content')

	</div>
	<script type="text/javascript" src="js/jquery-1.10.2.js"></script>	
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.4.js"></script>
	<script type="text/javascript" src="js/datepicker.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
</body>
</html>