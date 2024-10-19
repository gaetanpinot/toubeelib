<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;


use toubeelib\application\actions\GetDisposPraticien;
use toubeelib\application\actions\PostSignIn;
use \toubeelib\application\actions;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    $app->post('/rdvs[/]', \toubeelib\application\actions\PostCreateRdv::class)->setName('createRdv');
    $app->get('/test[/]', \toubeelib\application\actions\test::class);

    $app->get('/rdvs/{id}[/]', \toubeelib\application\actions\GetRdvId::class)->setName('getRdv');
    $app->delete('/rdvs/{id}[/]', \toubeelib\application\actions\DeleteRdvId::class)->setName('deleteRdvId');
    // TODO get patients
    $app->get("/patients/{id}[/]", function () {
    })->setName('getPatient');
    // TODO get praticiens
    $app->get("/praticiens/{id}[/]", function () {
    })->setName('getPraticien');
    $app->patch('/rdvs/{id}[/]', \toubeelib\application\actions\PatchRdv::class)->setName('patchRdv');

    // TODO get dispos 
    $app->get('/praticiens/{id}/dispos[/]', \toubeelib\application\actions\GetDisposPraticien::class)->setName('disposPraticien');

    // TODO get dispos with date
    $app->get('/praticiens/{id}/dispos_date[/]', \toubeelib\application\actions\GetDisposPraticienDate::class)->setName('disposPraticienDate');


    //auth
    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->get('/patients/{id}/rdvs[/]', actions\GetRdvByPatient::class)->setName('rdvPatient');

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });


    return $app;
};
