<?php

namespace Controllers;

use Exception;
use Models\Transaction;
use Repositories\CompanyRepository;
use Repositories\ProductRepository;
use Repositories\TransactionRepository;

require_once("IndexController.php");
require_once("Repositories/TransactionRepository.php");
require_once("Repositories/ProductRepository.php");
require_once("Repositories/CompanyRepository.php");

class TransactionController extends IndexController
{
	private const EXPECTED = ['clientId', 'companyId', 'providerId', 'productId',
		'productQuantity', 'responsibleEmployeeId'];

	public function getIndex() {
		$transactions = TransactionRepository::getInstance()->findAll();

		self::render('Transaction/index', ['transactions' => $transactions]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);
			$transaction = self::fillTransactionWithPost($params);

			$product = ProductRepository::getInstance()->findProductById($transaction->getProductId());

			if (self::isTransactionPossible($transaction, $product) &&
				TransactionRepository::getInstance()->createTransaction($transaction)) {

				$balanceMovement = ($product->getPrice() + $product->getTax()) * $transaction->getProductQuantity();
				$stockMovement = $transaction->getProductQuantity();

				if ($transaction->getProviderId()) {
					$balanceMovement = -1 * $balanceMovement;
				} else if ($transaction->getClientId()) {
					$stockMovement = -1 * $transaction->getProductQuantity();
				}

				self::updateStockAndBalanceAfterTransaction($transaction, $balanceMovement, $stockMovement);
				self::redirectToPath("/transaction");

			} else {
				throw new Exception("Unable to create new transaction");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	private function fillTransactionWithPost(array $params): Transaction {
		$transaction = new Transaction();

		$transaction->setClientId(intval($params['clientId']));
		$transaction->setCompanyId(intval($params['companyId']));
		$transaction->setProviderId(intval($params['providerId']));
		$transaction->setProductId(intval($params['productId']));
		$transaction->setProductQuantity(intval($params['productQuantity']));
		$transaction->setResponsibleEmployeeId(intval($params['responsibleEmployeeId']));

		return $transaction;
	}

	private function isTransactionPossible(Transaction $transaction, $product): bool {
		try {
			if ($transaction->getProviderId()) {
				$company = CompanyRepository::getInstance()->findCompanyById($transaction->getCompanyId());
				$cost = ($product->getPrice() + $product->getTax()) * $transaction->getProductQuantity();
				if ($cost <= $company->getBalance())
					return true;
			} else if ($transaction->getClientId()) {
				if ($transaction->getProductQuantity() <= $product->getStock())
					return true;
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
		return false;
	}

	private function updateStockAndBalanceAfterTransaction(Transaction $transaction, float $balanceMovement, int $stockMovement): void {

		ProductRepository::getInstance()->updateProductStockAfterTransaction($stockMovement,
			$transaction->getProductId());
		CompanyRepository::getInstance()->updateCompanyBalanceAfterTransaction($balanceMovement,
			$transaction->getCompanyId());
	}

	public function getEdit(array $params): void {
		$id = intval($params['id']);

		try {

			self::validateParams(['id'], $params);

			if ($transaction = (TransactionRepository::getInstance()->findTransactionById($id))) {
				self::render('Transaction/edit', ['transaction' => $transaction]);
			} else {
				throw new Exception("Transaction doesn't exist");
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

			$transaction = self::fillTransactionWithPost($params);
			$transaction->setId(intval($params['id']));
			$product = ProductRepository::getInstance()->findProductById($transaction->getProductId());

			$oldTransaction = TransactionRepository::getInstance()->findTransactionById(intval($params['id']));

			$stockMovement = $transaction->getProductQuantity() - $oldTransaction->getProductQuantity();
			$oldCost = ($product->getPrice() + $product->getTax()) * $oldTransaction->getProductQuantity();
			$newCost = ($product->getPrice() + $product->getTax()) * $transaction->getProductQuantity();
			$balanceMovement = $newCost - $oldCost;

			if (TransactionRepository::getInstance()->updateTransaction($transaction)) {
				self::updateStockAndBalanceAfterTransaction($transaction, $balanceMovement, $stockMovement);
				self::redirectToPath("/transaction");
			} else {
				throw new Exception("Unable to edit transaction id " . $params['id']);
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

			if (TransactionRepository::getInstance()->deleteTransactionById($id)) {
				self::redirectToPath("/transaction");
			} else {
				throw new Exception("Unable to delete transaction with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}