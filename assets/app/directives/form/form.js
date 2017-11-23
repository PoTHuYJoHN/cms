(function() {
	'use strict';

	angular.module('directives.form', [])

		.directive('form', form)

		.directive('customInput', customInput)
		.directive('customTextarea', customTextarea)
		.directive('customSelect', customSelect)
		.directive('customCheckbox', customCheckbox)
		.directive('customCheckboxMultiple', customCheckboxMultiple)
		.directive('customRadio', customRadio)

		.directive('widgetAvatar', widgetAvatar)
		.directive('widgetAttachments', widgetAttachments)
	;



	/* Fn
	 ============================================================================================================ */

	function widgetAvatar() {
		var directive = {
			require: [],
			restrict: 'E',
			templateUrl: '/vendor/cms/app/directives/widgets/avatar.html',
			scope: {
				fileToken: '=',
				fileType: '@',
				label: '@',
				fileSize: '@',
				croparea: '=',
				cropEnable: '@'
			},
			controller: ['$scope', 'BACKEND_CFG',  'FileUploader', 'Notify', controllerFunc],
			replace: true,
			priority: 10
		};

		return directive;

		function controllerFunc($scope, BACKEND_CFG, FileUploader, Notify)
		{
			$scope.progress = 0;

			$scope.fileSize = $scope.fileSize || 'preview';

			$scope.remove = function () {
				$scope.fileToken = '';
			};

			//if($scope.$parent.editMode) {
			$scope.uploader = new FileUploader({
				url: '/api/common/files?type='+$scope.fileType,
				headers : {'X-CSRF-TOKEN' : BACKEND_CFG.CSRF_TOKEN},
				autoUpload: true,
				removeAfterUpload: true,
				queueLimit: 1,
				onBeforeUploadItem: function () {
				},
				onProgressAll: function (progress) {
					$scope.progress = progress;
				},
				onCompleteItem : function (item, response, status, headers) {
					$scope.fileToken = response.token;
				},
				onErrorItem: function (item, response) {
					Notify.alert(response.message ? response.message : 'Something went wrong. Please refresh page and try again.');
				},
				onCompleteAll: function () {
					$scope.progress = 0;
				}
			});

			// FILTERS
			$scope.uploader.filters.push({
				name: 'imageFilter',
				fn: function(item /*{File|FileLikeObject}*/, options) {
					var is_image = item.type == 'image/jpeg' || item.type == 'image/jpg' || item.type == 'image/png';
					if(!is_image) {
						Notify.alert(Lang.get('general.uploader_error_extension'));
					}
					return is_image;
				}
			});

		}
	}

	widgetAttachments.$inject = ['FileService', 'BACKEND_CFG'];
	function widgetAttachments(FileService, BACKEND_CFG)
	{
		var directive = {
			restrict: 'E',
			templateUrl: templateFunc,
			scope: {
				fileType: '@',
				files: '=', // Array of uploaded files
				parentId: '=',
				limit: '@',
				label: '@',
				isgallery: '@',
				placeholder: '@',
				name: '@'
			},
			replace: true,
			priority: 10,
			controller: ['$scope', 'Notify', 'FileUploader', 'FileService', 'HttpService', controllerFunc]
		};

		return directive;

		function templateFunc(tElement, tAttrs) {
			if(tAttrs.isgallery) {
				return '/vendor/cms/app/directives/widgets/attachments-gallery.html';
			}else {
				return '/vendor/cms/app/directives/widgets/attachments.html';
			}
		}


		function controllerFunc($scope, Notify, FileUploader, FileService, HttpService) {

			// Init progress
			$scope.progress = 0;

			// Check if already uploaded files array defined
			if(!angular.isDefined($scope.files)) {
				$scope.files = [];
			}

			$scope.downloadFile = function(file) {
				window.location.href = '/files/download/' + file.token + '/';
			};

			// UPLOADER
			$scope.uploader = new FileUploader({
				url: '/api/common/files?type='+ $scope.fileType + ($scope.parentId ? '&parent_id=' + $scope.parentId : ''),
				headers : {'X-CSRF-TOKEN' : BACKEND_CFG.CSRF_TOKEN},
				autoUpload: true,
				removeAfterUpload: true,
				queueLimit: 10,
				filters: [{
					name: 'limit',
					fn: function() {
						//Check for limit files if defined
						if(!$scope.limit || _.filter($scope.files, function(x){ return !x._REMOVE }).length < $scope.limit) {
							return true;
						} else {
							Notify.alert('Max file size');
						}
					}
				}],
				onBeforeUploadItem: function() {
				},
				onProgressAll: function (progress) {
					$scope.progress = progress;
				},
				onCompleteItem : function (item, response, status, headers) {
					$scope.files.push(response);
				},
				onErrorItem: function (item, response) {
					Notify.alert(response.message ? response.message : 'Something went wrong. Please refresh page and try again.');
				},
				onCompleteAll: function() {
					$scope.progress = 0;
				}
			});

			// FILTERS
			if($scope.isgallery) {
				$scope.uploader.filters.push({
					name: 'imageFilter',
					fn: function(item /*{File|FileLikeObject}*/, options) {
						var is_image = item.type == 'image/jpeg' || item.type == 'image/jpg' || item.type == 'image/png';
						if(!is_image) {
							Notify.alert(Lang.get('general.uploader_error_extension'));
						}
						return is_image;
					}
				});
			}

			// Cancel upload
			$scope.cancelFile = function(file) {
				$scope.files.splice(_.indexOf($scope.files, file), 1);
			};

			// Remove file
			$scope.removeFile = function(index) {
				//Notify.confirm(function () {
					// Fore delete of file
					HttpService.delete('/api/common/files/forceDelete/' + $scope.files[index].token).success(function (resp) {
						delete $scope.files[index].token;
					}).error(function (error) {
						$scope.profileLoad = false;
					});

				//}, 'Confirm?');
			};

			$scope.openPhotoSwipe = function(currentIndex, attachments) {
				var pswpElement = document.querySelectorAll('.pswp')[0];
				console.log(attachments);
				_.map(attachments, function(x) {
					x.src = FileService.src(x.type, x.token, 'fullsize', 'jpg');
					x.w = 1200;
					x.h = 900;
					x.title = '';
				});

				var options = {
					index:  currentIndex, // start at first slide
					history: false
				};

				// Initializes and opens PhotoSwipe
				var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, attachments, options);
				gallery.init();
			};
		}
	}

	form.$inject = ['$filter', '$timeout'];
	function form($filter, $timeout) {

		var directive = {
			restrict: 'E',
			priority: -1,
			link: linkFunc
		};

		return directive;

		function linkFunc(scope, element) {

			scope.submitted = false;

			element[0].addEventListener('submit', function(event) {
				//if(scope.submitted) {
				//	//event.stopImmediatePropagation();
				//	event.preventDefault();
				//}
				//scope.submitted = true;
				// TODO 1
				var button = element.find('button[type=submit]');
				if(button) {
					element.find('button[type=submit]')[0].setAttribute('disabled', true);
					$timeout(function(){
						element.find('button[type=submit]')[0].removeAttribute('disabled');
					}, 1000)
				}
			});

			// Loader
			scope.$root.$on('form:submitted', function(event, res){
				// submit true
				scope.submitted = true;
			});

			scope.$root.$on('form:success', function(event, res){
				// submit false
				scope.submitted = false;
			});

			scope.form_error_title = false;

			scope.$root.$on('form:error', function(event, res){

				// submit false
				scope.submitted = false;

				//$(window).scrollTop(0);

				/**
				 * Set $validity false to form & all inputs
				 */
				if(scope.form) {

					cleanValidity(scope.form);

					setInputsInvalid(scope.form, res);
				}

				//element.find('.ng-invalid').first().focus();

				//scope.form_error_title = $filter('trans')('general.form_error_title');
				scope.form_error_title = 'There are some errors!';

				angular.element('.main-container').scrollTop(0);
			});

			/**
			 * Cleans form errors by setting validity of every input to true
			 * @param  {Object} form
			 */
			function cleanValidity(form) {
				if(angular.isUndefined(form)) {return;}

				angular.forEach(form, function(input) {
					if(!input) { return; } //
					angular.forEach(input.$error, function(invalid, errorKey) {
						if(invalid) {
							input.$setValidity(errorKey, true);
						}
						input.errors = null;
					});
				});
			}

			/**
			 * Set $validity false to form & all inputs
			 */
			function setInputsInvalid(form, res)
			{
				if(!form) return;

				// if not name="form", like name="form.settings"

				if(Object.keys(form).length === 1) {
					form = form[Object.keys(form)[0]];
				}

				if(!form || !form.$setValidity) return;

				form.$setValidity(false, false);

				angular.forEach(res, function(errors, inputName) {
					if(form[inputName]) {
						form[inputName].$setValidity(false,false);
						form[inputName].$setPristine();
						form[inputName].$setUntouched();
						form[inputName].errors = errors;
					}
				});
			}
		}
	}

	function customInput()
	{
		var directive = {
			restrict: 'E',
			templateUrl: templateFunc,
			scope: {
				model: '=',
				//ngModelOptions : '@',
				search: '=',
				disabled: '=',
				icondatepicker: '@',
				iconcurrency: '@',
				iconpercent: '@',
				iconcategory: '@',
				custominput: '@',
				change: '&?',
				label: '@',
				required: '=',
				name: '@',
				domain: '@',
				http: '@',
				maximum: '='
			},
			replace: true,
			transclude: true,
			priority: 10,
			compile: compileCustomElement,
			controller: ['$scope', _ErrorControllerFunc]
		};

		return directive;

		function templateFunc(tElement, tAttrs) {
			return '/vendor/cms/app/directives/form/input.html';
		}
	}

	function customTextarea()
	{

		var directive = {
			restrict: 'E',
			templateUrl: templateFunc,
			scope: {
				model: '=',
				disabled: '=',
				label: '@',
				required: '=',
				name: '@'
			},
			replace: true,
			transclude: true,
			priority: 10,
			compile: compileFunc,
			controller: ['$scope', _ErrorControllerFunc]
		};

		return directive;

		function templateFunc(tElement, tAttrs) {
			return '/vendor/cms/app/directives/form/textarea.html';
		}


		function compileFunc(tElement, tAttrs) {
			//if (angular.isDefined(tAttrs.ckeditor)) {
			//	tElement.find('textarea')[0].setAttribute('class', 'ckeditor' + (tAttrs.ckeditor ? ' editor-simple' : ''));
			//}

			return compileCustomElement(tElement, tAttrs);
		}
	}

	function customSelect()
	{
		var directive = {
			restrict: 'E',
			templateUrl: function(tElement, tAttrs) {
				if(tAttrs.asobject && tAttrs.asobject !== 'false') {
					return '/vendor/cms/app/directives/form/select-obj.html';
				} else {
					return '/vendor/cms/app/directives/form/select.html';
				}
			},
			scope: {
				model: '=',
				options: '=',
				label: '@',
				multiple: '@',
				required: '=',
				disabled: '=',
				asobject: '@',
				sortkey: '@',
				plain:'@',
				name: '@'
			},
			replace: true,
			transclude: true,
			priority: 10,
			controller: ['$scope', controllerFunc],
			compile: compileCustomElement
		};

		return directive;

		function controllerFunc($scope) {

			if(!$scope.asobject && $scope.asobject !== 'false') {
				var newOptions = [];

				for(var i in $scope.options) {
					newOptions.push({key: i.toString(), value: $scope.options[i]});
				}

				$scope.options = newOptions;
			}

			$scope.$watch('model', function(newValue, oldValue) {
				if(angular.isDefined($scope.multiple)) {

				} else {
					$scope.model = ($scope.model) ? $scope.model.toString() : '';
				}
			});

			$scope.$root.$on('form:error', function(event, res){
				$scope.errors = res[$scope.name];
			});
		}
	}

	function customCheckbox()
	{
		var directive = {
			restrict: 'E',
			templateUrl: '/vendor/cms/app/directives/form/checkbox.html',
			scope: {
				model: '=',
				label: '@',
				required: '@',
				name: '@',
				title: '@',
				tooltip: '@'
			},
			replace: true,
			transclude: false,
			priority: 10,
			controller: ['$scope', controllerFunc],
			compile: compileCustomElement
		};

		return directive;

		function controllerFunc($scope) {
			if(typeof $scope.model !== 'boolean') {
				$scope.model = !!parseInt($scope.model);
			}

			$scope.$root.$on('form:error', function(event, res){
				$scope.errors = res[$scope.name];
			});
		}
	}


	customCheckboxMultiple.$inject = ['array_util'];
	function customCheckboxMultiple(array_util)
	{
		var directive = {
			restrict: 'E',
			templateUrl: templateFunc,
			scope: {
				model: '=',
				options: '=',
				column: '@',
				size: '@',
				required: '@',
				disabled: '@',
				modeldisabled: '=',
				style: '@',
				name: '@'
			},
			replace: true,
			transclude: false,
			priority: 10,
			controller: ['$scope', 'array_util', controllerFunc],
			compile: compileCustomElement
		};

		return directive;

		function templateFunc(tElement, tAttrs) {
			if(tAttrs.column) {
				return '/vendor/cms/app/directives/form/column-checkbox-multiple.html';
			} else {
				return '/vendor/cms/app/directives/form/checkbox-multiple.html';
			}
		}

		function controllerFunc($scope, array_util) {
			//column-checkbox-multiple
			$scope.disabled = $scope.disabled ? parseInt($scope.disabled) : -1;

			var initValue = _.clone($scope.model);

			$scope.toggleSelection = function toggleSelection(key) {

				$scope.model = angular.isArray($scope.model) ? $scope.model : [];

				if(initValue && typeof initValue === 'string') {
					$scope.model.push(initValue);
				}

				var idx = $scope.model.indexOf(key);

				// is currently selected
				if (idx > -1) {
					$scope.model.splice(idx, 1);
				}

				// is newly selected
				else {
					$scope.model.push(key);
				}
			};

			$scope.$root.$on('form:error', function(event, res){
				$scope.errors = res[$scope.name];
			});
		}
	}

	function customRadio()
	{
		var directive = {
			restrict: 'E',
			templateUrl: templateFunc,
			scope: {
				model: '=',
				options: '=',
				label: '@',
				required: '@',
				disabled: '=',
				column: '@',
				name: '@',
				int: '@'
			},
			replace: true,
			transclude: true,
			priority: 10,
			controller: ['$scope', controllerFunc],
			compile: compileCustomElement
		};

		return directive;

		function templateFunc(tElement, tAttrs) {
			if(tAttrs.column) {
				return '/vendor/cms/app/directives/form/column-radio.html';
			} else {
				return '/vendor/cms/app/directives/form/radio.html';
			}
		}

		function controllerFunc($scope) {

			if(!_.isArray($scope.disabled)) {
				$scope.disabled = $scope.disabled ? parseInt($scope.disabled) : -1;
			}

			$scope._ = _;

			var newOptions = [];

			for(var i in $scope.options) {
				if(typeof $scope.options[i] == 'object') {
					newOptions = $scope.options;
					break;
				}

				if($scope.int == true) {
					newOptions.push({key: i, value: $scope.options[i]});
				} else {
					newOptions.push({key: i.toString(), value: $scope.options[i]});
				}

			}

			$scope.options = newOptions;

			$scope.$watch('model', function(v) {
				$scope.model = ($scope.model) ? $scope.model.toString() : '';
			});

			$scope.$root.$on('form:error', function(event, res){
				$scope.errors = res[$scope.name];
			});
		}
	}

	function compileCustomElement(tElement, tAttrs)
	{
		var input = tElement.find('textarea, input, select')[0];

		if(tAttrs.type !== undefined) {
			input.setAttribute('type', tAttrs.type);
			tElement[0].removeAttribute('type');
		}
		if(tAttrs.title !== undefined) {
			input.setAttribute('title', tAttrs.title);
			tElement[0].removeAttribute('title');
		}
		if(tAttrs.name !== undefined) {
			tElement[0].removeAttribute('name');
		}
		if(tAttrs.model !== undefined) {
			tElement[0].removeAttribute('model');
		}
		if(tAttrs.required !== undefined) {
			input.setAttribute('required', tAttrs.required);
			tElement[0].removeAttribute('required');
		}
		if(tAttrs.label !== undefined) {
			tElement[0].removeAttribute('label');
		}
		if(tAttrs.name !== undefined) {
			tElement[0].removeAttribute('name');
		}
		if(tAttrs.options !== undefined) {
			tElement[0].removeAttribute('options');
		}
		if(tAttrs.min !== undefined) {
			input.setAttribute('min', parseInt(tAttrs.min));
			input.setAttribute('step', '0.01');
			tElement[0].removeAttribute('min');
		}
		if(tAttrs.max !== undefined) {
			input.setAttribute('max', parseFloat(tAttrs.max));
			tElement[0].removeAttribute('max');
		}
		if(tAttrs.maximum !== undefined) {
			tElement[0].removeAttribute('maximum');
		}
		if(tAttrs.rows !== undefined) {
			input.setAttribute('rows', tAttrs.rows);
			tElement[0].removeAttribute('rows');
		}
		if(tAttrs.autofocus !== undefined) {
			input.setAttribute('autofocus', tAttrs.autofocus);
			tElement[0].removeAttribute('autofocus');
		}
		if(tAttrs.placeholder !== undefined) {
			input.setAttribute('placeholder', tAttrs.placeholder);
			tElement[0].removeAttribute('placeholder');
		}
		if(tAttrs.datepicker) {
			input.setAttribute('datepicker', tAttrs.datepicker);
			tElement[0].removeAttribute('datepicker');
		}
		if(tAttrs.timepicker) {
			input.setAttribute('timepicker', tAttrs.timepicker);
			tElement[0].removeAttribute('timepicker');
		}
		if(tAttrs.format) {
			input.setAttribute('format', tAttrs.format);
			tElement[0].removeAttribute('format');
		}
		if(tAttrs.checked) {
			input.setAttribute('checked', tAttrs.checked);
			tElement[0].removeAttribute('checked');
		}
		if(tAttrs.change) {
			input.setAttribute('ng-change', tAttrs.change);
			tElement[0].removeAttribute('change');
		}
	}

	_ErrorControllerFunc.$inject = ['$scope', '$element'];
	function _ErrorControllerFunc($scope, $element) {

		$scope.$root.$on('form:error', function(event, res){
			$scope.errors = res[$scope.name];
		});

		if($scope.change) {
			$scope.$watch('model', function(val, oldVal){

				var fn = $scope.$eval($scope.change);
				fn($scope, val, oldVal);
			});
		}

		if($scope.maximum){
			$scope.$watch('model', function(val){
				if(parseFloat(val) > parseFloat($scope.maximum)) {
					$scope.model = $scope.maximum;
				}
			});
		}
	}
})();
