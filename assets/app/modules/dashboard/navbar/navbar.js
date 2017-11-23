(function() {
	'use strict';

	angular.module("dashboard")
		.directive('navBar', navbar)

	;

	function navbar() {
		var directive = {
			restrict: 'EA',
			controller: navbarCtrl,
			replace: true,
			scope: {},
			templateUrl: '/vendor/cms/app/modules/dashboard/navbar/navbar.html',
			link: linkFunc
		};

		return directive;

		function linkFunc(scope, element, attrs){
			var $sidebar = $('.page-sidebar');
			$sidebar.sidebar($sidebar.data());
			//console.log($sidebar);
		}

		function navbarCtrl( $scope, $state ) {
			$scope.state = $state;

		}
	}
})();
