<?php

namespace Controllers;

use Exception;
use Models\User;
use Repositories\UserRepository;

require_once("IndexController.php");
require_once("Repositories/UserRepository.php");

class AuthenticationController extends IndexController
{
	private const EXPECTED = ['name', 'password'];

	public function getLogin(): void {
		self::render('Authentication/login');
	}

	public function postLogin(array $params): void {
		try {
			self::validateParams(self::EXPECTED, $params);

			$user = (UserRepository::getInstance()->findUserByName($params['name']));

			if (password_verify($params['password'], $user->getPwd())) {
				$_SESSION['user'] = [
					"name" => $user->getName(),
					"role" => $user->getRole()
				];
				self::redirectToPath("/");
			} else {
				throw new Exception("Wrong password");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getLogout(): void {
		session_destroy();
		self::redirectToPath("/");
	}

	public function getRegister(array $params): void {
		self::render('Authentication/register');
	}

	public function postRegister(array $params): void {
		try {
			self::validateParams(array_merge(self::EXPECTED), $params);

			$user = new User();
			$user->setName($params['name']);
			$user->setPwd(password_hash($params['password'], PASSWORD_BCRYPT));

			if (UserRepository::getInstance()->createUser($user)) {
				$_SESSION['user'] = [
					"name" => $user->getName(),
					"role" => $user->getRole()
				];
				self::redirectToPath("/");
			} else {
				throw new Exception("Unable to create new user. User name might be taken already");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}
}