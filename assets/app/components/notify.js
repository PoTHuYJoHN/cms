(function () {
	'use strict';

	angular
		.module('shared.notify', [
			'ui-notification' // https://github.com/alexcrack/angular-ui-notification
		])

		.config(function(NotificationProvider) {
			NotificationProvider.setOptions({
				delay: 3000,
				startTop: 20,
				startRight: 10,
				verticalSpacing: 20,
				horizontalSpacing: 20,
				positionX: 'left',
				positionY: 'bottom'
			});
		})

		.factory('Notify', ['ngDialog', 'Notification', function (ngDialog, Notification) {
			return {
				confirm: function (callback, title, template) {
					ngDialog.openConfirm({
						templateUrl: template ? template.toString() : '/vendor/cms/app/directives/main/confirm.html',
						showClose: false,
						data: {
							title: title
						},
						scope: true
					}).then(function (value) {
						callback(value);
					});
				},
				alert: function (message, title) {
					title = title ? title : 'Attention!';

					ngDialog.open({
						plain: true,
						template: '<div class="custom-alert u-textCenter"><i class="fa fa fa-warning" style="font-size: 30px; color: #da5050;"></i><h4>' + title + '</h4><p>' + message + '</p></div>'
					});
				},

				info: function (text) {
					Notification.info({message: text});
				},
				success: function (text, delay) {
					Notification.success({message: text, delay: delay || 3000});
				},
				warning: function (text) {
					Notification.warning({message: text});
				},
				error: function (text, delay) {
					Notification.error({message: text, delay: delay || 3000});
				},
				clear: function () {
					Notification.clearAll();
				}
			};
		}])

	;

})();
