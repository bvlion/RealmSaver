<?php
require 'vendor/autoload.php';
require 'lib/db_access.php';
require 'lib/slack.php';
require 'lib/Log.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . "/");
$dotenv->load();

$c = new \Slim\Container();

// 404
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('Page not found');
    };
};

// 500
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json')
            ->withJson(['error' => getenv('debug') ? $exception : 'Internal Server Error']);
    };
};

// Create Slim app
$app = new \Slim\App($c);

// Fetch DI Container
$container = $app->getContainer();

// set DB access
$container['pdo'] = function ($c) {
    $dbhost = getenv('db.host');
    $dbuser = getenv('db.user');
    $dbpass = getenv('db.pass');
    $dbname = getenv('db.name');

    $dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbConnection;
};

// restore API
$app->get('/{hash}/restore', function($request, $response, $args) {
    $hash = $args['hash'];
    $content = select_backup($hash, $this->pdo);
    if (empty($content)) {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('Page not found');
    }
    downloaded($hash, $this->pdo);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->write($content);
});

// save API
$app->post('/{hash}/save', function($request, $response, $args) {
    $hash = $args['hash'];
    $value = $request->getBody();
    $res['result'] = insert_backup($hash, $value, $this->pdo);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withJson($res);
});

// inquiry API
$app->post('/inquiry', function($request, $response, $args) {
    $value = $request->getBody();
    $res['result'] = send_to_slack($value, getenv('slack.url'));
    $res_val = json_encode($res);
    $app->log->info('Inquiry save:' . $res_val);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->write($res_val);
});

$app->run();