<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {
	/**
	 * The users model instance.
	 */
	protected $users;
	protected $accounts;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct (User $users, Account $accounts)
	{
		$this->users = $users;
		$this->accounts = $accounts;
	}

	public function index (Request $request)
	{
		if( $request->q == '/users' || $request->q == '/users/'){
			$return = (['code'=> 'Erro interno do servidor', 'message' => 'Not found']);
			$status = 500;
		} else {
			$return = $this->users->getUsers ($request->q);
			$status = (!array_key_exists ('code', $return)) ? 200 : 500;
		}

		return response ()->json ($return, $status);
	}

	public function show($user_id)
	{
		$return = $this->users->getUser($user_id);
		$status = (!array_key_exists ('code', $return)) ? 200 : 404;

		return response ()->json ($return, $status);
	}

	public function store (Request $request)
	{
		$validator = Validator::make ($request->all (), ['full_name' => 'required|string', 'cpf' => 'required|string|unique:users', 'email' => 'required|email|unique:users', 'password' => 'required|string', 'phone_number' => 'required|string|min:2']);
		if ($validator->fails ()) {
			return response ()->json (['code' => 'Erro de validaçao nos campos', 'message' => $validator->errors ()->all ()], 422);
		}
		$return = $this->users->storeUser ($request->all ());
		$status = (!array_key_exists ('code', $return)) ? 201 : 500;

		return response ()->json ($return, $status);
	}

	public function storeSellers (Request $request)
	{
		$validator = Validator::make ($request->all (), ['username' => 'required|string|unique:accounts', 'user_id' => 'required|integer', 'cnpj' => 'required|string|', 'fantasy_name' => 'required|string', 'social_name' => 'required|string',]);
		if ($validator->fails ()) {
			return response ()->json (['code' => 'Erro de validaçao nos campos', 'message' => $validator->errors ()->all ()], 422);
		}

		$returnUser = $this->users->getUser ($request->get ('user_id'));
		if (array_key_exists ('code', $returnUser)) {
			return response ()->json ($returnUser, 404);
		}

		$return = $this->accounts->storeAccount ($request->all (), 'seller');
		$status = (!array_key_exists ('code', $return)) ? 201 : 422;

		return response ()->json ($return, $status);
	}

	public function storeConsumers (Request $request)
	{

		$validator = Validator::make ($request->all (), ['username' => 'required|string|unique:accounts', 'user_id' => 'required|integer']);

		if ($validator->fails ()) {

			return response ()->json (['code' => 'Erro de validaçao nos campos', 'message' => $validator->errors ()->all ()], 422);
		}
		$returnUser = $this->users->getUser ($request->get ('user_id'));
		if (array_key_exists ('code', $returnUser)) {
			return response ()->json ($returnUser, 404);
		}
		$return = $this->accounts->storeAccount ($request->all (), 'consumer');
		$status = (!array_key_exists ('code', $return)) ? 201 : 422;

		return response ()->json ($return, $status);
	}

}
	