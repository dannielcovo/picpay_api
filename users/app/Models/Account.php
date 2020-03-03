<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use DB;
use Exception;

class Account extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'username', 'type', 'cnpj', 'fantasy_name', 'social_name'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
	];

	public function user()
	{
		return $this->belongsTo (User::class);
	}

	public function storeAccount($request, $type){

		$registry = $this->where('type', $type)
			->where('user_id', $request['user_id'])
			->first();

		if(!empty($registry)){
			return ['code'=> 'Erro de validação nos campos', 'message' => 'Já existe um usuário vinculado a esse tipo de conta'];
		}
		try {

			DB::beginTransaction();
			$account = new Account();

			if($type == 'seller'){
				$account->cnpj = $request['cnpj'];
				$account->fantasy_name = $request['fantasy_name'];
				$account->social_name = $request['social_name'];
			}

			$account->user_id = $request['user_id'];
			$account->type = $type;
			$account->username = $request['username'];

			$account->save();

			DB::commit();
		} catch (Exception $e){
			return ['code'=> 'Erro no servidor', 'message' => $e->getMessage()];
		}

		return $account->toArray();
	}

	public function validateAccount($request){
		$queryPayee = $this->where('id', $request['payee_id'])
			->first();

		$queryPayer = $this->where('id', $request['payer_id'])
			->first();

		if(empty($queryPayee)){
			return ['code'=> 'User not found', 'message' => 'Invalid payee_id'];
		}
		if(empty($queryPayer)){
			return ['code'=> 'User not found', 'message' => 'Invalid payer_id'];
		}

		return ['validated' => true];

	}

}
