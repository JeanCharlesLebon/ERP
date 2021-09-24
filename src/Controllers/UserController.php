<?php

namespace Controllers;

use Exception;
use Models\User;
use Repositories\UserRepository;

require_once("IndexController.php");
require_once("Repositories/UserRepository.php");

class UserController extends IndexController
{
	private const EXPECTED = ['name', 'role', 'password'];

	public function getIndex(): void {
		$users = UserRepository::getInstance()->findAll();

		self::render('User/index', ['users' => $users]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);

			$user = new User();
			$user->setName($params['name']);
			$user->setRole($params['role']);
			$user->setPwd($params['password']);

			if (UserRepository::getInstance()->createUser($user)) {
				self::redirectToPath("/user");
			} else {
				throw new Exception("Unable to create new user");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {
		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($user = (UserRepository::getInstance()->findUserById($id))) {
				self::render('User/edit', ['user' => $user]);
			} else {
				throw new Exception("User doesn't exist");
			}
		} catch (Exception $exception) {
			echo $exception->getMessage();
			self::redirectToError($exception->getMessage());
		}
	}

	public function postEdit(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(array_merge(self::EXPECTED, ['id']), $params);
			$user = new User();
			$user->setId($params['id']);
			$user->setName($params['name']);
			$user->setRole($params['role']);
			$user->setPwd($params['password']);

			if (UserRepository::getInstance()->updateUser($user)) {
				self::redirectToPath("/user");
			} else {
				throw new Exception("Unable to edit user id " . $params['id']);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}


	public function getDelete(array $params): void {
		self::validateRoleIsAdmin();

		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if (UserRepository::getInstance()->deleteUserById($id)) {
				self::redirectToPath("/user");
			} else {
				throw new Exception("Unable to delete user with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}