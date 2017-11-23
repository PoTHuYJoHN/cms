(function() {
	'use strict';

	angular.module('dashboard.settings', [])
		.config(configure)

		.controller('DashboardSettingsCtrl', DashboardSettingsCtrl)
		.controller('DashboardSettingsCtrl.edit', DashboardSettingsCtrl_edit)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{

		$stateProvider
			.state('settings', {
				url: '/dashboard',
				abstract : true,
				views : {
					'' : {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})
			.state('settings.list', {
				url: '/settings',
				views : {
					content: {
						controller: 'DashboardSettingsCtrl',
						templateUrl:  '/vendor/cms/app/modules/dashboard/settings/list.html'
					},
					'form@settings.list': {
						templateUrl: '/vendor/cms/app/modules/dashboard/settings/group-changing.html'
					}
				},
				resolve: {
					Items : [
						'HttpService',
						function(HttpService) {
							return HttpService.get('/api/settings');
						}
					]
				},
				data: {
					title: 'Settings'
				},
				seo: {
					title: 'Settings'
				}
			});
			//UNCOMMENT IF NEEDED
			//.state('settings.create', {
			//	url: '/create',
			//	controller: 'DashboardSettingsCtrl.edit',
			//	templateUrl: '/vendor/cms/app/modules/dashboard/settings/create.html',
			//	resolve: {
			//		Item : function() {
			//			return null;
			//		}
			//	}
			//})
			//.state('settings.edit', {
			//	url: '/:id/edit',
			//	controller: 'DashboardSettingsCtrl.edit',
			//	templateUrl: '/vendor/cms/app/modules/dashboard/settings/edit.html',
			//	resolve: {
			//		Item : function(DashboardSettingsResource, $stateParams) {
			//			return DashboardSettingsResource.get({ id : $stateParams.id}).$promise;
			//		}
			//	}
			//})
			//.state('settings.destroy', {
			//	url: '/:id',
			//	controller: 'DashboardSettingsCtrl.edit',
			//	resolve: {
			//		Item : function(HttpService, $stateParams) {
			//			return HttpService.delete('/api/settings/' + $stateParams.id);
			//			//return DashboardSettingsResource.remove({ id : $stateParams.id}).$promise;
			//		}
			//	}
			//});
	}

	DashboardSettingsCtrl.$inject = ['$scope', 'Items', 'HttpService', '$state', 'BreadCrumbsService', 'Notify'];
	function DashboardSettingsCtrl($scope, Items, HttpService, $state, BreadCrumbsService, Notify) {
		$scope.items = Items.data.data;
		BreadCrumbsService.addCrumb('Settings');

		$scope.saveAllItems = function(items) {
			var data = {};
			angular.forEach(items, function(item, i) {
				data[item.key] = item.value;
			}, data);
			HttpService.post('/api/settings/updateAll', data, function () {
				Notify.success('Changes Saved!');
				$state.go('dashboard.main');
			});
		}
	}

	DashboardSettingsCtrl_edit.$inject = ['$scope', '$state', 'HttpService', 'formErrors', 'Item'];
	function DashboardSettingsCtrl_edit($scope, $state, HttpService, formErrors, Item) {

		//UNCOMMENT IF NEEDED

		//$scope.item = Item ? Item.data : {}  ;
		//$scope.forms = {};
		//
		//$scope.saveItem = function(item) {
		//
		//	if($scope.item.id === undefined) { //create
		//		HttpService.post('/api/settings', item)
		//			.success(function(){
		//				$state.go('settings.list');
		//			})
		//			.error(function(err){
		//				formErrors.handle($scope.forms.formAdd, [err.data]);
		//			});
		//	} else { //update
		//		HttpService.put('/api/settings/' + item.id, item)
		//			.success(function(){
		//				$state.go('settings.list');
		//			})
		//			.error(function(err){
		//				formErrors.handle($scope.forms.formAdd, [err.data]);
		//			});
		//	}
		//};
	}

})();



