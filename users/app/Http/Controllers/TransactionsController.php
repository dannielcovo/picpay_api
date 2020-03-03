<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller {
	/**
	 * The users model instance.
	 */
	protected $users;
	protected $accounts;
	protected $transactions;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct (User $users, Account $accounts, Transaction $transactions)
	{
		$this->users = $users;
		$this->accounts = $accounts;
		$this->transactions = $transactions;
	}

	public function store (Request $request)
	{

		$validator = Validator::make ($request->all (), [
			'payee_id' => 'required|integer',
			'payer_id' => 'required|integer',
			'value' => 'required|numeric',
		]);
		if ($validator->fails ()) {
			return response ()->json (['code' => 'Erro de validaçao nos campos', 'message' => $validator->errors ()->all ()], 422);
		}

		$validate = $this->accounts->validateAccount ($request->all ());

		if (array_key_exists ('code', $validate)) {
			return response ()->json ($validate, 404);
		}

		try{
			$endpoint = "http://transaction-api-php/payment";
			$client = new \GuzzleHttp\Client();
			$response = $client->post($endpoint, ['form_params' =>  $request->all()]);
			$content = $response->getBody ();

			if (array_key_exists ('code', $content)) {
				return response ()->json ($content, 404);
			} else {
				$return = $this->transactions->storePayment($request->all());
				return response ()->json ($return, 201);
			}

		} catch (\GuzzleHttp\Exception\RequestException $e) {

			$response = $e->getResponse();
			return response ()->json (json_decode((string) $response->getBody(), true), $response->getStatusCode());
		}
	}

	public function show($transaction_id)
	{
		$return = $this->transactions->getTransaction ($transaction_id);
		$status = (!array_key_exists ('code', $return)) ? 200 : 404;

		return response ()->json ($return, $status);
	}


}
