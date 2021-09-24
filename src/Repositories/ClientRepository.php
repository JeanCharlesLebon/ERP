<?php

namespace Repositories;

use Database;
use Models\Client;

require_once("Database.php");
require_once("Models/Client.php");

class ClientRepository
{
	private static ClientRepository $instance;

	public static function getInstance(): ClientRepository {
		if (!isset($instance)) {
			ClientRepository::$instance = new ClientRepository();
		}
		return ClientRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT clients.id, clients.name, clients.address, clients.country FROM clients");
		$req->execute();
		$res = $req->fetchAll();
		$clients = [];
		foreach ($res as $row) {
			array_push($clients, $this->sqlRowToCLient($row));
		}
		return $clients;
	}

	private function sqlRowToCLient(array $row): Client {
		$client = new Client();
		$client->setId($row[0]);
		$client->setName($row[1]);
		$client->setAddress($row[2]);
		$client->setCountry($row[3]);
		return $client;
	}

	public function createClient(Client $client): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO clients(name, address, country) VALUES(:name, :address, :country)");
		return $req->execute(array(
			"name" => $client->getName(),
			"address" => $client->getAddress(),
			"country" => $client->getCountry()
		));
	}

	public function updateClient(Client $client): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE clients SET name=:name, address=:address, country=:country WHERE id=:id");
		return $req->execute(array(
			"name" => $client->getName(),
			"address" => $client->getAddress(),
			"country" => $client->getCountry(),
			"id" => $client->getId()
		));
	}

	public function findClientById(int $id): Client {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT clients.id, clients.name, clients.address, clients.country FROM clients " .
			"WHERE clients.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToCLient($row);
		}
		throw new Exception("Unkown client ID");
	}

	public function deleteClientById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM clients WHERE clients.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}