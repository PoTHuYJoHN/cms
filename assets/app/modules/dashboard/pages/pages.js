(function() {
	'use strict';

	angular.module('dashboard.pages', [])
		.config(configure)

		.controller('Pages.edit', Pages_edit)
		.controller('Pages.list', Pages_list)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state( 'pages', {
				url: '/dashboard/pages',
				abstract : true,
				views : {
					'': {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})

			.state('pages.editById', {
				url: '/{id:[0-9]{1,8}}',
				views : {
					content: {
						controller: 'Pages.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/pages/edit.html'
					}
				},
				resolve: {
					item : function(HttpService, $stateParams) {
						return HttpService.get('/api/landing/' + $stateParams.id + '/edit');
					}
				}
			})

			.state('pages.edit', {
				url: '/:token/:section',
				params: {
					section: {
						value: '',
						squash: true
					}
				},
				views : {
					content: {
						controller: 'Pages.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/pages/edit.html'
					}
				},
				resolve: {
					item : function(HttpService, $stateParams) {
						var url = '/api/landing/editByToken/' + $stateParams.token;

						if($stateParams.section) {
							//if this is sub page then create from config
							return {data: {}};
						}

						return HttpService.get(url);
					}
				}
			})

			.state('pages.list', {
				url: '/list/:token/:section',
				views : {
					content: {
						controller: 'Pages.list',
						templateUrl:  '/vendor/cms/app/modules/dashboard/pages/list.html'
					}
				},
				resolve: {
					items : function(HttpService, $stateParams) {
						return HttpService.get('/api/landing/getByTokenAndSection/'
								+ $stateParams.token + '/' +  $stateParams.section);
					}
				}
			})


		;
	}

	Pages_edit.$inject = ['$scope', '$stateParams', 'BACKEND_CFG', 'MultiLangHelper', 'LandingPageService', '$state', 'item', 'BreadCrumbsService'];
	function Pages_edit($scope, $stateParams, BACKEND_CFG, MultiLangHelper, LandingPageService, $state, item, BreadCrumbsService) {
		var edit = typeof item.data.item !== 'undefined';
		var config,
			backState = {};

		//summernoteOptions redactor
		$scope.options = {
			height: 200,
			focus: true
		};

		if($stateParams.id) {

			config = BACKEND_CFG.pages[item.data.item.section];

			if(config.parent) {
				backState.state = 'pages.list';
				backState.params = {token:config.parent,section:item.data.item.section};
				BreadCrumbsService.addCrumb('List items', "pages.list({token:'"+config.parent+"',section:'"+item.data.item.section+"'})");
			}

			BreadCrumbsService.addCrumb('Editing item');

		} else {

			if($stateParams.section) {
				config = BACKEND_CFG.pages[$stateParams.section];
				//todo check by parent if needed

				if(config.parent) {
					backState.state = 'pages.list';
					backState.params = {token:config.parent,section:$stateParams.section};

					BreadCrumbsService.addCrumb('List items', "pages.list({token:'"+config.parent+"',section:'"+$stateParams.section+"'})");
				}
			} else {
				config = BACKEND_CFG.pages[$stateParams.token];
				backState.state = "dashboard.main";
			}

			BreadCrumbsService.addCrumb(edit ? 'Editing item' : 'Add item');
		}


		$scope.fields = MultiLangHelper.prepareFieldsForLang(config.fields, edit, edit ? item.data.fields : false);

		$scope.editAvatar = false;
		$scope.editGallery = false;

		if(config.coverPhoto) {
			$scope.editCover = true;
			$scope.coverFileType = config.coverPhoto.fileType;
		}

		if(config.gallery) {
			$scope.editGallery = true;
			$scope.galleryFileType = config.gallery.fileType;
		}

		//item for images. Multilang hack
		$scope.item = edit ? item.data.item : {};
		//$scope.item.gallery = Item.images;
		$scope.editMode = true;

		//TABS
		$scope.activeLang = 'en';
		$scope.showLangForm = function (lang) {
			$scope.activeLang = lang;
		};

		//SAVE PERSISTENT
		$scope.saveAll = function() {
			if(edit === false) { //create
				LandingPageService.createPage($scope.fields, $scope.item, null, $stateParams.token, $stateParams.section, function () {
					if(backState) {
						$state.go(backState.state, backState.params);
					} else {
						$state.reload();
					}

				});

			} else {
				LandingPageService.updatePage(item.data.item.id, $scope.fields, $scope.item, function () {
					if(backState) {
						$state.go(backState.state, backState.params);
					} else {
						$state.reload();
					}
				});
			}
		};
	}

	Pages_list.$inject = ['$scope', 'items', '$stateParams', 'HttpService', '$state', 'BreadCrumbsService', 'BACKEND_CFG'];
	function Pages_list($scope, items, $stateParams, HttpService, $state, BreadCrumbsService, BACKEND_CFG)
	{
		BreadCrumbsService.addCrumb('List items');
		$scope.stateParams = $stateParams;

		//$scope.custom_list_title = false;
		//
		//if(typeof BACKEND_CFG.pages[$stateParams.section].list_title !== 'undefined') {
		//	$scope.custom_list_title = BACKEND_CFG.pages[$stateParams.section].list_title;
		//}
		$scope.list = items.data.list;

		//old url staff
		$scope.old_url = false;
		if(typeof BACKEND_CFG.pages[$stateParams.section].old_url !== 'undefined') {
			$scope.old_url = BACKEND_CFG.pages[$stateParams.section].old_url;
		}

		$scope.generateTitle = function(item) {
			if($scope.list) {
				var titleKey = typeof BACKEND_CFG.pages[$stateParams.section].list_title !== 'undefined'
					? BACKEND_CFG.pages[$stateParams.section].list_title
					: 'title';
				var value = _.findWhere(item.fields, {'key': titleKey}).texts[0].value;
				return value ? value : 'Item #' + item.id;
			} else {
				return 'Item #' + item.id;
			}
		};

		//todo parse from config if enable Delete functionality
		$scope.removeItem = function(id) {
			HttpService.delete('/api/landing/' + id)
				.success(function(){
					$state.go($state.current, {}, {reload: true});
				});
		};

		$scope.updateOldUrl = function(id, value) {
			console.log(id, value);
			HttpService.post('/api/landing/' + id + '/updateOldUrl', {old_url : value}).success(function(resp) {
				console.log('saved');
			})
		};
	}
})();



