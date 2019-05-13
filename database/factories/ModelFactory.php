<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$fakerBr = Faker\factory::create('pt_BR');
$factory->define(App\NotasFiscaisModel::class, function (Faker\Generator $faker) use ($fakerBr) {
    return [
        'nome' => $fakerBr->firstName.' '. $fakerBr->lastName,
        'telefone' => $fakerBr->phonenumber,
        'email' => $fakerBr->safeEmail,
        'uf' => $fakerBr->stateAbbr,
        'estado' => $fakerBr->state,
        'cidade' => $fakerBr->city,
        'endereco'=> $fakerBr->streetAddress,
    ];
});
