<?php

include './src/RouteCollector.php';
include './src/RouteParser.php';
include './src/DataGenerator.php';

$route = new \SF\RouteCollector();

$route->addRoute('GET', '/', 'Homepage@Action');
$route->addRoute('POST', '/', 'Homepage@Action');
$route->addRoute('HEAD', '/', 'Homepage@Action');
$route->addRoute('IO', '/', 'Homepage@Action');
$route->addRoute('GET', '/{id:\d+}', 'Homepage@Action');

$route->addRoute('GET', '/users', 'get_all_users_handler');
// {id} must be a number (\d+)
$route->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
// The /{title} suffix is optional
$route->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');

$route->addRoute(['GET', 'POST'], '/test', 'test_handler');

echo '<pre/>';
print_r($route->getData());