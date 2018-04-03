<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/2
 * Time: 15:36
 */

namespace App\Models;


use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    protected  $table = 'admin';
    public  $timestamps = false;

    public function login(Request $request){

        $pass = md5($request->pass);
        $this->whereRaw("account = '$request->name' and password = '$pass'")->first();
        return sizeof($this);

    }


}