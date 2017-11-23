(function() {

	'use strict';
	angular
		.module("directives.main", [])

		.directive('jumbotron', jumbotron)
		.directive('perfectScrollbar', perfectScrollbar)
		.directive('ngThumb', ngThumb)
		.directive('faq', faq)
		.directive('datepicker', datepicker)
	;

	function datepicker()
	{
		var directive = {
			restrict: 'A',
			link : linkFunc,
			priority: 1
		};

		return directive;

		function linkFunc(scope, element, attrs, ctrls) {
			if(element.context.tagName == 'INPUT') {
				var attr = {
					format: 'mm/dd/yyyy',
					autoclose: true
				};

				if(attrs.format !== undefined) {
					attr.format = attrs.format;
				}

				if(attrs.datepicker.length) {
					var attrCustom = JSON.parse(attrs.datepicker);

					//set min age accepted by picker
					if(attrCustom.min_age !== undefined) {
						attr.viewMode = "years";
						var dt = new Date();
						dt.setFullYear(new Date().getFullYear() - attrCustom.min_age);
						attr.endDate = dt;
					}

					attr = angular.extend(attr, attrCustom);
				}

				if(attrs.id) {
					element.attr('id', attrs.id);
				}

				element.datepicker(attr);

				if(scope.model) {
					element.datepicker('update', scope.model)
				}
			}
		}
	}

	function faq(){
		var directive = {
			restrict: 'EA',
			replace: true,
			templateUrl: '/vendor/cms/app/directives/partials/faq.html',
			link: linkFunc,
			scope : {
				items : '='
			},
			controller: ['$scope', faqCtrl]
		};

		return directive;

		function linkFunc(scope, element, attrs) {
			//var $sidebar = $(element);
			//$sidebar.sidebar($sidebar.data());
		}

		function faqCtrl($scope ) {
			//Init Underscore in template
			$scope._ = _;

			$scope.togglerTab = function (id) {
				if (!$scope.activeTab || $scope.activeTab !== id) {
					$scope.activeTab = id;
				} else {
					$scope.activeTab = false;
				}
			};

		}
	}

	ngThumb.$inject = ['$window'];
	function ngThumb($window) {
		var helper = {
			support: !!($window.FileReader && $window.CanvasRenderingContext2D),
			isFile: function(item) {
				return angular.isObject(item) && item instanceof $window.File;
			},
			isImage: function(file) {
				var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
				return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
			}
		};

		return {
			restrict: 'A',
			template: '<canvas/>',
			link: function(scope, element, attributes) {
				if (!helper.support) return;

				var params = scope.$eval(attributes.ngThumb);

				if (!helper.isFile(params.file)) return;
				if (!helper.isImage(params.file)) return;

				var canvas = element.find('canvas');
				var reader = new FileReader();

				reader.onload = onLoadFile;
				reader.readAsDataURL(params.file);

				function onLoadFile(event) {
					var img = new Image();
					img.onload = onLoadImage;
					img.src = event.target.result;
				}

				$window.requestAnimFrame = (function() {
					return $window.requestAnimationFrame ||
						$window.webkitRequestAnimationFrame ||
						$window.mozRequestAnimationFrame ||
						$window.oRequestAnimationFrame ||
						$window.msRequestAnimationFrame ||
						function(callback, element) {
							$window.setTimeout(callback, 1000 / 60);
						};
				})();

				function onLoadImage() {
					var width = params.width || this.width / this.height * params.height;
					var height = params.height || this.height / this.width * params.width;
					var ctx = canvas[0].getContext('2d');
					var maskwidth = 0;
					canvas.attr({ width: width, height: height });
					ctx.drawImage(this, 0, 0, width, height);

				}
			}
		};
	}

	//function mainHeader() {
	//	var directive = {
	//		restrict: 'EA',
	//		replace: true,
	//		templateUrl: '/vendor/cms/app/directives/partials/header.html',
	//		link: linkFunc,
	//		scope : {},
	//		controller: ['$scope', '$state', navbarCtrl]
	//	};
    //
	//	return directive;
    //
	//	function linkFunc(scope, element, attrs) {
    //
	//	}
    //
	//	function navbarCtrl($scope, $state ) {
	//		$scope.state = $state;
	//	}
	//}
    //
	//function mainFooter() {
	//	var directive = {
	//		restrict: 'EA',
	//		replace: true,
	//		templateUrl: '/vendor/cms/app/directives/partials/footer.html',
	//		link: linkFunc,
	//		scope : {},
	//		controller: ['$scope', '$state', navbarCtrl]
	//	};
    //
	//	return directive;
    //
	//	function linkFunc(scope, element, attrs) {
    //
	//	}
    //
	//	function navbarCtrl($scope, $state ) {
	//		$scope.state = $state;
	//	}
	//}

	//function placeholderNoItems() {
	//	var directive = {
	//		restrict: 'E',
	//		replace: true,
	//		templateUrl: '/vendor/cms/app/directives/partials/placeholder.html',
	//		scope: {
	//			label1: '@',
	//			label2: '@',
	//			btnurl: '@',
	//			btnname: '@'
	//		},
	//		controller: ['$scope', '$state', placeholderCtrl]
	//	};
    //
	//	return directive;
    //
	//	function placeholderCtrl($scope, $state ) {
    //
	//	}
	//}


	perfectScrollbar.$inject = ['$parse', '$window', '$timeout', '$rootScope'];
	function perfectScrollbar($parse, $window, $timeout, $rootScope) {
		var psOptions = [
			'wheelSpeed', 'wheelPropagation', 'minScrollbarLength', 'useBothWheelAxes',
			'useKeyboard', 'suppressScrollX', 'suppressScrollY', 'scrollXMarginOffset',
			'scrollYMarginOffset', 'includePadding'//, 'onScroll', 'scrollDown'
		];


		return {
			restrict: 'EA',
			transclude: true,
			template: '<div><div ng-transclude></div></div>',
			replace: true,
			link: function($scope, $elem, $attr) {
				var jqWindow = angular.element($window);
				var options = {};

				for (var i=0, l=psOptions.length; i<l; i++) {
					var opt = psOptions[i];
					if ($attr[opt] !== undefined) {
						options[opt] = $parse($attr[opt])();
					}
				}


				$scope.$evalAsync(function() {
					$elem.perfectScrollbar(options);
					var onScrollHandler = $parse($attr.onScroll)
					$elem.scroll(function(){
						var scrollTop = $elem.scrollTop()
						var scrollHeight = $elem.prop('scrollHeight') - $elem.height()
						$scope.$apply(function() {
							onScrollHandler($scope, {
								scrollTop: scrollTop,
								scrollHeight: scrollHeight
							})
						})
					});
				});

				function update(event) {
					$scope.$evalAsync(function() {
						if ($attr.scrollDown == 'true' && $attr.down == 'true' && event != 'mouseenter') {
							setTimeout(function () {
								$($elem).scrollTop($($elem).prop("scrollHeight"));
							}, 100);
						}
						$elem.perfectScrollbar('update');
					});
				}

				// This is necessary when you don't watch anything with the scrollbar
				$elem.bind('mouseenter', update('mouseenter'));

				// Possible future improvement - check the type here and use the appropriate watch for non-arrays
				if ($attr.refreshOnChange) {
					$scope.$watchCollection($attr.refreshOnChange, function() {
						update();
					});
				}

				// this is from a pull request - I am not totally sure what the original issue is but seems harmless
				if ($attr.refreshOnResize) {
					jqWindow.on('resize', update);
				}

				$elem.bind('$destroy', function() {
					jqWindow.off('resize', update);
					$elem.perfectScrollbar('destroy');
				});

			}
		};
	}

	function jumbotron() {
		var directive = {
			restrict: 'EA',
			replace: true,
			scope: {
				breadcrumbs: '='
			},
			templateUrl: '/vendor/cms/app/directives/partials/jumbotron.html',
			controller: ['$scope', '$state', jumbotronCtrl]
		};

		return directive;


		function jumbotronCtrl($scope, $state ) {

		}
	}

})();
