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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
	return [
		'full_name' => $faker->name,
		'email' => $faker->email,
		'phone_number' => $faker->phoneNumber,
		'password' => $faker->password,
		'cpf' => $faker->numerify ('##########'),
	];
});

$factory->define(App\Models\Account::class, function (Faker\Generator $faker) {
	//	$user = \App\Models\User::all ()->
	$ids = \App\Models\User::orderBy('id','asc')->pluck('id')->toArray();

	return [
		'username' => $faker->userName,
		'user_id' => $faker->numberBetween ($ids[0], end($ids))
	];
});

$factory->define(App\Models\Transaction::class, function (Faker\Generator $faker) {
	$ids = \App\Models\Account::orderBy('id','asc')->pluck('id')->toArray();

	return [
		'payee_id' => $faker->numberBetween ($ids[0], end($ids)),
		'payer_id' => $faker->numberBetween ($ids[0], end($ids)),
		'value' => $faker->numberBetween (0, 100),
		'transaction_date' => $faker->date ('Y-m-d H:i:s')
	];

});