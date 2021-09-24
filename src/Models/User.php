<?php

namespace Models;

class User
{
	private int $id;
	private string $name;
	private string $role = '';
	private string $password;

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

	public function getRole(): string {
		return $this->role;
	}

	public function setRole(string $role): void {
		$this->role = $role;
	}

	public function getPwd(): string {
		return $this->password;
	}

	public function setPwd(string $pwd): void {
		$this->password = $pwd;
	}
}