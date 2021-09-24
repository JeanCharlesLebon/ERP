<?php

namespace Controllers;

use Exception;
use Models\Client;
use Repositories\ClientRepository;

require_once("IndexController.php");
require_once("Repositories/ClientRepository.php");

class ClientController extends IndexController
{
	private const EXPECTED = ['name', 'address', 'country'];

	public function getIndex(): void {
		$clients = ClientRepository::getInstance()->findAll();

		self::render('Client/index', ['clients' => $clients]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);

			$client = new Client();
			$client->setName($params['name']);
			$client->setCountry($params['country']);
			$client->setAddress($params['address']);

			if (ClientRepository::getInstance()->createClient($client)) {
				self::redirectToPath("/client");
			} else {
				throw new Exception("Unable to create new client");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {
		//validate role here

		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($client = (ClientRepository::getInstance()->findClientById($id))) {
				self::render('Client/edit', ['client' => $client]);
			} else {
				throw new Exception("Client doesn't exist");
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
			$client = new Client();
			$client->setId($params['id']);
			$client->setName($params['name']);
			$client->setCountry($params['country']);
			$client->setAddress($params['address']);

			if (ClientRepository::getInstance()->updateClient($client)) {
				self::redirectToPath("/client");
			} else {
				throw new Exception("Unable to edit client id " . $params['id']);
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

			if (ClientRepository::getInstance()->deleteClientById($id)) {
				header("/client");
			} else {
				throw new Exception("Unable to delete client with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}