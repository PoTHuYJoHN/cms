<?php

namespace Webkid\Cms\Http\Controllers\Api\Dashboard;

use Webkid\Cms\Http\Controllers\ApiController;
use Webkid\Cms\Subscriber;
use Webkid\Cms\Http\Requests;
use Webkid\Cms\Transformers\SubscriberTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;

class SubscribersController extends ApiController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$subscribers = Subscriber::orderBy('created_at', 'desc')->paginate(2);
		if ($request->search) {

			$search = '%' . $request->search . '%';

			$subscribers = Subscriber::where('email', 'like', $search)->paginate(2);
		}

		return $this->respond(Fractal::collection($subscribers, new SubscriberTransformer())->getArray());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Subscriber::destroy($id);
	}
}
