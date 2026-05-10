<?php

// ??debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// session object
require_once __DIR__ . '/../core/Session.php';
Session::init();

// config setup
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// core setup
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';

// init db
$db = (new Database())->connect();
$GLOBALS['db'] = $db;

// load routes
$router = new Router();
require_once __DIR__ . '/../routes/web.php';

// get current URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// dispatch
$router->dispatch($uri, $method, $db);
