<?php


namespace Webkid\Cms\Services;


use Webkid\Cms\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;

/**
 * Class AuthenticateUser
 *
 * @package App\Services
 */
class AuthenticateUser
{
	CONST LOGIN_TYPE_FB = 'facebook';
	CONST LOGIN_TYPE_GOOGLE = 'google';

	/**
	 * @var UserRepository
	 */
	private $userRepo;
	/**
	 * @var Socialite
	 */
	private $socialite;
	/**
	 * @var Guard
	 */
	private $auth;

	/**
	 * Facebook or google or other
	 * @var
	 */
	private $loginType;

	/**
	 * @param UserRepository $userRepo
	 * @param Socialite      $socialite
	 * @param Guard          $auth
	 */
	public function __construct(UserRepository $userRepo, Socialite $socialite, Guard $auth)
	{

		$this->userRepo = $userRepo;
		$this->socialite = $socialite;
		$this->auth = $auth;
	}

	/**
	 * @param string $loginType
	 * @param        $hasCode
	 * @param        $listener
	 *
	 * @return mixed
	 */
	public function execute($loginType = self::LOGIN_TYPE_FB ,$hasCode, $listener)
	{
		$this->loginType = $loginType;

		if( !$hasCode ) {
			return $this->getAuthorizationFirst();
		}

		//retrieve info from social network and create user if not created yet
		$user = $this->userRepo->findByEmailOrCreate($this->getSocialUser());

		$this->auth->login($user, true);

		//callback after login
		return $listener->userHasLoggedIn($user);
	}

	/**
	 * @return mixed
	 */
	private function getAuthorizationFirst()
	{
		return $this->socialite->with($this->loginType)->redirect();
	}

	/**
	 * @return mixed
	 */
	private function getSocialUser()
	{
		return $this->socialite->with($this->loginType)->user();
	}
}
