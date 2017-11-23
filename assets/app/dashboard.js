(function() {
	'use strict';

	angular.module('dashboard', [
		'ui.router',
		'ui.bootstrap',
		'ngSanitize',
		'ngDialog',
		'summernote', // Editor
		'notification',
		'angularFileUpload',
		'angular-loading-bar',
		'services',
		'directives',
		'directivesForm',
		'common.filters',
		'directives.form',
		'directives.main',
		'dashboard.home',
		'dashboard.settings',
		'dashboard.subscribers',
		'dashboard.users',
		'dashboard.pages',
		'shared.notify',
		'paging'
	])
		.run(runBlock)
		.config(configure);

	//BACKEND_CFG  : array of backend configuration - CSRF_TOKEN, files cfg etc.

	configure.$inject = ['$httpProvider', '$locationProvider', '$urlRouterProvider', 'BACKEND_CFG'];
	function configure($httpProvider, $locationProvider, $urlRouterProvider, BACKEND_CFG)
	{

		$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
		$httpProvider.defaults.useXDomain = true;

		$httpProvider.defaults.headers.common['X-Csrf-Token'] = BACKEND_CFG.CSRF_TOKEN;

		$locationProvider.html5Mode({
			enabled: true,
			requireBase: false
		});

		$urlRouterProvider.otherwise("/dashboard");

		//todo add interceptor

	}

	runBlock.$inject = ['$rootScope','SeoService', 'BreadCrumbsService', 'BACKEND_CFG'];
	function runBlock($rootScope, SeoService, BreadCrumbsService, BACKEND_CFG) {
		$rootScope.$on('$stateChangeStart', function(event, toState){
			BreadCrumbsService.clear();
			$rootScope.stateData = toState.data;
			SeoService.setTitleFromState(toState);
		});

		$rootScope.$on('$stateChangeError', function (event, toState, toParams, fromState, fromParams, error) {
			console.log( 'Resolve Error: ', error);
		});
		$rootScope.$on('$stateChangeSuccess', function (event, to, toParams, from, fromParams) {
			$rootScope.breadCrumbs = BreadCrumbsService.getCrumbs();
		});

		if(BACKEND_CFG.langs.length) {
			$rootScope.langs = BACKEND_CFG.langs;
		}
		//Functions for SEO
		$rootScope.getSeoTitle = SeoService.getTitle;

		$rootScope._ = _;

		// Check if not mobile client
		var isMobile = false;
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) isMobile = true; //&& confirm('Are you on a mobile device?')
		$rootScope.isDesktop = !isMobile;
	}
})();
