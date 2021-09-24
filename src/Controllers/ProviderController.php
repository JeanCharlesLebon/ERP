<?php

namespace Controllers;

use Exception;
use Models\Provider;
use Repositories\ProviderRepository;

require_once("IndexController.php");
require_once("Repositories/ProviderRepository.php");

class ProviderController extends IndexController
{
	private const EXPECTED = ['name', 'address', 'country'];

	public function getIndex(): void {
		$providers = ProviderRepository::getInstance()->findAll();

		self::render('Provider/index', ['providers' => $providers]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);

			$provider = new Provider();
			$provider->setName($params['name']);
			$provider->setCountry($params['country']);
			$provider->setAddress($params['address']);

			if (ProviderRepository::getInstance()->createProvider($provider)) {
				self::redirectToPath("/provider");
			} else {
				throw new Exception("Unable to create new provider");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {
		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($provider = (ProviderRepository::getInstance()->findProviderById($id))) {
				self::render('Provider/edit', ['provider' => $provider]);
			} else {
				throw new Exception("Provider doesn't exist");
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
			$provider = new Provider();
			$provider->setId($params['id']);
			$provider->setName($params['name']);
			$provider->setCountry($params['country']);
			$provider->setAddress($params['address']);

			if (ProviderRepository::getInstance()->updateProvider($provider)) {
				self::redirectToPath("/provider");
			} else {
				throw new Exception("Unable to edit provider id " . $params['id']);
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

			if (ProviderRepository::getInstance()->deleteProviderById($id)) {
				self::redirectToPath("/provider");
			} else {
				throw new Exception("Unable to delete provider with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}