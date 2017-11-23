(function() {
	'use strict';

	angular.module('dashboard.recipes', [])
		.config(configure)

		.controller('Recipes.categories', Recipes_categories)
		.controller('Recipes.list', Recipes_list)
		.controller('Recipes.edit', Recipes_edit)
	;


	configure.$inject = ['$stateProvider'];
	function configure($stateProvider)
	{
		$stateProvider
			.state( 'recipes', {
				url: '/dashboard/recipes',
				abstract : true,
				views : {
					'': {
						templateUrl: '/vendor/cms/app/modules/dashboard/layout.html'
					}
				}
			})

			.state('recipes.categories', {
				url: '/categories',
				views : {
					content: {
						controller: 'Recipes.categories',
						templateUrl:  '/vendor/cms/app/modules/dashboard/recipes/categories.html'
					}
				}
			})
			.state('recipes.list', {
				url: '/list/:category',
				views : {
					content: {
						controller: 'Recipes.list',
						templateUrl:  '/vendor/cms/app/modules/dashboard/recipes/list.html'
					}
				},
				resolve: {
					items : function(HttpService, $stateParams) {
						return HttpService.get('/api/recipes/category/'+ $stateParams.category);
					}
				}
			})

			.state('recipes.edit', {
				url: '/{id:[0-9]{1,8}}',
				views : {
					content: {
						controller: 'Recipes.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/recipes/edit.html'
					}
				},
				resolve: {
					item : function(HttpService, $stateParams) {
						return HttpService.get('/api/recipes/' + $stateParams.id + '/edit');
					}
				}
			})

			.state('recipes.create', {
				url: '/list/:category/create',
				views : {
					content: {
						controller: 'Recipes.edit',
						templateUrl:  '/vendor/cms/app/modules/dashboard/recipes/edit.html'
					}
				},
				resolve: {
					item : function() { return {data: {}}; }
				}
			})



		;
	}

	Recipes_categories.$inject = ['$scope', '$stateParams', 'HttpService', '$state', 'BreadCrumbsService'];
	function Recipes_categories($scope, $stateParams, HttpService, $state, BreadCrumbsService)
	{
		$scope.list = Lang.get('recipes.categories');

		BreadCrumbsService.addCrumb('List categories');
	}

	Recipes_list.$inject = ['$scope', 'items', '$stateParams', 'HttpService', '$state', 'BreadCrumbsService'];
	function Recipes_list($scope, items,  $stateParams, HttpService, $state, BreadCrumbsService)
	{
		//$scope.list = Lang.get('recipes.categories');
		BreadCrumbsService.addCrumb('Categories', 'recipes.categories');
		BreadCrumbsService.addCrumb(Lang.get('recipes.categories.' + $stateParams.category));
		$scope.stateParams = $stateParams;
		$scope.list = items.data.list;

		//Delete recipe
		$scope.removeItem = function(id) {
			HttpService.delete('/api/recipes/' + id)
				.success(function(){
					$state.go($state.current, {}, {reload: true});
				});
		};
	}

	Recipes_edit.$inject = ['$scope', '$stateParams', '$state', 'item', 'BreadCrumbsService', 'HttpService'];
	function Recipes_edit($scope, $stateParams, $state, item, BreadCrumbsService, HttpService ) {
		var edit = typeof item.data.item !== 'undefined';

		//item for images. Multilang hack
		$scope.item = edit ? item.data.item : {};
		$scope.editMode = true;

		//summernote Options redactor
		$scope.options = {
			height: 400,
			focus: true
		};

		BreadCrumbsService.addCrumb('Categories', "recipes.categories");
		BreadCrumbsService.addCrumb(Lang.get('recipes.categories.' + (edit ? $scope.item.category_id : $stateParams.category)),
				"recipes.list({category:'"+(edit ? $scope.item.category_id : $stateParams.category)+"'})");
		BreadCrumbsService.addCrumb(edit ? 'Editing item' : 'Add item');


		//SAVE PERSISTENT
		$scope.save = function() {

			if(edit === false) { //create
				$scope.item.category_id = $stateParams.category;
				HttpService.post('/api/recipes', $scope.item, function(resp) {
					$state.go('recipes.list', {'category' : $stateParams.category})
				});

			} else {
				HttpService.put('/api/recipes/' + $stateParams.id, $scope.item, function(resp) {
					$state.go('recipes.list', {'category' : $scope.item.category_id})
				});
			}
		};
	}


})();



