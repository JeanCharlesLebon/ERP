<?php

namespace Router;

class Route
{
	private string $controller;
	private string $action;
	private array $params;

	public function __construct(string $controller, string $action, array $params) {
		$this->controller = $controller;
		$this->action = $action;
		$this->params = $params;
	}

	public function getController(): string {
		return $this->controller;
	}

	public function getAction(): string {
		return $this->action;
	}

	public function getParams(): array {
		return $this->params;
	}
}