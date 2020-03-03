<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;

class TransactionTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */

	public function testCreateTransaction()
	{
		$data = factory(\App\Models\Transaction::class)->make()->toArray();
		$this->post('transactions', $data);
		$this->seeStatusCode(201);
		$this->seeJsonStructure([
			'id',
			'payee_id',
			'payer_id',
			'value',
			'transaction_date'
		]);
	}

}
