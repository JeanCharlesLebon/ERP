<?php

namespace Models;

class Company
{
	private int $id;
	private float $balance;
	private string $name;
	private string $country;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id = $id;
	}

	public function getBalance(): float {
		return $this->balance;
	}

	public function setBalance(float $balance): void {
		$this->balance = $balance;
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
}