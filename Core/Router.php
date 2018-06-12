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
	public function add($route, $params = [])
	{
		//Convert the route to reg_exp, escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		//Convert variebles e.g. {controller}
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

		//Convert variebles with custom reg_exp {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		//Add start and end delimiters
		$route = '/^' . $route . '$/i';

		$this->routes[$route] = $params;
	}

	/**
	 * Get all the routes from the routing
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

		foreach ($this->routes as $route => $params){
				if (preg_match($route, $url, $matches)) {
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
		return true;
	}


	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	public function dispatch($url){

		if($this->match($url)){
			$controller = $this->params['controller'];
			$controller = $this->convertToStudlyCaps($controller);
			if(class_exists($controller)){
				$controller_object = new $controller();
				$action = $this->params['action'];
				$action = $this->convertToCamelCase($action);
				if(is_callable($controller_object, $action)){
					$controller_object->$action();
				} else {
					echo "Method $action not found";
				}
			} else {
				echo "Controller class $controller not found";			}
		}
		else {
			echo 'No route matched';
		}
	}

	/**
	 * Convert the string with hyphens to StudlyCaps,
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToStudlyCaps($string)
	{
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	 * Convert the string with hyphens to camelCase,
	 * e.g. add-new => addNew
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToCamelCase($string)
	{
		return lcfirst($this->convertToStudlyCaps($string));
	}
}