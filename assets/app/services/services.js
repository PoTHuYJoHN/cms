(function() {
	'use strict';

	angular
		.module('services', [])

		.service('FilesService', FilesService)
		.service('FileService', FileService)
		.factory('postInterceptor', postInterceptor)
		.factory('authInterceptor', authInterceptor)
		.factory('Session', Session)
		.factory('AuthService', AuthService)
		.service('ProfileService', ProfileService)
		.service('HttpService', HttpService)
		.service('SeoService', SeoService)
		.service('LandingPageService', LandingPageService)
		.factory('MultiLangHelper', MultiLangHelper)
		.service('BreadCrumbsService', BreadCrumbsService)
		.service('PaginationService', PaginationService)
	;

	/* Fn
	 ============================================================================================================= */

	postInterceptor.$inject = ['$injector', '$q', '$rootScope'];
	function postInterceptor($injector, $q, $rootScope) {
		return {
			request : function(config) {
				if(config.method === 'POST') {
//					Loader.start();
				}
				return config;
			},
			response : function(response) {
				if(response.config.method == 'POST') {
//					Loader.stop();
				}
				return response;
			},
			responseError: function(response) {
//				Loader.stop();
				return $q.reject(response);
			}
		};
	}

	/* Fn
	 ============================================================================================================= */
	authInterceptor.$inject = ['$injector', '$q'];
	function authInterceptor($injector, $q) {
		return {
			response : function(response) {
				// If success $http Output debug, hide loader, etc
				// ...
				return response;
			},
			responseError: function(response) {
				//todo fix logout
				// Intercept 401s & 403s
				// (typeof response.data !== string) - check if template
				//if(typeof response.data !== string && response.status === 401 || response.status === 403) {
				//	$injector.get('$state').transitionTo('auth_login');
				//	return $q.reject(response);
				//}
				//else {
				return $q.reject(response);
				//}
			}
		};
	}

	FileService.$inject = ['BACKEND_CFG'];
	function FileService(BACKEND_CFG) {
		var cfg = BACKEND_CFG.files;
		this.src = function (type, token, size, ext) {
			ext = ext || false;

			if(typeof token !== 'undefined' && token) {
				return ['', cfg.assets_dir, cfg.dir[type], token[0], token[1], token[2], token, size + '.' + (ext ? ext : (cfg.sizes[type][size].format || 'jpg') )].join('/');
			} else {
				if(cfg.rules[type].type == '1') {
					return '/images/stubs/'+ cfg.dir[type] +'/' + size + '.jpg';
				}
				else {
					return '/images/stubs/'+ cfg.dir[type] +'/' + size + '.jpg';
				}
			}
		}
	}

	FilesService.$inject = ['$http'];
	function FilesService($http) {
		return {
			config:
				BACKEND_CFG.files
			,
			get: function(id) {
				if(id) {
					return $http.get('/api/files/'+id+'/');
				} else {
					return $http.get('/api/files/');
				}
			},
			post: function(model) {
				return $http.post('/api/files/', model);
			},
			put: function(model) {
				return $http.put('/api/files/'+model.id+'/', model);
			},
			deleteAll: function(data) {
				return $http.delete('/api/files/', {data: data});
			},
			src: function(type, token, size, ext) {
				var ext = ext || false;

				if(typeof token !== 'undefined' && token) {
					return '/' + this.config.assets_dir + '/'
						+ this.config.dir[type] + '/' + 'images' + '/'
						+ token[0] + '/' + token[1] + '/' + token[2] + '/' + token + '/'
						+ size + '.' + (ext ? ext : this.config.sizes[type][size].format);
				} else {
					if(this.config.rules[type].type == '1') {
						//log(this.config.rules[type]);
						return '/images/stubs/'+ this.config.dir[type] +'/' + size + '.png';
					}
					else {
						//log(this.config);
						return '/images/stubs/'+ this.config.dir[type] +'/' + size + '.png';
					}
				}
			},
			decache : function() {

			}
		};
	}

	function Session() {
		this.create = function (user) {
			this.isAdmin = user.role_id == 2 || false;
			this.user = user;
		};
		this.destroy = function (scope) {
			this.isAdmin = null;
			this.user = null;
			scope = null;
		};
		return this;
	}

	AuthService.$inject = ['Session', '$http', 'ProfileService'];
	function AuthService(Session, $http, ProfileService) {
		this.login = function(params, form, formErrorCb, errorCb) {
			return $http.post('/auth/login', params)
				.success(function(data){

					if(data.success === true) {
						Session.create(data.item);

						if(typeof data.redirect !== 'undefined') {
							window.location.href = data.redirect;
						}
					} else {
						return errorCb(data.message);
					}



					return data.item;
				})
				.error(function(err) {
					return formErrorCb(err);
				});
		};
		this.create = function(callback) {
			ProfileService.get(cb);

			function cb(res){
				if(res.item === false) {
					return callback(null);
				}

				Session.create(res.item);

				var user = {
					email: res.item.email,
					fullname: res.item.name,
					isAdmin: res.item.role_id == 2 || false
				};

				return callback(user);
			}
		};
		this.getAuth = function(callback) {
			return ProfileService.get(callback);
		};
		this.register = function(user, errorCallback) {

			return $http.post('/auth/register', user)
				.success(function(resp) {
					window.location.href = resp.redirect;
				})
				.error(function(err) {
					return errorCallback(err);
				});
		};
		this.resetPassEmail = function(item, successCb, errorCb) {
			return $http.post('/password/email', item)
				.success(function(resp) {
					return successCb(resp);
				})
				.error(function(err) {
					return errorCb(err);
				});
		};
		this.resetPass = function(item, successCb, errorCb) {
			return $http.post('/password/reset', item)
				.success(function(resp) {
					return successCb(resp);
				})
				.error(function(err) {
					return errorCb(err);
				});
		},
//		this.isAuth = function () {
//			return !!Session.user;
//		};
			this.isAdmin = function(){
				return !!Session.isAdmin;
			};

		return this;
	}

	ProfileService.$inject = ['$http'];
	function ProfileService($http) {
		var url = '/api/auth/profile';

		return {
			get: function(callback) {


				return $http.get(url, false)
					.success(callback);
			}
			//update: function (data, callback) {
			//	return $http.put(url, {user: data}, callback);
			//},
			//updatePassword: function (data, callback) {
			//	return $http.put(url+'password/', {user: data}, callback);
			//}
		};
	}

	HttpService.$inject = ['$http', '$rootScope'];
	function HttpService($http, $rootScope) {
		return {
			get: getFn,
			getWParams: getFnWParams,
			post: postFn,
			put: putFn,
			delete: deleteFn
		};

		function successCallback(resp, callback) {
			if(callback) {
				return callback(resp);
			} else {
				return resp;
			}
		}

		function errorCallback(resp, callback) {
			if(callback) {
				return callback(resp);
			} else {
				return resp;
			}
		}

		function getFn(url, callback, errorCb) {
			return $http.get(url)
				.success(function (resp) {
					if(callback) callback(resp);
				})
				.error(function(resp){
					if(errorCb) errorCallback(resp, errorCb)
				});
		}

		function getFnWParams(url, params, callback, errorCb) {
			params = params || {};

			return $http.get(url, params)
				.success(function (resp) {
					successCallback(resp, callback);
				})
				.error(function(resp) {
					errorCallback(resp, errorCb);
				});
		}

		function postFn(url, data, callback, errorCb) {
			$rootScope.$emit('form:submitted');

			return $http.post(url, data)
				.success(function (resp) {
					$rootScope.$emit('form:success');
					if(callback) successCallback(resp, callback);
				})
				.error(function(resp) {
					$rootScope.$emit('form:error', resp.errors);
					if(errorCb) errorCallback(resp, errorCb);
				});
		}

		function putFn(url, data, callback, errorCb) {
			$rootScope.$emit('form:submitted');

			return $http.put(url, data)
				.success(function (resp) {
					$rootScope.$emit('form:success');
					successCallback(resp, callback);
				})
				.error(function(resp) {
					$rootScope.$emit('form:error', resp.errors);
					if(errorCb) errorCallback(resp, errorCb);
				});
		}

		function deleteFn(url, callback, errorCb) {
			$rootScope.$emit('form:submitted');

			return $http.delete(url)
				.success(function (resp) {
					$rootScope.$emit('form:success');
					successCallback(resp, callback);
				})
				.error(function(resp) {
					$rootScope.$emit('form:error', resp);
					if(errorCb) errorCallback(resp, errorCb);
				});
		}
	}

	/**
	 * Service for meta title and meta description etc.
	 * @type {string[]}
	 */
	SeoService.$inject = ['$rootScope'];
	function SeoService($rootScope) {
		var title = 'Dashboard',
			description = 'Dashboard';

		return {
			getTitle : getTitleFn,
			setTitle : setTitleFn,
			setTitleFromState : setTitleFromStateFn,
			getDescription : getDescriptionFn,
			setDescription : setDescriptionFn,
			setDescriptionFromState : setDescriptionFromStateFn
		};

		/**
		 * Set meta <title>
		 * @param newTitle
		 */
		function setTitleFn(newTitle) {
			title = newTitle ? newTitle + getTitleSeparator() : newTitle;
		}

		/**
		 * set title from ui state
		 * @param state
		 */
		function setTitleFromStateFn(state) {
			setTitleFn(state.seo ? state.seo.title : null);
		}

		/**
		 * set <meta name="description">
		 * @param descr
		 */
		function setDescriptionFn(descr) {
			description = descr;
		}

		/**
		 * set meta description from state
		 * @param state
		 */
		function setDescriptionFromStateFn(state) {
			setDescriptionFn(state.seo ? state.seo.description : null);
		}


		/**
		 * Get title for html
		 * @returns {string}
		 */
		function getTitleFn() {
			return title;
		}

		/**
		 * Get description for html
		 * @returns {string}
		 */
		function getDescriptionFn() {
			return description;
		}

		function getTitleSeparator() {
			return ' - Dashboard';
		}
	}

	/**
	 * Service to work with landing pages with multilanguage support
	 * @type {string[]}
	 */
	LandingPageService.$inject = ['HttpService', 'MultiLangHelper', '$state', 'BACKEND_CFG'];
	function LandingPageService(HttpService, MultiLangHelper, $state, BACKEND_CFG) {
		return {
			updatePage: updatePage,
			createPage: createPage
		};

		function updatePage(pageId, fields, item, cb) {
			var data = prepareDataItems(fields, true);
			data = prepareOtherOptions(data, item);
			cb = cb || false;

			HttpService.put('/api/landing/updateAllLangs/' + pageId, data)
				.success(function(resp) {
					if(cb) cb();
				})
				.error(function(err){
					//console.log(err);
					//formErrors.handle($scope.forms.formAdd, [err.data]);
				});
		}

		function createPage(fields, item, parentId, token, section, cb) {
			var data = prepareDataItems(fields, false);
			data = prepareOtherOptions(data, item);
			cb = cb || false;


			if(section) { //multiple item
				data['parent_id'] = parentId;
				data['section'] = section;
				data['token'] = token;
				//data['section'] = token;
			} else { //master page
				data['token'] = token;
			}


			//todo change url
			HttpService.post('/api/landing', data)
				.success(function(resp) {
					if(cb) cb();
					//$state.go('slider.list', {'parentId' : parentId});
				})
				.error(function(err){
					//console.log(err);
					//formErrors.handle($scope.forms.formAdd, [err.data]);
				});
		}

		//HELPER FUCNTIONS
		function prepareDataItems(fields, edit) {
			var data = {};
			edit = edit || false;
			data.items = [];

			angular.forEach(BACKEND_CFG.langs, function(value, key) {

				var obj = edit
					? MultiLangHelper.prepareFieldsForPut(fields[value])
					: MultiLangHelper.prepareFieldsForCreate(fields[value]);
				obj.lang = value;

				data.items.push(obj);
			});

			return data;
		}

		/**
		 * Add other optinos to data object.
		 * coverToken etc.
		 * @param data
		 * @param item
		 * @returns {*}
		 */
		function prepareOtherOptions(data, item) {
			//console.log(item);
			if(typeof item.coverToken !== 'undefined') {
				data['coverToken'] = item.coverToken;
			}
			if(typeof item.files !== 'undefined') {
				data['files'] = item.files;
			}
			return data;
		}
	}

	/**
	 * Helper for landing pages persistent
	 * @returns {{prepareFieldsForLang: prepareFieldsForLang, prepareFieldsForPut: prepareFieldsForPut}}
	 * @constructor
	 */
	MultiLangHelper.$inject = ['BACKEND_CFG'];
	function MultiLangHelper(BACKEND_CFG) {
		return {
			prepareFieldsForLang: prepareFieldsForLang,
			prepareFieldsForPut: prepareFieldsForPut,
			prepareFieldsForCreate : prepareFieldsForCreate
		};

		/**
		 * Prepare object for forms with language
		 * @param fields
		 * @param edit
		 * @param values
		 */
		function prepareFieldsForLang(fields, edit, values) {
			var data = {};

			angular.forEach(BACKEND_CFG.langs, function(langVal) {
				data[langVal] = {};

				angular.forEach(fields, function(value, key) {
					data[langVal][key] = {};

					var fieldVal = '';
					if(edit) {
						//first find record with our foreach key;
						var fieldRecord =  _.findWhere(values, {'key' : key});
						//then find appropriate lang value
						if(fieldRecord) {
							fieldVal = _.findWhere(fieldRecord.texts, {'lang' : langVal}).value;
						}
					}

					data[langVal][key]['value'] = fieldVal;
					data[langVal][key]['label'] = value.label;
					data[langVal][key]['type'] = value.type;
					data[langVal][key]['editor'] = value.editor;
					data[langVal][key]['datetime'] = value.datetime;
					data[langVal][key]['date'] = value.date;
					data[langVal][key]['time'] = value.time;
				});
			});

			return data;
		}

		/**
		 * Prepare fields for saving
		 * @param fields
		 * @returns {{}}
		 */
		function prepareFieldsForPut(fields) {
			var data = {};
			data.fields = {};

			angular.forEach(fields, function(value, key) {
				data.fields[key] = value.value;
			});
			return data;
		}

		function prepareFieldsForCreate(fields) {
			var data = {};
			data.fields = {};

			angular.forEach(fields, function(value, key) {
				data.fields[key] = {
					value : value.value,
					editor : value.editor,
					label : value.label,
					type : value.type
				};
			});
			return data;
		}
	}

	function BreadCrumbsService($state) {

		this.addCrumb = function (label, state, params) {
			state = state || false;
			params = params || false;

			crumbs.push({
				label: label,
				state: state,
				params: params,
				crumb_href: params ? this.crumb_href(state, params) : ''
			});
		};
		//use this func for state with params
		this.crumb_href = function (state, params) {
			return $state.href(state, params, {absolute: true});
		};

		this.getCrumbs = function () {
			return crumbs;
		};

		this.clear = function () {
			crumbs = this.init();
		};

		this.init = function () {
			return [{
				state: 'dashboard.main',
				label: 'dashboard'
			}];
		};

		var crumbs = this.init();
	}

	PaginationService.$inject = ['$location', '$rootScope', 'HttpService'];
	function PaginationService($location, $rootScope, HttpService) {

		var GET_url = null;

		return {
			init: init,
			list: []
		};

		function init(pagination, url) {

			GET_url = url;

			// load page
			pagination.loadPage = function(page){
				_.extend($location.search(), {page: page ? page : $location.search().page});

				$rootScope.$emit('filter:change', $location.search(), {}, url, function(res){
					angular.element('#scopePage').scope().pagination.current_page = res.pagination.current_page;
					angular.element('#scopePage').scope().pagination.per_page = res.pagination.per_page;
					angular.element('#scopePage').scope().pagination.total = res.pagination.total;
					angular.element('#scopePage').scope().pagination.list = res.list;
				});

			};

			return pagination;
		}
	}

})();
