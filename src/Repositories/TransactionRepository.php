<?php

namespace Repositories;

use Database;
use Exception;
use Models\Transaction;

require_once("Database.php");
require_once("Models/Transaction.php");

class TransactionRepository
{
	private static TransactionRepository $instance;

	public static function getInstance(): TransactionRepository {
		if (!isset($instance)) {
			TransactionRepository::$instance = new TransactionRepository();
		}
		return TransactionRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT transactions.id, transactions.clientId, transactions.companyId, " .
			"transactions.providerId, transactions.productId, transactions.productQuantity, " .
			"transactions.responsibleEmployeeId " .
			"FROM transactions");
		$req->execute();
		$res = $req->fetchAll();
		$transactions = [];
		foreach ($res as $row) {
			array_push($transactions, $this->sqlRowToTransaction($row));
		}
		return $transactions;
	}

	private function sqlRowToTransaction(array $row): Transaction {
		$transaction = new Transaction();
		$transaction->setId($row[0]);
		$transaction->setClientId($row[1] == null ? 0 : $row[1]);
		$transaction->setCompanyId($row[2]);
		$transaction->setProviderId($row[3] == null ? 0 : $row[3]);
		$transaction->setProductId($row[4]);
		$transaction->setProductQuantity($row[5]);
		$transaction->setResponsibleEmployeeId($row[6]);
		return $transaction;
	}

	public function createTransaction(Transaction $transaction): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO transactions(clientId, companyId, providerId, productId, " .
			"productQuantity, responsibleEmployeeId) VALUES(:clientId, :companyId, :providerId, :productId, " .
			":productQuantity, :responsibleEmployeeId)");
		return $req->execute(array(
			"clientId" => $transaction->getClientId() == 0 ? null : $transaction->getClientId(),
			"companyId" => $transaction->getCompanyId(),
			"providerId" => $transaction->getProviderId() == 0 ? null : $transaction->getProviderId(),
			"productId" => $transaction->getProductId(),
			"productQuantity" => $transaction->getProductQuantity(),
			"responsibleEmployeeId" => $transaction->getResponsibleEmployeeId()
		));
	}

	public function updateTransaction(Transaction $transaction): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE transactions SET clientId=:clientId, companyId=:companyId, " .
			"providerId=:providerId, productId=:productId, productQuantity=:productQuantity, " .
			"responsibleEmployeeId=:responsibleEmployeeId WHERE id=:id");
		return $req->execute(array(
			"clientId" => $transaction->getClientId() == 0 ? null : $transaction->getClientId(),
			"companyId" => $transaction->getCompanyId(),
			"providerId" => $transaction->getProviderId() == 0 ? null : $transaction->getProviderId(),
			"productId" => $transaction->getProductId(),
			"productQuantity" => $transaction->getProductQuantity(),
			"responsibleEmployeeId" => $transaction->getResponsibleEmployeeId(),
			"id" => $transaction->getId()
		));
	}

	public function findTransactionById(int $id): Transaction {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT transactions.id, transactions.clientId, transactions.companyId, " .
			"transactions.providerId, transactions.productId, transactions.productQuantity, " .
			"transactions.responsibleEmployeeId  FROM transactions WHERE transactions.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToTransaction($row);
		}
		throw new Exception("Unkown transaction ID");
	}

	public function deleteTransactionById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM transactions WHERE transactions.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}