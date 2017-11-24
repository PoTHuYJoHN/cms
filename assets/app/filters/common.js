(function() {
	'use strict';

	angular.module("common.filters", [])

		.filter('languageFullByShort', languageFullByShort)


		.filter('capitalize', capitalize)
		.filter('slugify', slugify)
		.filter('getCover', getCover) // get file url by token and type
		.filter('creditcard', creditCard)
		.filter('dateToISO', dateToISO)
		.filter('cut', cut)
		.filter('mobileImage', mobileImage)
		.filter('unsafe', unsafe)
		.filter('decode', decode)
		.filter('toBoolean', toBoolean)

		.filter('getAvaUrl', getAvaUrl)
		.filter('trans', trans)
		.filter('trans_plural', trans_plural)
		.filter('trans_as_array', trans_as_array)

		.filter('momentDate', momentDate)
	;


	getAvaUrl.$inject = ['FileService'];
	function getAvaUrl(FileService) {
		return function(token, type, size, ext) {
			size = size || 'preview';
			ext = ext || false;

			return FileService.src(type, token, size, ext);
		};
	}

	/**
	 * Get translation from resources.
	 * Example:
	 * 'pagination.next' | trans => 'Next'
	 */
	function trans() {
		return function (input, replaces) {
			return Lang.get(input, replaces);
		};
	}

	/**
	 * Get translation from resources.
	 * Example:
	 * 'subscription.month' | trans_plural:1 => 'month'
	 * 'subscription.month' | trans_plural:2 => 'months'
	 */
	function trans_plural() {
		return function (input, number) {
			return Lang.choice(input, number);
		};
	}

	/**
	 * Convert translation object to array from resources.
	 * Example:
	 * 'property.property_types' | trans_as_array
	 */
	function trans_as_array() {
		return function (input) {
			if(typeof Lang.get(input) !== 'object') {
				console.error('Error, ' + input + 'not a object.');
				return [];
			}
			var newOptions = [];
			angular.forEach(Lang.get(input), function(val, key){
				newOptions.push({key: key, value: val});
			});

			return newOptions;
		};
	}

	function languageFullByShort() {
		return function(short) {
			switch(short) {
				case 'en' :
					return 'english';break;
				case 'es' :
					return 'spanish';break;
				case 'fr' :
					return 'french';break;
				case 'ua' :
					return 'ukrainian';break;
				case 'ru' :
					return 'russian';break;
				default :
					return 'english';
			}
		}
	}

	/**
	 * Predefined filters
	 *
	 **/
	//Capitalize first letter in first word or all words
	function capitalize() {
		return function(input, all) {
			var pattern = all
				? /([^\W_]+[^\s-]*) */g
				: /([^\W_]+[^\s-]*) */;
			return (!!input)
				? input.replace(pattern ,
				function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})
				: '';
		}
	}

	// mobile image
	mobileImage.$inject = ['$rootScope'];
	function mobileImage($rootScope) {
		return function(image) {
			var name = image;
			if(!$rootScope.isDesktop){
				return 'mobile_' + name;
			} else {
				return name;
			}
		}
	}

	//{{some_text | cut:true:100:' ...'}}
	function cut() {
		return function (value, wordwise, max, tail) {
			if (!value) return '';

			max = parseInt(max, 10);
			if (!max) return value;
			if (value.length <= max) return value;

			value = value.substr(0, max);
			if (wordwise) {
				var lastspace = value.lastIndexOf(' ');
				if (lastspace != -1) {
					value = value.substr(0, lastspace);
				}
			}

			return value + (tail || ' …');
		};
	}

	// Slugify words
	function slugify() {
		return function(input) {
			return input.toString().toLowerCase()
				.replace(/\s+/g, '-')           // Replace spaces with -
				.replace(/[^\w\-]+/g, '')       // Remove all non-word chars
				.replace(/\-\-+/g, '-')         // Replace multiple - with single -
				.replace(/^-+/, '')             // Trim - from start of text
				.replace(/-+$/, '');            // Trim - from end of text
		}
	}

	/**
	 * Get src for image
	 * @type {string[]}
	 */
	getCover.$inject = ['FilesService'];
	function getCover(FilesService) {
		return function(token, type, size, ext) {
			size = size || 'preview';
			ext = ext || false;

			return FilesService.src(type, token, size, ext);
		};
	}

	/**
	 * Show credit card last four numbers
	 * @returns {Function}
	 */
	function creditCard() {
		return function(lastFour) {
			return '**** **** **** ' + lastFour;
		}
	}
	//todo comment
	function dateToISO() {
		return function(input) {
			if(input && input !== '0000-00-00 00:00:00') {
				input = input.toString().split('-').join('/');
				return new Date(input).toISOString();
			} else {
				return null;
			}
		};
	}

	//todo comment
	unsafe.$inject = ['$sce'];
	function unsafe($sce) {
		return function(val) {
			return $sce.trustAsHtml(val);
		};
	}

	//todo comment
	function decode(){
		return function(str){
			var el = document.createElement("div");
			el.innerHTML = str;
			str =   el.textContent || el.innerText;
			return str;
		}
	}

	/**
	 * Simplify boolean
	 * @returns {Function}
	 */
	function toBoolean() {
		return function(val){
			if(val === 'false' || val === '0' || val === 0 || val === false) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * Format date with moment.js
	 */
	function momentDate()
	{
		return function(val, format)
		{
			return moment(val).format(format || 'MMMM Do YYYY')
		}
	}
})();
