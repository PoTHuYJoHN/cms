angular
	.module('utils', [])

	.factory('array_util', function() {
		var util_array = {
			partition: partition,
			uniqueItems: uniqueItems,
			chunk: chunk,
			split: split,
			imgload: imgload
		};

		return util_array;


		/**
		 * Divide into parts
		 * @param items
		 * @param size
		 * @returns {Array}
		 */
		function partition(items, size) {
			var result = _.groupBy(items, function(item, i) {
				return Math.floor(i/size);
			});
			return _.values(result);
		}

		/**
		 * Load image
		 * @param src
		 * @param callback
		 */
		function imgload(src, callback) {
			var image = new Image();
			image.src = src;
			$(image).load(function() {
				callback();
			});
		}

		/**
		 * Return array of unique values from collections
		 * @param data
		 * @param key
		 * @returns {Array}
		 */
		function uniqueItems(data, key, sort) {
			var result = [];
			for (var i = 0; i < data.length; i++) {
				var value = data[i][key];
				if (result.indexOf(value) === -1) {
					result.push(value);
				}
			}

			if(sort) {
				result = _.sortBy(result, function(val) {
					return val;
				});
			}
			return result;
		}

		/**
		 * Get {start} nested arrays each containing maximum of {amount} items
		 * @param arr
		 * @param start
		 * @param amount
		 * @returns {Array}
		 */
		function chunk(arr, start, amount){
			var result = [],
				start = start || 0,
				amount = amount || 500,
				len = arr.length;

			do {
				//console.log('appending ', start, '-', start + amount, 'of ', len, '.');
				result.push(arr.slice(start, start+amount));
				start += amount;

			} while (start< len);

			return result;
		}

		function split(a, n) {
			var len = a.length,out = [], i = 0;
			while (i < len) {
				var size = Math.ceil((len - i) / n--);
				out.push(a.slice(i, i += size));
			}
			return out;
		}
	})

	.factory('string_util', function(){

		var util_array = {
			similar_text: similar_text
		};

		return util_array;

		function similar_text(first, second, percent) {
			//  discuss at: http://phpjs.org/functions/similar_text/
			// original by: Rafał Kukawski (http://blog.kukawski.pl)
			// bugfixed by: Chris McMacken
			// bugfixed by: Jarkko Rantavuori original by findings in stackoverflow (http://stackoverflow.com/questions/14136349/how-does-similar-text-work)
			// improved by: Markus Padourek (taken from http://www.kevinhq.com/2012/06/php-similartext-function-in-javascript_16.html)
			//   example 1: similar_text('Hello World!', 'Hello phpjs!');
			//   returns 1: 7
			//   example 2: similar_text('Hello World!', null);
			//   returns 2: 0

			if (first === null || second === null || typeof first === 'undefined' || typeof second === 'undefined') {
				return 0;
			}

			first += '';
			second += '';

			var pos1 = 0,
				pos2 = 0,
				max = 0,
				firstLength = first.length,
				secondLength = second.length,
				p, q, l, sum;

			max = 0;

			for (p = 0; p < firstLength; p++) {
				for (q = 0; q < secondLength; q++) {
					for (l = 0;
					     (p + l < firstLength) && (q + l < secondLength) && (first.charAt(p + l) === second.charAt(q + l)); l++)
						;
					if (l > max) {
						max = l;
						pos1 = p;
						pos2 = q;
					}
				}
			}

			sum = max;

			if (sum) {
				if (pos1 && pos2) {
					sum += this.similar_text(first.substr(0, pos1), second.substr(0, pos2));
				}

				if ((pos1 + max < firstLength) && (pos2 + max < secondLength)) {
					sum += this.similar_text(first.substr(pos1 + max, firstLength - pos1 - max), second.substr(pos2 + max,
						secondLength - pos2 - max));
				}
			}

			if (!percent) {
				return sum;
			} else {
				return (sum * 200) / (firstLength + secondLength);
			}
		}
	})
;
