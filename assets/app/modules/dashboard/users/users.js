(function() {
	'use strict';

	angular.module('dashboard.users', [])
		.config(configure)

		.controller('DashboardUsersCtrl', DashboardUsersCtrl)
		.controller('DashboardUsersCtrl.edit', DashboardUsersCtrl_edit)
		.controller('DashboardUsersCtrl.changePassword', DashboardUsersCtrl_changePassword)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state('users', {
				url: '/dashboard/users',
				abstract : true,
				views : {
					'' : {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})
			.state('users.list', {
				url: '',
				views : {
					content: {
						controller: 'DashboardUsersCtrl',
						templateUrl:  '/vendor/cms/app/modules/dashboard/list.html'
					},
					'listItems@users.list': {
						templateUrl: '/vendor/cms/app/modules/dashboard/users/list-item.html'
					}
				},
				resolve: {
					Items : [
						'HttpService',
						function(HttpService) {
						return HttpService.get('/api/users');
						}
					]
				},
				data: {
					urlAdd: 'users.create',
					title: 'List users'
				},
				seo: {
					title: 'List users'
				}
			})
			.state('users.create', {
				url: '/create',
				views: {
					content: {
						controller: 'DashboardUsersCtrl.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/list.html'
					},
					'form_create@users.create': {
						templateUrl: '/vendor/cms/app/modules/dashboard/users/create-form.html'
					}
				},
				resolve: {
					Data : [
						'HttpService',
						function(HttpService) {
							return HttpService.get('/api/users/create');
						}
					]
				},
				data: {
					title: 'Create user'
				},
				seo: {
					title: 'Create user'
				}
			})
			.state('users.edit', {
				url: '/:id/edit',
				views: {
					content: {
						controller: 'DashboardUsersCtrl.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/list.html'
					},
					'form_create@users.edit': {
						templateUrl: '/vendor/cms/app/modules/dashboard/users/edit-form.html'
					}
				},
				resolve: {
					Data : [
						'HttpService',
						'$stateParams',
						function(HttpService, $stateParams) {
							return HttpService.get('/api/users/' + $stateParams.id + '/edit');
						}
					]
				},
				data: {
					title: 'Edit user'
				},
				seo: {
					title: 'Edit user'
				}
			})
			.state('users.changePassword', {
				url: '/changePassword/:id',
				views: {
					content: {
						controller: 'DashboardUsersCtrl.changePassword',
						templateUrl:  '/vendor/cms/app/modules/dashboard/list.html'
					},
					'form@users.changePassword': {
						templateUrl: '/vendor/cms/app/modules/dashboard/users/change-password.html'
					}
				},
				data: {
					title: 'Change user\'s password'
				},
				seo: {
					title: 'Change user\'s password'
				},
			});
	}


	DashboardUsersCtrl.$inject = ['$scope', '$state', 'Items', 'HttpService', 'BreadCrumbsService'];
	function DashboardUsersCtrl($scope, $state, Items, HttpService, BreadCrumbsService) {
		BreadCrumbsService.addCrumb('List users');
		// get users
		$scope.items = Items.data.users;
		// show user's role
		$scope.showRole = function(id) {
			if (id === '1') {
				return 'member'
			} else {
				return 'admin'
			}
		};
		// delete user
		$scope.removeItem = function(item) {
			HttpService.delete('/api/users/' + item.id)
				.success(function () {
					$state.go($state.current, {}, {reload: true});
				});
		}
	}

	DashboardUsersCtrl_edit.$inject = ['$scope', '$state', 'HttpService', 'formErrors', 'Data', 'BreadCrumbsService'];
	function DashboardUsersCtrl_edit($scope, $state, HttpService, formErrors, Data, BreadCrumbsService) {
		BreadCrumbsService.addCrumb('List users', 'users.list');
		// get user if isset
		$scope.item = Data.data.user !== undefined ? Data.data.user : {};
		$scope.roles = Data.data.roles;
		// add breadCrumbs
		if($scope.item.id === undefined) {
			BreadCrumbsService.addCrumb('Create user');
		} else {
			BreadCrumbsService.addCrumb('Edit user');
		}
		// create or update user
		$scope.changeItem = function() {
			if($scope.item.id === undefined) {
				//create user
				HttpService.post('/api/users', $scope.item, function(resp) {
					$state.go('users.list');
				});
			} else {
				//update user
				HttpService.put('/api/users/' + $scope.item.id, $scope.item, function(resp) {
					$state.go('users.list');
				});
			}
		};
	}

	DashboardUsersCtrl_changePassword.$inject = ['$scope', '$state', 'HttpService', '$stateParams', 'formErrors', 'BreadCrumbsService'];
	function DashboardUsersCtrl_changePassword($scope, $state, HttpService, $stateParams, formErrors, BreadCrumbsService) {
		BreadCrumbsService.addCrumb('List users', 'users.list');
		BreadCrumbsService.addCrumb('Change password');
		// item for form change password
		$scope.item = {};
		// change user's password
		$scope.changeItem = function() {
			HttpService.post('/api/users/changePassword/' + $stateParams.id, $scope.item, function(resp){
				$state.go('users.list');
			});
		}
	}

})();
