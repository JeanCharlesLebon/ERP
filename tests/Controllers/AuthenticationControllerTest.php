<?php

use PHPUnit\Framework\TestCase;
use Controllers\AuthenticationController;

class AuthenticationControllerTest extends TestCase
{
	public function testGetLogin() {
		$authController = $this->createMock(AuthenticationController::class);
		$this->assertNull($authController->getLogin());
	}
}