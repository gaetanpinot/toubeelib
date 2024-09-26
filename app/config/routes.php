<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    $app->post('/rdvs[/]', \toubeelib\application\actions\PostCreateRdv::class)->setName('createRdv');
    $app->get('/test[/]', \toubeelib\application\actions\test::class);

    $app->get('/rdvs/{id}[/]', \toubeelib\application\actions\GetRdvId::class)->setName('getRdvId');
    $app->delete('/rdvs/{id}[/]', \toubeelib\application\actions\DeleteRdvId::class)->setName('deleteRdvId');


    return $app;
};