<?php

use DI\ContainerBuilder;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Factory\AppFactory;



$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/constantes.php');
$builder->addDefinitions(__DIR__ . '/settings.php' );
$builder->addDefinitions(__DIR__ . '/dependencies.php');
$builder->addDefinitions(__DIR__ . '/actions.php');

$c=$builder->build();
$app = AppFactory::createFromContainer($c);


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);
//    ->getDefaultErrorHandler()
//    ->forceContentType('application/json')

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();


return $app;
