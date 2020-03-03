<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $this->call('UsersTableSeeder');
		factory(\App\Models\User::class)->create();
		factory(\App\Models\Account::class)->create();
		factory(\App\Models\Transaction::class)->create();

	}
}