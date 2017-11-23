<?php

namespace Webkid\Cms\Http\Controllers;

use Webkid\Cms\Events\NewMessageFromContactPage;
use Webkid\Cms\Http\Requests\ContactFormRequest;
use Webkid\Cms\Http\Requests\SubscriberFormRequest;
use Webkid\Cms\Http\Requests;
use Webkid\Cms\Subscriber;

class ServicesController extends Controller
{
	/**
	 * Send contact message
	 *
	 * @param ContactFormRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendContact(ContactFormRequest $request)
	{
		\Event::fire(new NewMessageFromContactPage($request->all()));

		return response()->json(['message' => 'Message was sent']);
	}

	/**
	 * Add subscribe email
	 *
	 * @param SubscriberFormRequest $request
	 */
	public function storeSubscription(SubscriberFormRequest $request)
	{
		$email = $request->get('email');

		if(! Subscriber::where('email', $email)->exists()) {
			Subscriber::create([
				'email' => $email
			]);
		}
	}

}
