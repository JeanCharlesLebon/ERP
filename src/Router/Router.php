<?php

namespace Router;
require_once("Route.php");

class Router
{
	private static Router $instance;

	public static function getInstance(): Router {
		if (!isset($instance)) {
			Router::$instance = new Router();
		}
		return Router::$instance;
	}

	public function route(string $method, string $uri, array $postParams): void {
		$route = $this->buildRoute($method, $uri, $postParams);

		require_once(str_replace('\\', '/', $route->getController()) . '.php');

		if (class_exists($route->getController())) {
			if (in_array($route->getAction(), get_class_methods($route->getController()))) {
				if (is_null($route->getParams())) {
					call_user_func([$route->getController(), $route->getAction()]);
				} else {
					call_user_func_array([$route->getController(), $route->getAction()], [$route->getParams()]);
				}
			} else {
				http_response_code(404);
			}
		} else {
			http_response_code(404);
		}
	}

	public function buildRoute(string $method, string $request_uri, array $postParams): Route {
		$request_uri = explode("/", $request_uri);
		$request = [];
		foreach ($request_uri as $s) {
			if (!empty($s)) {
				array_push($request, $s);
			}
		}

		$controller = "Controllers\\" . (sizeof($request) > 0 ? ucfirst(array_shift($request)) . 'Controller'
				: 'IndexController');
		$action = strtolower($method) . ucfirst(sizeof($request) > 0 ? array_shift($request) : 'index');

		$params = [];
		foreach ($postParams as $k => $v) {
			$params[$k] = $v;
		}

		if (sizeof($request) > 0) {
			$params["id"] = array_shift($request);
		}
		return new Route($controller, $action, $params);
	}
}