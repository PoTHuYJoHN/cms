<form class="m-forms" method="post" name="form.contact" ng-submit="contactFormPersist()">

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
					model="contact.phone"
					name="phone"
					maxLength="32"
					title="Phone"
					placeholder="Phone"></custom-input>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-24 col-sm-24">
				<custom-input
					model="contact.email"
					name="email"
					required="true"
					maxLength=128
					title="Email"
					placeholder="Email"></custom-input>
			</div>

		</div>

		<custom-textarea
			model="contact.message"
			title="Message text"
			placeholder="Message text..."></custom-textarea>

		<div class="display-flexbox middle-xs between-xs typography-li">

			<button class="m-button m-button-rose" type="submit">
				<span>Send message </span>
				<i class="icon-fontello-mail"></i>
			</button>
		</div>

	</fieldset>
</form>
