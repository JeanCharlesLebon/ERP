<?php

namespace Controllers;

use Exception;
use Models\Product;
use Repositories\ProductRepository;

require_once("IndexController.php");
require_once("Repositories/ProductRepository.php");

class ProductController extends IndexController
{
	private const EXPECTED = ['name', 'stock', 'price', 'tax', 'companyId'];

	public function getIndex() {
		$products = ProductRepository::getInstance()->findAll();

		self::render('Product/index', ['products' => $products]);
	}

	public function postAdd(array $params): void {
		self::validateRoleIsAdmin();

		try {
			self::validateParams(self::EXPECTED, $params);

			$product = new Product();
			$product->setName($params['name']);
			$product->setStock($params['stock']);
			$product->setPrice($params['price']);
			$product->setTax($params['tax']);
			$product->setCompanyId($params['companyId']);

			if (ProductRepository::getInstance()->createProduct($product)) {
				self::redirectToPath("/product");
			} else {
				throw new Exception("Unable to create new product");
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}
	}

	public function getEdit(array $params): void {
		$id = intval($params['id']);

		try {
			self::validateParams(['id'], $params);

			if ($product = (ProductRepository::getInstance()->findProductById($id))) {
				self::render('Product/edit', ['product' => $product]);
			} else {
				throw new Exception("Product doesn't exist");
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
			$product = new Product();
			$product->setId($params['id']);
			$product->setName($params['name']);
			$product->setStock($params['stock']);
			$product->setPrice($params['price']);
			$product->setTax($params['tax']);
			$product->setCompanyId($params['companyId']);

			if (ProductRepository::getInstance()->updateProduct($product)) {
				self::redirectToPath("/product");
			} else {
				throw new Exception("Unable to edit product id " . $params['id']);
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

			if (ProductRepository::getInstance()->deleteProductById($id)) {
				self::redirectToPath("/product");
			} else {
				throw new Exception("Unable to delete product with id " . $id);
			}
		} catch (Exception $exception) {
			self::redirectToError($exception->getMessage());
		}

	}
}