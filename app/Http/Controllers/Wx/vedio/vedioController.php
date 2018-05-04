<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/13
 * Time: 20:46
 */
namespace App\Http\Controllers\Wx\vedio;

use App\Http\Controllers\Controller;
use App\Models\Admin\vedio\vedio;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
class vedioController extends Controller
{
    public $vedio;

    public function __construct(){
        $this->vedio = new vedio();
    }

    public function getList(Request $request){
        if($request->isMethod('get')){
            $data =  $this->vedio->getUserList();

            if(sizeof($data)>0){
                return responseToJson(0,'success',$data);
            }else{
                return responseToJson(1,'no page');
            }
        }

    }

    public function getOneVedioList(Request $request){
        if($request->isMethod('get')){
            $data = $this->vedio->getOneList($request);
            if(sizeof($data)>0)
                return responseToJson(0,'success',$data);
            else
                return responseToJson(2,'没有数据啦');
        }else{
            return responseToJson(1,'REQUEST ERROR');
        }
    }



    public function getVedio(Request $request){

        if($request->isMethod('get')){
            if(!empty($request->id)){


                return responseToJson(0,'success',$this->vedio->getUserVedio($request));
            }else{
                return responseToJson(1,'no id');
            }
        }else{
            return responseToJson(1,'Error Request');
        }
    }

}