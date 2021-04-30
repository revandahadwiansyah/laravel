<?php

namespace App\Repositories;

use App\User;
use DB;
use Hash;

class UserRepository {

    private $key = '';
    private $conn;

    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Configure the Model
     * */
    public function model() {
        //return User::class;
    }

    public function __construct() {
        
    }

    public function find($id) {
        try {
            return User::where('id', $id)->first();
        } catch (Exception $e) {
            error_log('DB:' . $e);
            return [];
        }
    }

    public function store($input) {
        $User = [];
        try {
            //dd($input);
            $User = DB::table('users')->insertGetId($input);
        } catch (Exception $e) {
            error_log('DB:' . $e);
        }
        return $User;
    }

    public function update($id, $input) {
        $responses = [];
        try {
            $info = $this->find($id);
            if (isset($info->id)) {
                $info->fill($input);
                $responses = ['status' => true, 'data' => $info->save()];
            } else {
                $this->store($input);
                $insertedID = DB::getPdo()->lastInsertId();
                $responses = ['status' => true, 'data' => $insertedID];
            }
        } catch (exception $e) {
            $responses = ['status' => false, 'err' => $e];
        }
        return (object) $responses;
    }

    public function findByEmail($email) {
        try {
            return User::where('email', $email)->first();
        } catch (Exception $e) {
            error_log('DB:' . $e);
            return [];
        }
    }

    public function details($id) {
        $Users = [];
        try {
            $Users = User::leftJoin("roles", "roles.id", "=", "users.roles")
                    ->where('users.id', $id)
                    ->select('users.*', 'roles.name as rolesname')
                    ->first();
        } catch (Exception $e) {
            error_log('DB:' . $e);
        }
        return $Users;
    }

    public function updatePassword($id, $input) {
        try {
            $datas = $this->find($id);
            if (isset($datas->id)) {
                if (Hash::check($input['password'], $datas->password)) {
                    $new_input = array('password' => Hash::make($input['new_password']));
                    $datas->fill($new_input);
                    return $datas->save();
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } catch (Exception $e) {
            error_log('DB:' . $e);
            return 0;
        }
    }

    public function getAll() {
        try {
            return User::select('*')->get();
        } catch (Exception $e) {
            error_log('DB:' . $e);
            return [];
        }
    }

    public function getAllUsers() {
        try {
            return User::select('*')->where('roles', '=', '2')->get();
        } catch (Exception $e) {
            error_log('DB:' . $e);
            return [];
        }
    }

    public function filters($filter = '', $paginated = false) {
        $User = [];
        try {
            $User = User::where('fname', 'LIKE', '%' . $filter . '%')
                    ->orWhere('lname', 'LIKE', '%' . $filter . '%')
                    ->orWhere('email', 'LIKE', '%' . $filter . '%')
                    ->select('*')
                    ->orderBy('roles', 'ASC')
                    ->orderBy('updated_at', 'DESC');

            if ($paginated == true || $paginated === true) {
                $User = $User->paginate(env('ROWPERPAGE'));
            } else {
                $User = $User->get();
            }
        } catch (Exception $e) {
            error_log('DB:' . $e);
        }
        return $User;
    }

}
