<?php

namespace App\Http\Controllers;

use App\Models\Admin\Super;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Response;
use Log;
use Redirect;
use App\Models\admin;

class LoginController extends Controller
{

    public $user;

    public function __construct()
    {

        $this->user = new Admin();
    }

    public function index()
    {
        return view("login");
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
//            $name = $request->name;//code or mobile
//            $pwd = $request->pass;
//            $user = Super::get_account($name);
            $user = $this->user->login($request);
            if ($user) {
                //dd(encrypt_password($pwd, $user->salt));


                // $roleIds = explode(',', $user->role_id);
                //解决ajax轮询对session的影响,导致登录后session丢失,此处重新生成session
                // $session = $request->session();
                // $session->invalidate();//重新生成session
                $this->login_success($request, $request->name);

                Log::info(['LOGIN SUCCESS' => json_encode($user)]);

                return Response::json(['status' => 0, 'msg' => '登陆成功']);
            } else {
                Log::error(['LOGIN ERROR' => json_encode($user)]);
                return Response::json(['status' => 1, 'msg' => '用户名或密码错误,请重新输入']);
            }

        } else {
            return view('login');
        }
    }

    function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->all();
        return Redirect::to('/');
    }


    //登录成功之后调用
    private function login_success($request, $user)
    {
        $session = $request->session();
        $session->put('user', $user);
    }
}