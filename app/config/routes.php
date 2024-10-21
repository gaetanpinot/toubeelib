<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use toubeelib\application\actions\GetRdvByPatient;

use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;

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
    $app->get("/praticiens/{id:[0-9]+}[/]", function () {
    })->setName('getPraticien');
    $app->patch('/rdvs/{id}[/]', \toubeelib\application\actions\PatchRdv::class)->setName('patchRdv');

    $app->get('/praticiens/{id}/dispos[/]', \toubeelib\application\actions\GetDisposPraticien::class)->setName('disposPraticien');

    $app->get('/praticiens/{id}/dispos_date[/]', \toubeelib\application\actions\GetDisposPraticienDate::class)->setName('disposPraticienDate');


    $app->get('/praticiens/{id}/planning[/]', \toubeelib\application\actions\GetPraticienPlanning::class)->setName('planningPraticien');


    $app->get( '/praticiens/search[/]', SearchPraticien::class)->setName('searchPraticiens');

    //auth
    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)->setName('rdvPatient');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    


    return $app;
};
