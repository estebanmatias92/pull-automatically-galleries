<?php

use PullAutomaticallyGalleries\Storage\User\UserRepositoryInterface;
use PullAutomaticallyGalleries\Storage\RemoteUser\RemoteUserRepositoryInterface;
use RestGalleries\Exception\AuthException;

class UserController extends BaseController
{
	/**
	 * User Repository.
	 *
	 * @var \PullAutomaticallyGalleries\Storage\User\UserRepositoryInterface
	 */
	protected $user;

	/**
	 * RemoteUser Repository.
	 *
	 * @var \PullAutomaticallyGalleries\Storage\RemoteUser\RemoteUserRepositoryInterface
	 */
	protected $remoteUser;

	/**
	 * Initializes model instance vars.
	 *
	 * @param  \PullAutomaticallyGalleries\Storage\User\UserRepositoryInterface             $user
	 * @param  \PullAutomaticallyGalleries\Storage\RemoteUser\RemoteUserRepositoryInterface $remoteUser
	 * @return void
	 */
	public function __construct(UserRepositoryInterface $user, RemoteUserRepositoryInterface $remoteUser)
	{
		$this->user       = $user;
		$this->remoteUser = $remoteUser;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = $this->user->all();

		return View::make('users.index')->with('users', $users);
	}

	/**
	 * Creates new User after the gallery web-service authentication.
	 *
	 * @return Response
	 */
	public function connect()
	{
		$host     = Input::get('host');
		$redirect = Request::path();

		try {

			$remoteUserData = $this->remoteUser->connect($host, $redirect);

			$this->user->create($remoteUserData);

			return Redirect::route('users.index')->with('message','New account connected !');

		} catch (AuthException $e) {

			$error = $e->getMessage();

			return Redirect::route('users.index')->with('message', $error);

		}

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->user->delete($id);

		return Redirect::route('users.index')->with('message','Account disconnected');
	}

}
