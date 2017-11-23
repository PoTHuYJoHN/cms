(function() {
	'use strict';

	angular.module('main.pages', [])
		.config(configure)
		.controller('PagesCtrl.home', PagesCtrlHome)
		.controller('PagesCtrl.about', PagesCtrlAbout)
		.controller('PagesCtrl.contact', PagesCtrlContact)
	;

	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state('langs', {
				url: '/:language',
				abstract: true,
				views: {
					'': {
						templateUrl: '/vendor/cms/app/modules/main/layout.html'
					}
				}
			})
			.state('langs.page_home', {
				url: '',
				views : {
					content: {
						controller: 'PagesCtrl.home',
						templateUrl: '/vendor/cms/app/modules/main/pages/home.html'
					}
				},
				resolve: {
					Data : function(HttpService) {
						return HttpService.get('/api/common/pages/home');
					}
				}
			});
			//.state('lang.page_about', {
			//	url: '/about',
			//	controller: 'PagesCtrl.about',
			//	templateUrl: '/vendor/cms/app/modules/main/pages/about.html',
			//	resolve: {
			//		Data : function(HttpService) {
			//			return HttpService.get('/api/common/pages/about');
			//		}
			//	},
			//	seo : {
			//		title : 'About us'
			//	}
			//})
            //
			//.state('lang.page_contact', {
			//	url: '/contact',
			//	controller: 'PagesCtrl.contact',
			//	templateUrl: '/vendor/cms/app/modules/main/pages/contact.html',
			//	resolve: {
			//		Data : function(HttpService) {
			//			return HttpService.get('/api/common/pages/contact');
			//		}
			//	},
			//	seo : {
			//		title : 'Contact Us'
			//	}
			//})
            //
			//.state('lang.page_contact_success', {
			//	url: '/contact-success',
			//	templateUrl: '/vendor/cms/app/modules/main/pages/contact-success.html',
			//	seo : {
			//		title : 'Contact Success'
			//	}
			//});
			//controller: function($state, $scope) {
			//	$scope.changeLanguage = function(language) {
			//		$state.go($state.current.name, {language: language});
			//	}
			//}
	}

	PagesCtrlHome.$inject = ['$scope', 'Data','SeoService'];
	function PagesCtrlHome($scope, Data, SeoService)
	{
		// Data from api
		$scope.page = Data.data.page;
		$scope.slider = Data.data.page;

		setMetaInfo($scope.page, SeoService);
	}

	PagesCtrlAbout.$inject = ['$scope', 'Data', 'array_util', 'SeoService'];
	function PagesCtrlAbout($scope, Data, array_util, SeoService)
	{
		$scope.page = Data.data.page;
		$scope.members = array_util.partition(Data.members, 2);
		setMetaInfo($scope.page, SeoService);
	}


	PagesCtrlContact.$inject = ['$scope', '$state', 'Data', 'HttpService', 'SeoService'];
	function PagesCtrlContact($scope, $state, Data, HttpService, SeoService)
	{
		$scope.page = Data.data.page;
		$scope.settings = Data.data.settings;
		setMetaInfo($scope.page, SeoService);

		$scope.forms = {};

		$scope.sendMessage = function(message) {

			HttpService.post('/api/messages', $scope.message)
				.success(function(resp){
					$state.go('page_contact_success');
				})
				.error(function(err){ //validation or other error
					$scope.formErrors = err;
				});
		};
	}

	/**
	 * Helper method to set seo values
	 * @param page
	 * @param SeoService
	 */
	function setMetaInfo(page, SeoService) {
		if(page.seo_title) {
			SeoService.setTitle(page.seo_title);
		}

		if(page.seo_description) {
			SeoService.setDescription(page.seo_description);
		}


	}
})();



