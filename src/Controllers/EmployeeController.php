<?php

namespace Controllers;

use DateTime;
use Exception;
use Models\Employee;
use Repositories\EmployeeRepository;

require_once("IndexController.php");
require_once("Repositories/EmployeeRepository.php");

class EmployeeController extends IndexController
{
	private const EXPECTED = ['name', 'country', 'birthday', 'firstDayInCompany', 'companyId'];

	public function getIndex(): void {
		$employees = EmployeeRepository::getInstance()->findAll();

		self::render('Employee/index', ['employees' => $employees]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);


			$birthday = new DateTime($params['birthday']);
			$firstDayInCompany = new DateTime($params['firstDayInCompany']);

			$employee = new Employee();
			$employee->setName($params['name']);
			$employee->setCountry($params['country']);
			$employee->setBirthday($birthday);
			$employee->setFirstDayInCompany($firstDayInCompany);
			$employee->setCompanyId($params['companyId']);

			if (EmployeeRepository::getInstance()->createEmployee($employee)) {
				self::redirectToPath("/employee");
			} else {
				throw new Exception("Unable to create new employee");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {
		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($employee = (EmployeeRepository::getInstance()->findEmployeeById($id))) {
				self::render('Employee/edit', ['employee' => $employee]);
			} else {
				throw new Exception("Employee doesn't exist");
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

			$birthday = new DateTime($params['birthday']);
			$firstDayInCompany = new DateTime($params['firstDayInCompany']);
			$employee = new Employee();
			$employee->setId($params['id']);
			$employee->setName($params['name']);
			$employee->setCountry($params['country']);
			$employee->setBirthday($birthday);
			$employee->setFirstDayInCompany($firstDayInCompany);
			$employee->setCompanyId($params['companyId']);

			if (EmployeeRepository::getInstance()->updateEmployee($employee)) {
				self::redirectToPath("/employee");
			} else {
				throw new Exception("Unable to edit employee id " . $params['id']);
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

			if (EmployeeRepository::getInstance()->deleteEmployeeById($id)) {
				self::redirectToPath("/employee");
			} else {
				throw new Exception("Unable to delete employee with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}