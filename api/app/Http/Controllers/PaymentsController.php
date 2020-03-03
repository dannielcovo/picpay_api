<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class PaymentsController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	public function index()
	{
		print_r ('exit');
	}
	public function store(Request $request)
	{
		try{
			if($request['value'] >= 100){
				$return = ['code'=> 'Transação não Autorizada', 'message' => 'Transação Recusada'];
				$status = 401;
			} else {
				$return = ['code'=> 'Transação Autorizada', 'message' => 'Transação Autorizada'];
				$status = 200;
			}

			return response()->json($return, $status);
		} catch (Exception $e){
			return $e;
		}


	}


}