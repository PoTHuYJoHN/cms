<?php

namespace Webkid\Cms\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkid\Cms\Controllers\ControllerTraits\ApiTrait;
use Webkid\Cms\Controllers\ControllerTraits\Paginatable;

abstract class ApiController extends BaseController
{
	use DispatchesJobs, ValidatesRequests, AuthorizesRequests, ApiTrait, Paginatable;
}
