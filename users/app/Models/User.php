<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;
use function foo\func;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'cpf', 'phone_number'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

	public function accounts()
	{
		return $this->hasMany(Account::class);
	}

    public function getUsers($search = false){

		$query = $this->where(function($query) use ($search){
			if(!empty($search)){

				$query->where('full_name', 'like', "%{$search}%");
				$query->orWhereHas('accounts', function($q) use ($search){
					$q->where('username', 'like', "%{$search}%");
				});
			}
		});

		$query = $query->orderBy('full_name', 'ASC')
			->get()
			->toArray();

		return $query;
	}

	public function storeUser($request){
		try {
			DB::beginTransaction();
			$user = new User();
			$user->cpf = $request['cpf'];
			$user->email = $request['email'];
			$user->full_name = $request['full_name'];
			$user->phone_number = $request['cpf'];
			$user->password = Hash::make($request['password']);

			$user->save();

			DB::commit();
		} catch (Exception $e){
			return ['code'=> '500', 'message' => $e->getMessage()];
		}

		return $user->toArray();
	}



	public function getUser($user_id){
		$query = $this->with('accounts')->find($user_id)->toArray ();

		$account_ids = array_map( function($account) {
				return $account['type'];
			},
			$query['accounts']
		);

		$test['accounts'] = array_combine($account_ids, array_values($query['accounts']));
		$query['accounts'] = $test['accounts'];

		if(is_null ($query)){
			return ['code'=> 'Usuário não encontrado', 'message' => 'Not found user_id'];
		}

		return $query;
	}

}
