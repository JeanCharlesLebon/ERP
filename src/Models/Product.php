<?php

namespace Models;

class Product
{
	private int $id;
	private int $stock;
	private float $price;
	private float $tax;
	private string $name;
	private int $companyId;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id = $id;
	}

	public function getStock(): int {
		return $this->stock;
	}

	public function setStock(int $stock): void {
		$this->stock = $stock;
	}

	public function getPrice(): float {
		return $this->price;
	}

	public function setPrice(float $price): void {
		$this->price = $price;
	}

	public function getTax(): float {
		return $this->tax;
	}

	public function setTax(float $tax): void {
		$this->tax = $tax;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function getCompanyId(): int {
		return $this->companyId;
	}

	public function setCompanyId(int $companyId): void {
		$this->companyId = $companyId;
	}
}