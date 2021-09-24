<?php

namespace Controllers;

use Exception;
use Models\Company;
use Repositories\CompanyRepository;


require_once("IndexController.php");
require_once("Repositories/CompanyRepository.php");

class CompanyController extends IndexController
{
	private const EXPECTED = ['name', 'balance', 'country'];

	public function getIndex(): void {
		$companies = CompanyRepository::getInstance()->findAll();

		self::render('Company/index', ['companies' => $companies]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);

			$company = new Company();
			$company->setName($params['name']);
			$company->setCountry($params['country']);
			$company->setBalance($params['balance']);

			if (CompanyRepository::getInstance()->createCompany($company)) {
				self::redirectToPath("/company");
			} else {
				throw new Exception("Unable to create new company");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {

		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($company = (CompanyRepository::getInstance()->findCompanyById($id))) {
				self::render('Company/edit', ['company' => $company]);
			} else {
				throw new Exception("Company doesn't exist");
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
			$company = new Company();
			$company->setId($params['id']);
			$company->setName($params['name']);
			$company->setCountry($params['country']);
			$company->setBalance($params['balance']);

			if (CompanyRepository::getInstance()->updateCompany($company)) {
				self::redirectToPath("/company");
			} else {
				throw new Exception("Unable to edit company id " . $params['id']);
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

			if (CompanyRepository::getInstance()->deleteCompanyById($id)) {
				self::redirectToPath("/company");
			} else {
				throw new Exception("Unable to delete company with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}