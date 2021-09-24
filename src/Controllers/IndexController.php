<?php

namespace Controllers;

use Exception;

abstract class IndexController
{
	public static function validateParams(array $expected, array $params): void {
		foreach ($expected as $param) {
			if (!in_array($param, array_keys($params))) {
				throw new Exception("Invalid query parameters.");
			}
		}
	}

	public function validateRoleIsAdmin(): void {
		if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["role"]) || $_SESSION["user"]["role"] != 'admin') {
			self::redirectToError("Permission denied");
		}
	}

	public function redirectToError(string $errorMessage): void {
		self::render("Error/index", ["error" => $errorMessage]);
		die(400);
	}

	public function render(string $fichier, array $data = []): void {
		$data["user"] = $_SESSION["user"];
		extract($data);

		require_once('Views/' . '/' . $fichier . '.php');
	}

	public function redirectToPath(string $path): void {
		header('Location: ' . $path);
		exit();
	}
}