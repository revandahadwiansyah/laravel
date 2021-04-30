<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Countries;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Auth;
use Redirect;
use Response;

class UsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $UserRepository) {
        $this->middleware('auth');
        $this->UserRepository = $UserRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        $this->authChecker();
        $filters = '';
        try {
            $countries = Countries::all();
            if (Auth::user()->roles == 1) {
                $users = $this->UserRepository->getAll(true);
            } else {
                $users = $this->UserRepository->getAllUsers();
            }

            if ($request->ajax()) {
                $return = array('status' => 'false', 'msg' => 'RecordNotFound', 'countries' => $countries);
                if ($request->ajaxtype == 'details') {
                    $finds = $this->UserRepository->details($request->user_id);
                    if (isset($finds->id) != 0) {
                        $finds->files = 'http://placehold.it/380x500';
                        return response()->json(array('status' => 'true', 'details' => $finds, 'msg' => 'details'), 200);
                    }
                    return response()->json($return, 200);
                }
                return response()->json($return, 200);
            } else if (isset($request->filters)) {
                if ($request->filters != '' && $request->filters != null) {
                    $filters = $request->filters;
                    $users = $this->UserRepository->filters($request->filters, true);
                }
                $users->appends($request->only('filters'));
            }

            if (isset($users[0])) {
                foreach ($users as $key => $user) {
                    if ($user->files != '' && $user->files != null) {
                        //$users[$key]->profileImageKey = $this->awsGetObject($user->profileImageKey, true);
                    }
                }
            }
            return view('users', compact('users'))
                            ->with('countries', $countries)
                            ->with('filters', $filters)
                            ->with('env', env('APP_ENV'));
        } catch (Exception $ex) {
            return view('home');
        }
    }

    public function add(Request $request) {
        $this->authChecker();
        $inputData = [];
        $datas = $request->all();
        try {
            foreach ($datas as $x => $data) {
                if ($data['name'] != '_token' && $data['name'] != 'id') {
                    $inputData[$data['name']] = ($data['name'] == 'password')? Hash::make($data['value']) :  $data['value'];
                }
            }
            $inputData['status'] = '1';
            $insertData = $this->UserRepository->store($inputData);
            if (!$insertData) {
                return response()->json(array('status' => 'false', 'data' => [], 'msg' => 'DuplicateNickname/Phone'), 200);
            }

            return response()->json(array('status' => 'true', 'details' => $insertData, 'msg' => 'successfully'), 200);
        } catch (Exception $e) {
            return response()->json(array('status' => 'false', 'data' => [], 'msg' => 'DuplicateNickname/Phone'), 200);
        }
    }

    public function edit(Request $request) {
        $this->authChecker();
        $responsed = [];
        $datas = $request->all();
        
        try {
            $inputData = [];
            $id = 0;
            foreach ($datas as $x => $data) {
                if ($data['name'] == 'id')
                    $id = $data['value'];

                if ($data['name'] != '_token' && $data['name'] != 'id') {
                    $inputData[$data['name']] = $data['value'];
                }
            }
            $updatedStatus = $this->UserRepository->update($id, $inputData);
            if (!$updatedStatus) {
                $responsed = array('status' => 'false', 'msg' => 'unableToUpdate!');
            }

            return response()->json($responsed, 200);
        } catch (exception $e) {
            echo 'err:' . $e;
        }
    }

    public function remove(Request $request) {
        $this->authChecker();
        $responsed = [];

        try {
            $id = (isset($request->id) && $request->id != '') ? $request->id : '';
            $inputData = array(
                "status" => 0
            );
            $updatedStatus = $this->UserRepository->update($id, $inputData);
            if (!$updatedStatus) {
                $responsed = array('status' => 'false', 'msg' => 'unableToUpdate!');
            }

            return response()->json($responsed, 200);
        } catch (exception $e) {
            echo 'err:' . $e;
        }
    }

}
