(function() {
	'use strict';

	angular.module('dashboard.home', [])
		.config(configure)

		.controller('DashboardHomeCtrl.main', DashboardHomeCtrl_main)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state( 'dashboard', {
				url: '/dashboard',
				abstract : true,
				views : {
					'': {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})
			.state('dashboard.main', {
				url: '',
				views : {
					content: {
						controller: 'DashboardHomeCtrl.main',
						templateUrl:  '/vendor/cms/app/modules/dashboard/home/main.html'
					}
				}
			});
	}

	DashboardHomeCtrl_main.$inject = [];
	function DashboardHomeCtrl_main() {

	}

})();



