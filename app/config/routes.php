<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use toubeelib\application\actions\GetRdvByPatient;

use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;
use toubeelib\middlewares\AuthnMiddleware;
use toubeelib\middlewares\AuthzPatient;
use toubeelib\middlewares\AuthzPraticiens;
use toubeelib\middlewares\AuthzRDV;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    $app->get('/test[/]', \toubeelib\application\actions\test::class);

    //RENDEZVOUS
    $app->post('/rdvs[/]', \toubeelib\application\actions\PostCreateRdv::class)
        ->setName('createRdv')
        ->add(AuthnMiddleware::class);
    ;

    $app->get('/rdvs/{id}[/]', \toubeelib\application\actions\GetRdvId::class)
        ->setName('getRdv')
        ->add(AuthzRDV::class)
        ->add(AuthnMiddleware::class);

    $app->delete('/rdvs/{id}[/]', \toubeelib\application\actions\DeleteRdvId::class)
        ->setName('deleteRdvId')
        ->add(AuthzRDV::class)
        ->add(AuthnMiddleware::class);

    $app->patch('/rdvs/{id}[/]', \toubeelib\application\actions\PatchRdv::class)
        ->setName('patchRdv')
        ->add(AuthzRDV::class)
        ->add(AuthnMiddleware::class);


    //PATIENTS
    $app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)
        ->setName('rdvPatient')
        ->add(AuthzPatient::class)
        ->add(AuthnMiddleware::class);

    // TODO get patients
    $app->get("/patients/{id}[/]", function () {})
        ->setName('getPatient')
        ->add(AuthzPatient::class)
        ->add(AuthnMiddleware::class);

    //PRATICIENS
    // TODO get praticiens
    $app->get("/praticiens/{id:[0-9]+}[/]", function () {
    })->setName('getPraticien');

    $app->get('/praticiens/{id}/dispos[/]', \toubeelib\application\actions\GetDisposPraticien::class)
        ->setName('disposPraticien')
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens/{id}/dispos_date[/]', \toubeelib\application\actions\GetDisposPraticienDate::class)->setName('disposPraticienDate')
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens/{id}/rdvs[/]', \toubeelib\application\actions\GetPraticienPlanning::class)
        ->setName('planningPraticien')
        ->add(AuthzPraticiens::class)
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens/search[/]', SearchPraticien::class)->setName('searchPraticiens');

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');


    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    


    return $app;
};
