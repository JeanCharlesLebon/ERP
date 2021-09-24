<?php

namespace Models;

class Transaction
{
	// the assignment mentions 5 basic entities when there are actually 6 of them
	// can there be transactions for several products at the same time, let's say product X with quantity 2 and produc Y with quantity 7 or do i have to link each transaction to only 1 product ?

	private int $id;
	private int $clientId;
	private int $companyId;
	private int $providerId;
	private int $productId;
	private int $productQuantity;
	private int $responsibleEmployeeId;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id = $id;
	}

	public function getCompanyId(): int {
		return $this->companyId;
	}

	public function setCompanyId(int $companyId): void {
		$this->companyId = $companyId;
	}

	public function getClientId(): int {
		return $this->clientId;
	}

	public function setClientId(int $clientId): void {
		$this->clientId = $clientId;
	}

	public function getProviderId(): int {
		return $this->providerId;
	}

	public function setProviderId(int $providerId): void {
		$this->providerId = $providerId;
	}

	public function getProductId(): int {
		return $this->productId;
	}

	public function setProductId(int $productId): void {
		$this->productId = $productId;
	}

	public function getProductQuantity(): int {
		return $this->productQuantity;
	}

	public function setProductQuantity(int $productQuantity): void {
		$this->productQuantity = $productQuantity;
	}

	public function getResponsibleEmployeeId(): int {
		return $this->responsibleEmployeeId;
	}

	public function setResponsibleEmployeeId(int $responsibleEmployeeId): void {
		$this->responsibleEmployeeId = $responsibleEmployeeId;
	}
}