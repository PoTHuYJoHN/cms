<?php

namespace Webkid\Cms\Controllers\ControllerTraits;


//use Webkid\Cms\Repositories\Exceptions\BaseExceptionAbstract;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiTrait
 *
 * @package App\Http\Controllers\ControllerTraits
 */
trait ApiTrait
{
	/**
	 * @var int
	 */
	protected $statusCode = Response::HTTP_OK;

	/**
	 * @return mixed
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * Builds a pagination array
	 *
	 * @param $data
	 * @return mixed
	 */
	public function buildPagination($data)
	{
		$pagination = is_array($data) ? $data : $data->toArray();
		unset($pagination['data']);

		return $pagination;
	}

	/**
	 * @param int $statusCode
	 * @return $this
	 */
	public function setStatusCode(int $statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * @param       $data
	 * @param array $headers
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function respond($data = [], array $headers = [])
	{
		return response()->json($data, $this->getStatusCode(), $headers);
	}

	/*
	|--------------------------------------------------------------------------
	| SUCCESS RESPONSES
	|--------------------------------------------------------------------------
	*/

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function respondWithCreated($data)
	{
		return $this->setStatusCode(Response::HTTP_CREATED)->respondWithSuccess($data);
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function respondWithSaved(array $data = [])
	{
		return $this->respondWithSuccess($data);
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function respondWithSuccess(array $data = [])
	{
		return $this->respond(array_merge(['status' => 'OK'], $data));
	}

	/*
	|--------------------------------------------------------------------------
	| ERROR RESPONSES
	|--------------------------------------------------------------------------
	*/

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function respondWithError(array $data = [])
	{
		return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
			->respond(array_merge(['status' => 'ERROR'], $data));
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function respondValidationErrors(array $data = [])
	{
		return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
			->respond($data);
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @return mixed
	 */
	public function respondUnprocessable(string $message = 'Request could not be processed', array $data = [])
	{
		$response = array_merge(['message' => $message], $data);

		return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
			->respond($response);
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @return mixed
	 */
	public function respondMethodNotAllowed(string $message = 'Method is not allowed', array $data = [])
	{
		$response = array_merge(['message' => $message], $data);

		return $this->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
			->respond($response);
	}

	/**
	 * @param HttpException $e
	 * @param int           $code
	 * @return mixed
	 */
	public function respondHttpException(HttpException $e, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
	{
		return $this->setStatusCode($code)
			->respond(['message' => $e->getMessage()]);
	}

//	/**
//	 * @param BaseExceptionAbstract $e
//	 * @param int                   $code
//	 * @return mixed
//	 */
//	public function respondWithException(BaseExceptionAbstract $e, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
//	{
//		return $this->setStatusCode($code)
//			->respond(['message' => $e->getMessage()]);
//	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondNotFound(string $message = 'Not Found')
	{
		return $this->setStatusCode(Response::HTTP_NOT_FOUND)
			->respond(['message' => $message]);
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondWithNotSaved(string $message = 'Data Not Saved')
	{
		return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
			->respond(['message' => $message]);
	}

	/**
	 * @param string $message
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function respondForbidden(string $message = 'Forbidden')
	{
		return $this->setStatusCode(Response::HTTP_FORBIDDEN)
			->respond(['message' => $message]);
	}

	/*
	|--------------------------------------------------------------------------
	| OTHER RESPONSES
	|--------------------------------------------------------------------------
	*/

	/**
	 * @param string $pathToFile
	 * @param null   $name
	 * @param array  $headers
	 * @param string $disposition
	 * @return BinaryFileResponse
	 */
	public function respondDownload(string $pathToFile, $name = null, array $headers = [], $disposition = 'attachment')
	{
		return response()->download($pathToFile, $name, $headers, $disposition);
	}
}
