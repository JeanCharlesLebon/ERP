<?php

namespace Repositories;

use Database;
use Exception;
use Models\Product;

require_once("Database.php");
require_once("Models/Product.php");

class ProductRepository
{
	private static ProductRepository $instance;

	public static function getInstance(): ProductRepository {
		if (!isset($instance)) {
			ProductRepository::$instance = new ProductRepository();
		}
		return ProductRepository::$instance;
	}

	public function findAll(): array {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT products.id, products.name, products.stock, products.price, products.tax, " .
			"products.companyId FROM products");
		$req->execute();
		$res = $req->fetchAll();
		$products = [];
		foreach ($res as $row) {
			array_push($products, $this->sqlRowToProduct($row));
		}
		return $products;
	}

	private function sqlRowToProduct(array $row): Product {
		$product = new Product();
		$product->setId($row[0]);
		$product->setName($row[1]);
		$product->setStock($row[2]);
		$product->setPrice($row[3]);
		$product->setTax($row[4]);
		$product->setCompanyId($row[5]);
		return $product;
	}

	public function createProduct(Product $product): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("INSERT INTO products(name, stock, price, tax, companyId) " .
			"VALUES(:name, :stock, :price, :tax, :companyId)");
		return $req->execute(array(
			"name" => $product->getName(),
			"stock" => $product->getStock(),
			"price" => $product->getPrice(),
			"tax" => $product->getTax(),
			"companyId" => $product->getCompanyId()
		));
	}

	public function updateProduct(Product $product): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE products SET name=:name, stock=:stock, price=:price, tax=:tax, " .
			"companyId=:companyId WHERE id=:id");
		return $req->execute(array(
			"name" => $product->getName(),
			"stock" => $product->getStock(),
			"price" => $product->getPrice(),
			"tax" => $product->getTax(),
			"companyId" => $product->getCompanyId(),
			"id" => $product->getId()
		));
	}

	public function updateProductStockAfterTransaction(int $stockMovement, int $id): bool {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("UPDATE products SET stock=stock+:stock WHERE id=:id");
		return $req->execute(array(
			"stock" => $stockMovement,
			"id" => $id
		));
	}

	public function findProductById(int $id): Product {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT products.id, products.name, products.stock, products.price, products.tax, " .
			"products.companyId FROM products WHERE products.id = :id");
		$req->bindParam(":id", $id);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToProduct($row);
		}
		throw new Exception("Unkown product ID");
	}

	public function findAllProductsByCompany(int $companyId): Product {
		$pdo = Database::getInstance();

		$req = $pdo->prepare("SELECT products.id, products.name, products.stock, products.price, products.tax " .
			"products.companyId FROM products WHERE products.companyId = :companyId");
		$req->bindParam(":companyId", $companyId);
		if ($req->execute() && $req->rowCount() == 1) {
			$row = $req->fetch();
			return $this->sqlRowToProduct($row);
		}
		throw new Exception("Error with products from company with id " . $companyId);
	}

	public function deleteProductById(int $id): bool {
		$pdo = Database::getInstance();
		$req = $pdo->prepare("DELETE FROM products WHERE products.id = :id");
		$req->bindParam(":id", $id);
		return $req->execute();
	}
}