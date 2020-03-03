<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */

	public function testCreateUser()
	{
		$data = factory(\App\Models\User::class)->make()->toArray();
		$data['password'] = Hash::make('3121ssad3322');

		$this->post('users', $data);
		$this->seeStatusCode(201);
		$this->seeJsonStructure([
			'full_name',
			'email',
			'phone_number',
			'cpf'
		]);
	}

	public function testCreateConsumer()
	{
		$data = factory(\App\Models\Account::class)->make()->toArray();

		$this->post('users/consumers', $data);
		$this->seeStatusCode(201);
		$this->seeJsonStructure([
			'username',
			'user_id'
		]);
	}
}
