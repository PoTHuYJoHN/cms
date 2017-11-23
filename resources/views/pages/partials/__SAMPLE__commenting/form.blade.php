<form name="form.comment" ng-controller="commentFormController" ng-submit="commentFormPersist()" ng-init="comment.item_id = '<?= $item_id ?>';comment.item_type = '<?= $item_type ?>'">

	<div class="row">

		<div class="col-xs-24 col-md-18">
			<div class="text-center">
				<div class="col-xs-24 col-md-24">
					<div class="row">
						<input ng-model="comment.name" class="name" type="text" name="name"
							 required tabindex="1" maxlength="128"
							 placeholder="{{ trans('comments.form.comment.placeholder') }}">
						<ul class="form-errors" ng-class="{'show' : errors.name}"><li ng-bind="errors.name[0]"></li></ul>
						<input ng-model="comment.email" class="email" type="text" name="email"
							 required tabindex="2" maxlength="128"
							 placeholder="{{ trans('comments.form.email.placeholder') }}">
						<ul class="form-errors" ng-class="{'show' : errors.email}"><li ng-bind="errors.email[0]"></li></ul>
					</div>

				</div>
				<div class="col-xs-24 col-md-24">
					<div class="row">
						<textarea ng-model="comment.comment" name="comment"
							    placeholder="{{ trans('comments.form.comment.placeholder') }}"
							    required
							    tabindex="3"
							    maxlength="500"
							    rows="3"></textarea>
						<ul class="form-errors" ng-class="{'show' : errors.comment}"><li ng-bind="errors.comment[0]"></li></ul>
					</div>

				</div>
			</div>
		</div>

		<div class="col-md-6 col-sm-24 col-xs-24 right-captcha">
			<div class="captcha-number">
				<div class="col-md-24 col-sm-4 col-xs-6 col-xs-50">
					<img id="captchaImg" src="/captcha/">
				</div>
				<div class="col-md-24 col-sm-12 col-xs-8 col-xs-50">
					<div class="input-row">
						<input ng-model="comment.captcha" class="captcha" type="text" name="captcha"
							 required tabindex="4" maxlength="4">

						<a href="/captcha?time={{ time() }}" onclick="$('#captchaImg').attr('src', $('#captchaImg').attr('src') + '?time=' + Date.now()); return false;" class="update"><i></i></a>
						<ul class="form-errors" ng-class="{'show' : errors.captcha}"><li ng-bind="errors.captcha[0]"></li></ul>
					</div>
				</div>
				<div class="col-md-24 col-sm-8 col-xs-12 col-xs-100">
					<div class="btn-row">
						<button type="submit" class="btn btn-green-border btn-size-medium" tabindex="5">{{ trans('comments.form.submit.label') }}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
