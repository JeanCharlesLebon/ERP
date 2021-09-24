<?php

namespace Repositories;

use Database;
use Exception;
use Models\Company;

require_once("Database.php");
require_once("Models/Company.php");

class CompanyRepository
{
	private static CompanyRepository $instance;

	public static function getInstance(): CompanyRepository {
		if (!isset($instance)) {
			CompanyRepository::$instance = new CompanyRepository();
		}
		return CompanyRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT companies.id, companies.name, companies.balance, companies.country " .
			"FROM companies");
		$req->execute();
		$res = $req->fetchAll();
		$companies = [];
		foreach ($res as $row) {
			array_push($companies, $this->sqlRowToCompany($row));
		}
		return $companies;
	}

	private function sqlRowToCompany(array $row): Company {
		$company = new Company();
		$company->setId($row[0]);
		$company->setName($row[1]);
		$company->setBalance($row[2]);
		$company->setCountry($row[3]);
		return $company;
	}

	public function createCompany(Company $company): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO companies(name, balance, country) VALUES(:name, :balance, :country)");
		return $req->execute(array(
			"name" => $company->getName(),
			"balance" => $company->getBalance(),
			"country" => $company->getCountry()
		));
	}

	public function updateCompany(Company $company): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE companies SET name=:name, balance=:balance, country=:country WHERE id=:id");
		return $req->execute(array(
			"name" => $company->getName(),
			"balance" => $company->getBalance(),
			"country" => $company->getCountry(),
			"id" => $company->getId()
		));
	}

	public function updateCompanyBalanceAfterTransaction(int $balanceMovement, int $id): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE companies SET balance=balance+:balance WHERE id=:id");
		return $req->execute(array(
			"balance" => $balanceMovement,
			"id" => $id
		));
	}

	public function findCompanyById(int $id): Company {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT companies.id, companies.name, companies.balance, companies.country " .
			"FROM companies WHERE companies.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToCompany($row);
		}
		throw new Exception("Unkown company ID");
	}

	public function deleteCompanyById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM companies WHERE companies.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}