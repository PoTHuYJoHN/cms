<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title ng-bind="getSeoTitle()">Dashboard</title>


	<!-- inject:vendor:js-->
	<script src="/vendor/cms/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="/vendor/cms/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="/vendor/cms/bower_components/perfect-scrollbar/min/perfect-scrollbar.min.js"></script>
	<script src="/vendor/cms/bower_components/photoswipe/dist/photoswipe.js"></script>
	<script src="/vendor/cms/bower_components/photoswipe/dist/photoswipe-ui-default.min.js"></script>
	<script src="/vendor/cms/bower_components/angular/angular.js"></script>
	<script src="/vendor/cms/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
	<script src="/vendor/cms/bower_components/ngDialog/js/ngDialog.min.js"></script>
	<script src="/vendor/cms/bower_components/underscore/underscore-min.js"></script>
	<script src="/vendor/cms/bower_components/angular-local-storage/dist/angular-local-storage.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-file-upload/angular-file-upload.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-loading-bar/build/loading-bar.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-deferred-bootstrap/angular-deferred-bootstrap.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-notification/angular-notification.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-ui-notification/dist/angular-ui-notification.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-bootstrap-datepicker/dist/angular-bootstrap-datepicker.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-bootstrap/ui-bootstrap.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
	<script src="/vendor/cms/bower_components/summernote/dist/summernote.js"></script>
	<script src="/vendor/cms/bower_components/angular-summernote/dist/angular-summernote.js"></script>
	<script src="/vendor/cms/bower_components/moment/min/moment.min.js"></script>
	<script src="/vendor/cms/bower_components/angular-moment/angular-moment.min.js"></script>
	<!-- endinject-->

	<!-- inject:app:js-->
	<script src="/vendor/cms/modules/dashboard/js/sidebar.js"></script>
	<script src="/vendor/cms/app/filters/common.js"></script>
	<script src="/vendor/cms/app/dashboard.js"></script>
	<script src="/vendor/cms/app/components/directives.js"></script>
	<script src="/vendor/cms/app/components/directives-form.js"></script>
	<script src="/vendor/cms/app/components/directives-main.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/navbar/navbar.js"></script>
	<script src="/vendor/cms/app/services/services.js"></script>
	<script src="/vendor/cms/app/components/notify.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/home/home.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/settings/settings.js"></script>
	<script src="/vendor/cms/app/directives/form/form.js"></script>
	<script src="/vendor/cms/app/directives/main/main.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/users/users.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/pages/pages.js"></script>
	<script src="/vendor/cms/app/modules/dashboard/subscribers/subscribers.js"></script>
	<script src="/vendor/cms/app/services/pagination.js"></script>
	<script src="/vendor/cms/js/lang.dist.js"></script>
	<!-- endinject-->

	<!-- inject:libscss:css-->
	<link rel="stylesheet" href="/vendor/cms/modules/dashboard/plugins/pace/pace-theme-flash.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/perfect-scrollbar/min/perfect-scrollbar.min.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/photoswipe/dist/photoswipe.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/photoswipe/dist/default-skin/default-skin.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/ngDialog/css/ngDialog.min.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/ngDialog/css/ngDialog-theme-default.min.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/angular-loading-bar/build/loading-bar.min.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/angular-ui-notification/dist/angular-ui-notification.min.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/angular-bootstrap-datepicker/dist/angular-bootstrap-datepicker.css">
	<link rel="stylesheet" href="/vendor/cms/bower_components/summernote/dist/summernote.css">
	<link rel="stylesheet" href="/vendor/cms/css/dashboard.css">
	<!-- endinject-->

	<link rel="stylesheet" href="/vendor/cms/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/vendor/cms/modules/dashboard/css/pages.css">
	<link rel="stylesheet" href="/vendor/cms/modules/dashboard/css/pages-icons.css">

	<!-- END CSS TEMPLATE -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>



<body class="fixed-header ">

<nav-bar></nav-bar>

<div class="page-container">

	<div class="header ">
		<div class="pull-left full-height visible-sm visible-xs">
			<div class="sm-action-bar">
				<a  class="btn-link toggle-sidebar" data-toggle="sidebar">
					<span class="icon-set menu-hambuger"></span>
				</a>
			</div>
		</div>

		<div class="pull-right">
			<div class="m-t-10">

				<div class="dropdown pull-right">
					<button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
							<span class="semi-bold">{{ Auth::user()->name }}</span> <i class="fa fa-cogs"></i>
						</div>
					</button>
					<ul class="dropdown-menu profile-dropdown" role="menu">
						@if (Auth::guest())
							<li>
								<a href="/auth/login" target="_self"><i class="pg-settings_small"></i> Login</a>
							</li>
							<li>
								<a href="/auth/register" target="_self"><i class="pg-outdent"></i> Register</a>
							</li>
						@else
							<li class="bg-master-lighter" style="margin-top: 0px;">
								<a href="/auth/logout" target="_self" class="clearfix">
									<span class="pull-left">Logout</span>
									<span class="pull-right"><i class="pg-power"></i></span>
								</a>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="page-content-wrapper">

		<div class="content">
			<jumbotron breadcrumbs="breadCrumbs"></jumbotron>

			<div class="full-height full-width" ui-view ></div>
		</div>


		<div class="container-fluid container-fixed-lg footer">
			<div class="copyright sm-text-center">
				<p class="small no-margin pull-left sm-pull-reset">
					<span class="hint-text">Copyright © 2015 </span>
					<span class="font-montserrat">DASHBOARD</span>.
					<span class="hint-text">All rights reserved. </span>
				</p>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<script>
	deferredBootstrapper.bootstrap({
		element: document.documentElement,
		module: 'dashboard',
		resolve: {
			BACKEND_CFG: ['$http', '$q', function ($http, $q) {
				var deferred = $q.defer();

				// GET api cfg token from auth subdomain and use it in future requests.
				$http.get('/cfg').success(function(resp){
					deferred.resolve(resp);
				});

				return deferred.promise;
			}]
		}
	});
</script>

<script>
	{{--var BACKEND_CFG = {!! App\Services\ViewHelpers::getBackendCfg() !!};--}}
</script>

	<div ng-include="'/vendor/cms/app/directives/partials/photoswipe-template.html'"></div>
</body>
</html>
