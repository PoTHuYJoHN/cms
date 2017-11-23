<!DOCTYPE html>
<html lang="en" ng-app="main">
<head>
	{!! Html::style('css/dashboard.css') !!}
	{!! Html::style('bower_components/font-awesome/css/font-awesome.min.css') !!}
	<!-- BEGIN CSS TEMPLATE -->
	{!! Html::style('modules/dashboard/css/pages.css') !!}
	{!! Html::style('modules/dashboard/css/pages-icons.css') !!}
	<!-- END CSS TEMPLATE -->

</head>

<body class="fixed-header">

<div class="login-wrapper" style="background-color: #000000">

	<div class="bg-pic">
		<img src="/images/site/bg.jpg" alt="" class="home">

		<div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
			<h2 class="semi-bold text-white">
				UkieTech Dashboard
			</h2>
			<p class="small">
				Log in using your credentials to edit your website content. <br/>
				We are sure - you will love it :)
			</p>
		</div>

	</div>

	<div class="login-container bg-white">
		<div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
			<a href="https://www.ukietech.com" target="_blank" nofollow>
				<img src="/vendor/cms/images/ukie-logo.png" alt="logo" title="logo" />
			</a>

			<br/><br/><br/>

			@yield('content')

			<div class="pull-bottom sm-pull-bottom">
				<div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
					<div class="col-sm-3 col-md-2 no-padding">

					</div>
				</div>
			</div>
		</div>
	</div>

</div>

@include('layout-partials/scripts')

</body>
</html>
