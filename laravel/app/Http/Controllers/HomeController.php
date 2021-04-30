<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Response;
use Auth;
use Session;
use Redirect;
use App;

class HomeController extends Controller {

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
    public function index() {
        return view('home');
    }

    public function profileDetails(Request $request) {
        $this->authChecker();

        $uid = Auth::id();

        $profileDetails = $this->UserRepository->find($uid);

        return view('profile')
                        ->with('profileDetails', $profileDetails);
    }

    public function editProfile(Request $request) {
        $this->authChecker();

        $id = (isset($request->id) && $request->id != '') ? $request->id : '';
        $password = (isset($request->password) && $request->password != '') ? $request->password : '';
        $new_password = (isset($request->new_pasword) && $request->new_pasword != '') ? $request->new_pasword : '';

        if ($password == '' || $password == null || $new_password == '' || $new_password == null) {
            return Redirect::back()->withErrors(['msg', 'InvalidInputData']);
        }

        if ($password == $new_password) {
            return Redirect::back()->withErrors(['msg', 'InvalidSamePassword']);
        }

        $input = array(
            'password' => $password,
            'new_password' => $new_password
        );

        $updated = $this->UserRepository->updatePassword($id, $input);

        if ($updated == 0 || $updated === 0) {
            return Redirect::back()->withErrors(['msg', 'InvalidPreviousPassword!']);
        }
        return Redirect::back();
    }

}
