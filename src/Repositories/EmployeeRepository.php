<?php

namespace Repositories;

use Database;
use DateTime;
use Models\Employee;

require_once("Database.php");
require_once("Models/Employee.php");

class EmployeeRepository
{
	private static EmployeeRepository $instance;

	public static function getInstance(): EmployeeRepository {
		if (!isset($instance)) {
			EmployeeRepository::$instance = new EmployeeRepository();
		}
		return EmployeeRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT employees.id, employees.name, employees.country, employees.birthday, " .
			"employees.firstDayInCompany, employees.companyId FROM employees");
		$req->execute();
		$res = $req->fetchAll();
		$employees = [];
		foreach ($res as $row) {
			array_push($employees, $this->sqlRowToEmployee($row));
		}
		return $employees;
	}

	private function sqlRowToEmployee(array $row): Employee {
		$employee = new Employee();
		$birthday = new DateTime($row[3]);
		$firstDayInCompany = new DateTime($row[4]);

		$employee->setId($row[0]);
		$employee->setName($row[1]);
		$employee->setCountry($row[2]);
		$employee->setBirthday($birthday);
		$employee->setFirstDayInCompany($firstDayInCompany);
		$employee->setCompanyId($row[5]);
		return $employee;
	}

	public function createEmployee(Employee $employee): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO employees(name, country, birthday, firstDayInCompany, companyId) " .
			"VALUES(:name, :country, :birthday, :firstDayInCompany, companyId=:companyId)");
		return $req->execute(array(
			"name" => $employee->getName(),
			"country" => $employee->getCountry(),
			"birthday" => $employee->getBirthday()->format('Y-m-d H:i:s'),
			"firstDayInCompany" => $employee->getFirstDayInCompany()->format('Y-m-d H:i:s'),
			"companyId" => $employee->getCompanyId()
		));
	}

	public function updateEmployee(Employee $employee): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE employees SET name=:name, country=:country, birthday=:birthday, " .
			"firstDayInCompany=:firstDayInCompany, companyId=:companyId WHERE id=:id");
		return $req->execute(array(
			"name" => $employee->getName(),
			"country" => $employee->getCountry(),
			"birthday" => $employee->getBirthday()->format('Y-m-d'),
			"firstDayInCompany" => $employee->getFirstDayInCompany()->format('Y-m-d'),
			"companyId" => $employee->getCompanyId(),
			"id" => $employee->getId()
		));
	}

	public function findEmployeeById(int $id): Employee {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT employees.id, employees.name, employees.country, employees.birthday, " .
			"employees.firstDayInCompany, employees.companyId FROM employees WHERE employees.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToEmployee($row);
		}
		throw new Exception("Unkown employee ID");
	}

	public function deleteEmployeeById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM employees WHERE employees.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}