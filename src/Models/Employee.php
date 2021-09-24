<?php

namespace Models;

use DateTime;

class Employee
{
	private int $id;
	private string $name;
	private string $country;
	private DateTime $birthday;
	private DateTime $firstDayInCompany;
	private int $companyId;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id = $id;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function getCountry(): string {
		return $this->country;
	}

	public function setCountry(string $country): void {
		$this->country = $country;
	}

	public function getBirthday(): DateTime {
		return $this->birthday;
	}

	public function setBirthday(DateTime $birthday): void {
		$this->birthday = $birthday;
	}

	public function getFirstDayInCompany(): DateTime {
		return $this->firstDayInCompany;
	}

	public function setFirstDayInCompany(DateTime $firstDayInCompany): void {
		$this->firstDayInCompany = $firstDayInCompany;
	}

	public function getCompanyId(): int {
		return $this->companyId;
	}

	public function setCompanyId(int $companyId): void {
		$this->companyId = $companyId;
	}
}