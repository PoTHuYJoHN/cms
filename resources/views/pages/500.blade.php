<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERROR 500</title>


	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
               	<link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
               	<link href="{{ URL::asset('css/keyframes.css') }}" rel="stylesheet">
               	<link href="{{ URL::asset('css/new-website.css') }}" rel="stylesheet">
               	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
               	<script src="{{ URL::asset('js/modernizr.custom.79639.js') }}"></script>



</head>
<body class="error">
	<div class="container">
		<i class="ico-logo"></i>
		<i class="ico-logo-text"></i>
		<img src="{{ URL::asset('images/errors/500.png') }}" alt=""/>
		<p class="upper">Something has gone seriously wrong.</p>
		<p>We're experiencing internal server problem. Please try back later.</p>
		<div>
			<a class="btn-cast" data-wipe="CONTACT US" href="/">
				Go home
			</a>
		</div>

	</div>

	<script type="text/javascript">

		 $('.powEfect').on('click', function(e){
			var parent, ink, d, x, y;
			parent = $(this);
			if(parent.find(".ink").length == 0)
			      parent.prepend("<span class='ink'></span>");
			ink = parent.find(".ink");
			ink.removeClass("animate");
			if(!ink.height() && !ink.width())
			     {
				 d = Math.max(parent.outerWidth(), parent.outerHeight());
				 ink.css({height: d, width: d});
			     }
			x = e.pageX - parent.offset().left - ink.width()/2;
			y = e.pageY - parent.offset().top - ink.height()/2;
			ink.css({top: y+'px', left: x+'px'}).addClass("animate");

		 });

	</script>
</body>
</html>
