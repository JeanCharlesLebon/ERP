<?php

const DSN = "mysql:host=mysql;port=3306;dbname=erp";
const DB_USER = "root";
const DB_PASSWORD = "root";

class Database
{
	private static PDO $instance;

	public static function getInstance(): PDO {
		if (!isset(Database::$instance)) {
			Database::initDb();
		}
		return Database::$instance;
	}

	private static function initDb(): void {
		try {
			Database::$instance = new PDO(
				DSN,
				DB_USER,
				DB_PASSWORD
			);
		} catch (Exception $ex) {
			echo " EXCEPTION : ";
			print_r($ex);
			printf(DSN);
			error_log("Unable to set up db connection: " . DSN);
			die(500);
		}
	}

}