<div ng-controller="subscribeFormController">
	<form ng-if="subscriptionSuccess === false" name="form.subscribe" class="m-search-subscribe"  ng-submit="subscribeFormPersist()">
		<div class="input">
			<div class="input-block">
				<input type="email" name="email" required maxlength="64" ng-model="subscribe.email" placeholder="Enter your email ">
				<ul class="form-errors" ng-class="{'show' : errors.email}"><li ng-bind="errors.email[0]"></li></ul>
			</div>
			<div class="button-block">
				<button type="submit" class="m-button m-button-dark"><i class="icon-fontello-mail"></i> <span class="">Subscribe me</span></button>
			</div>
		</div>
	</form>

	<div ng-if="subscriptionSuccess === true" style="padding-bottom: 10px;padding-top: 10px;">
		<h2 class="thank-h2">{{ trans('promo.thank_you') }}</h2>
	</div>
</div>
