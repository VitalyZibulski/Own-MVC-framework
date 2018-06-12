<?php 	

	//echo 'initial project';
	//echo 'Requested URL = "' . $_SERVER['QUERY_STRING'] . '"';

/**
 * Routing
 */

require '../Core/Router.php';

$router = new Router();

//echo get_class($router);

//Add the routes
$router->add('',['controller' => 'Home', 'action' => 'index']);
$router->add('posts',['controller' => 'Posts', 'action' => 'index']);
//$router->add('posts/new',['controller' => 'Posts', 'action' => 'news']);
$router->add('{controller}/{action}');
$router->add('admin/{action}/{controller}');
$router->add('{controller}/{id:\d+}/{action}');


//Display the routing
var_dump($router->getRoutes());

//Match the requested route
$url = $_SERVER['QUERY_STRING'];

if($router->match($url)){
	var_dump($router->getParams());
} else {
	echo 'No route found for URL '.$url.'';
}
