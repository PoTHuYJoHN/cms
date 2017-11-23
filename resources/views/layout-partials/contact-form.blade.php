<form method="post" name="form.contact" ng-submit="contactFormPersist()">

	<fieldset>
		<div class="row">
			<div class="col-xs-24 col-sm-12">
				<custom-input
					model="contact.name"
					name="name"
					required="true"
					maxLength="32"
					title="Name"
					placeholder="Name"></custom-input>
			</div>
			<div class="col-xs-24 col-sm-12">
				<custom-input
					model="contact.organization"
					name="organization"
					required="true"
					maxLength="32"
					title="Organization"
					placeholder="Organization"></custom-input>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-24 col-sm-12">
				<custom-input
					model="contact.email"
					name="email"
					required="true"
					maxLength="32"
					title="Email"
					placeholder="Email"></custom-input>
			</div>
			<div class="col-xs-24 col-sm-12">
				<custom-input
					model="contact.phone"
					name="phone"
					required="true"
					maxLength="32"
					title="Phone"
					placeholder="Phone"></custom-input>
			</div>
		</div>

		<custom-textarea
			model="contact.message"
			title="How may we help you?"
			placeholder="How may we help you?"></custom-textarea>

		<div class="display-flexbox middle-xs between-xs typography-li">
			<ul>
				<li>All fields are required</li>
			</ul>

			{{--<div ng-if="successMessage" class="u-textLeft">--}}
				{{--@{{ successMessage }}--}}
			{{--</div>--}}

			<button class="m-btn-primary lg-primary" type="submit">
				<i class="fa fa-envelope m-r-5"></i>
				Send message
			</button>
		</div>

	</fieldset>
</form>
