<?php
declare(strict_types=1);

use \toubeelib\application\actions\GetDisposPraticien;

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

    $app->get('/praticiens/{id}/dispos[/]', GetDisposPraticien::class)->setName('disposPraticien');

    


    return $app;
};
