(function() {
	'use strict';

	angular.module('main.auth', [])
		.config(configure)

		.controller('AuthCtrl.login', AuthCtrlLogin)
		.controller('AuthCtrl.register', AuthCtrlRegister)
		.controller('AuthCtrl.auth_reset_email', AuthCtrlResetEmail)
		.controller('AuthCtrl.auth_reset', AuthCtrlReset)
		.run(runBlock)
	;
	runBlock.$inject = ['$rootScope'];
	function runBlock($rootScope) {

		$rootScope.isLoggedIn = function() {
			return !!$rootScope.AUTH;
		};
		$rootScope.isAdmin = function() {
			return $rootScope.AUTH &&  $rootScope.AUTH.isAdmin === true;
		}
	}

	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state('lang.auth_login', {
				url: '/auth/login',
				controller: 'AuthCtrl.login',
				templateUrl: '/vendor/cms/app/modules/main/auth/login.html',
				seo : {
					title : 'Sign in'
				}
			})
			.state('lang.auth_register', {
				url: '/auth/register',
				controller: 'AuthCtrl.register',
				templateUrl: '/vendor/cms/app/modules/main/auth/register.html',
				seo : {
					title : 'Sign up'
				}
			})
			.state('lang.auth_reset_email', {
				url : '/password/email',
				controller : 'AuthCtrl.auth_reset_email',
				templateUrl: '/vendor/cms/app/modules/main/auth/reset/email.html',
				seo : {
					title : 'Reset password'
				}
			})
			.state('lang.auth_reset', {
				url : '/password/reset/:token',
				controller : 'AuthCtrl.auth_reset',
				templateUrl: '/vendor/cms/app/modules/main/auth/reset/reset.html',
				seo : {
					title : 'Reset password'
				}
			});
	}


	AuthCtrlLogin.$inject = ['$scope', 'AuthService'];
	function AuthCtrlLogin($scope, AuthService)
	{
		$scope.user = {};
		$scope.forms = {};
		$scope.formErrors = [];

		$scope.login = function(user) {
			$scope.formErrors = [];
			AuthService.login(user, $scope.forms.login, function(errors) {
				$scope.formErrors = errors;
			}, function(authMessage) {
				$scope.authMessage = authMessage;
			});
		}
	}

	AuthCtrlRegister.$inject = ['$scope', 'AuthService'];
	function AuthCtrlRegister($scope, AuthService)
	{
		$scope.user = {};
		$scope.forms = {};
		$scope.formErrors = [];

		$scope.register = function() {
			$scope.user.role_id = 1;
			$scope.formErrors = [];
			AuthService.register($scope.user, function(errors) {
				$scope.formErrors = errors;
			});
		}
	}

	AuthCtrlResetEmail.$inject = ['$scope', 'AuthService'];
	function AuthCtrlResetEmail($scope, AuthService) {
		$scope.item = {};
		$scope.formErrors = [];
		$scope.resetPassEmail = function() {
			AuthService.resetPassEmail($scope.item, function(resp) {
				$scope.formErrors = [];
				$scope.message = resp.message;
			}, function(errors) {
				$scope.formErrors = errors;
			});
		}
	}

	AuthCtrlReset.$inject = ['$scope', 'AuthService', '$stateParams'];
	function AuthCtrlReset($scope, AuthService, $stateParams) {
		$scope.item = {};
		$scope.formErrors = [];
		$scope.resetPass = function() {
			$scope.item.token = $stateParams.token;
			AuthService.resetPass($scope.item, function(resp) {
				$scope.formErrors = [];
				$scope.message = resp.message;

				if(typeof resp.redirect !== 'undefined') {
					window.location.href = resp.redirect;
				}
			}, function(errors) {
				$scope.formErrors = errors;
			});
		}
	}
})();



