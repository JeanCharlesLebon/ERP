<?php

namespace Repositories;

use Database;
use Exception;
use Models\User;

require_once("Database.php");
require_once("Models/User.php");

class UserRepository
{
	private static UserRepository $instance;

	public static function getInstance(): UserRepository {
		if (!isset($instance)) {
			UserRepository::$instance = new UserRepository();
		}
		return UserRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT users.id, users.name, users.role, users.password FROM users");
		$req->execute();
		$res = $req->fetchAll();
		$users = [];
		foreach ($res as $row) {
			array_push($users, $this->sqlRowToUser($row));
		}
		return $users;
	}

	private function sqlRowToUser(array $row): User {
		$user = new User();
		$user->setId($row[0]);
		$user->setName($row[1]);
		$user->setRole($row[2]);
		$user->setPwd($row[3]);
		return $user;
	}

	public function createUser(User $user): bool {
		$pdo = Database::getInstance();

		if (self::findUserByName($user->getName())) {
			return false;
		}

		$req = $pdo->prepare("INSERT INTO users(name, role, password) VALUES(:name, :role, :password)");
		return $req->execute(array(
			"name" => $user->getName(),
			"role" => $user->getRole() == '' ? 'reader' : $user->getRole(),
			"password" => $user->getPwd()
		));
	}

	public function findUserByName(string $name): ?User {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT users.id, users.name, users.role, users.password FROM users " .
			"WHERE users.name = :name");
		$req->bindParam(":name", $name);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToUser($row);
		}
		return null;
	}

	public function updateUser(User $user): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE users SET name=:name, role=:role, password=:password WHERE id=:id");
		return $req->execute(array(
			"name" => $user->getName(),
			"role" => $user->getRole(),
			"password" => $user->getPwd(),
			"id" => $user->getId()
		));
	}

	public function findUserById(int $id): User {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT users.id, users.name, users.role, users.password FROM users " .
			"WHERE users.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToUser($row);
		}
		throw new Exception("Unkown user ID");
	}

	public function deleteUserById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM users WHERE users.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}