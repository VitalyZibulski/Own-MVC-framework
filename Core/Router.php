<?php

/**
 *	Router
 */

class Router
{
	/**
	 * Array of routes
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Parameters for the matched route
	 *
	 * @var array
	 */


	protected $params = [];

	/**
	 * Add a route to the routing
	 * @param $route - the route URL
	 * @param $params (controller, action, etc.)
	 *
	 * @return void
	 */
	public function add($route, $params)
	{
		$this->routes[$route] = $params;
	}

	/**
	 * Get all the routes from the rounting
	 *
	 * @return array
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * @param $url
	 * @return bool
	 */
	public function match($url)
	{
//		foreach ($this->routes as $route => $params){
//			if($url == $route){
//				$this->params = $params;
//				return true;
//			}
//		}
//		return false;
		//Match to the fixed URL format /controller/action
		$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

		if (preg_match($reg_exp, $url, $matches)) {
			//Get named capture group values
			$params = [];

			foreach ($matches as $key => $match) {
				if (is_string($key)) {
					$params[$key] = $match;
				}
			}

			$this->params = $params;
			return true;
		}
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}
}