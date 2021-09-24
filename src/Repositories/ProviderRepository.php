<?php

namespace Repositories;

use Database;
use Models\Provider;

require_once("Database.php");
require_once("Models/Provider.php");

class ProviderRepository
{
	private static ProviderRepository $instance;

	public static function getInstance(): ProviderRepository {
		if (!isset($instance)) {
			ProviderRepository::$instance = new ProviderRepository();
		}
		return ProviderRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT providers.id, providers.name, providers.address, providers.country " .
			"FROM providers");
		$req->execute();
		$res = $req->fetchAll();
		$providers = [];
		foreach ($res as $row) {
			array_push($providers, $this->sqlRowToProvider($row));
		}
		return $providers;
	}

	private function sqlRowToProvider(array $row): Provider {
		$provider = new Provider();
		$provider->setId($row[0]);
		$provider->setName($row[1]);
		$provider->setAddress($row[2]);
		$provider->setCountry($row[3]);
		return $provider;
	}

	public function createProvider(Provider $provider): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO providers(name, address, country) VALUES(:name, :address, :country)");
		return $req->execute(array(
			"name" => $provider->getName(),
			"address" => $provider->getAddress(),
			"country" => $provider->getCountry()
		));
	}

	public function updateProvider(Provider $provider): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE providers SET name=:name, address=:address, country=:country WHERE id=:id");
		return $req->execute(array(
			"name" => $provider->getName(),
			"address" => $provider->getAddress(),
			"country" => $provider->getCountry(),
			"id" => $provider->getId()
		));
	}

	public function findProviderById(int $id): Provider {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT providers.id, providers.name, providers.address, providers.country " .
			"FROM providers WHERE providers.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToProvider($row);
		}
		throw new Exception("Unkown provider ID");
	}

	public function deleteProviderById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM providers WHERE providers.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}