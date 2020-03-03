<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use DB;

class Transaction extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'payee_id', 'payer_id', 'transaction_date', 'value'
	];

	public $timestamps = false;

	public function storePayment($request) {
		try {
//			$date = new \DateTime();
			$timestamp = date('Y-m-d H:i:s');
			DB::beginTransaction();
			$transaction = new Transaction();

			$transaction->payee_id = $request['payee_id'];
			$transaction->payer_id = $request['payer_id'];
			$transaction->value = $request['value'];
			$transaction->transaction_date = $timestamp;
			$transaction->save();

			DB::commit();
		} catch (Exception $e){
			return ['code'=> 'Erro no servidor', 'message' => $e->getMessage()];
		}

		return $transaction->toArray();
	}

	public function getTransaction($transaction_id){
		$query = $this->find($transaction_id);

		if(empty($query)){
			return ['code'=> 'Transação não encontrada', 'message' => 'Invalid transaction_id'];
		}

		return $query->toArray();
	}
}