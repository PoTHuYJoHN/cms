<?php

namespace Webkid\Cms\Services;

/**
 * Class Notification
 *
 * @package App\Services
 */
class Notification {

	/**
	 * @param        $view
	 * @param string $subj
	 * @param array  $values
	 * @param bool   $deferred
	 */
//	public function sendToSupport($view, $subj = '', $values = [], $deferred = false)
//	{
//		$method = $deferred ? 'queue' : 'send' ;
//
//		\Mail::$method($view, $values, function($message) use ($subj)
//		{
//			$supportMail = config('general.emailSupport');
//			$message->to($supportMail)->subject($subj);
//		});
//	}

	/**
	 * @param        $view
	 * @param        $email
	 * @param string $subj
	 * @param array  $values
	 * @param bool   $deferred
	 */
	public function send($view, $email, $subj = '', $values = [], $deferred = false)
	{
		$method = $deferred ? 'queue' : 'send' ;
		\Mail::$method($view, $values, function($message) use ($subj, $email)
		{
			$message->to($email)->subject($subj);
		});
	}
}
