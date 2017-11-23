(function() {
	'use strict';

	angular.module('dashboard.subscribers', [])
		.config(configure)

		.controller('DashboardSubscribersCtrl', DashboardSubscribersCtrl)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state('subscribers', {
				url: '/dashboard/subscribers',
				abstract : true,
				views : {
					'' : {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})
			.state('subscribers.list', {
				url: '?page',
				views : {
					content: {
						controller: 'DashboardSubscribersCtrl',
						templateUrl:  '/vendor/cms/app/modules/dashboard/list.html'
					},
					'listItems@subscribers.list': {
						templateUrl: '/vendor/cms/app/modules/dashboard/subscribers/list-item.html'
					}
				},
				resolve: {
					Items : [
						'$http',
						'$stateParams',
						function($http, $stateParams) {
							return $http.get('/api/subscribers',
								{
									params: {
										page: $stateParams.page || 1,
										search: $stateParams.search || ''}
								});
						}
					]
				},
				data: {
					title: 'List subscribers'
				},
				seo: {
					title: 'List subscribers'
				}
			});
	}


	DashboardSubscribersCtrl.$inject = ['$scope', '$rootScope', '$state', '$stateParams', 'Items', 'HttpService', 'BreadCrumbsService'];
	function DashboardSubscribersCtrl($scope, $rootScope, $state, $stateParams, Items, HttpService, BreadCrumbsService) {
		BreadCrumbsService.addCrumb('List subscribers');
		// default value for search input
		$scope.search = '';
		$scope.stateParams = $stateParams;
		// get subscribers
		$scope.list = Items.data.data || [];
		// get pagination
		$scope.pagination = Items.data.meta.pagination || {meta: {}};

		// change page in pagination list
		$scope.changePage = function ()
		{
			HttpService.get('/api/subscribers?page=' + $scope.pagination.current_page + '&search=' + $scope.search)
				.then(function (response)
				{
					$scope.list = response.data.data || [];
					$scope.pagination = response.data.meta.pagination || {meta:{}};
				});
		};

		// search by email
		$scope.doSearch = function ()
		{
			HttpService.get('/api/subscribers?page='+ $scope.pagination.current_page + '&search=' + $scope.search)
				.then(function (response)
				{
					$scope.list = response.data.data || [];
					$scope.pagination = response.data.meta.pagination || {meta:{}};
				});
		};

		// delete subscriber
		$scope.removeItem = function(item) {
			HttpService.delete('/api/subscribers/' + item.id)
				.success(function () {
					$state.go($state.current, {}, {reload: true});
				});
		}
	}

})();
