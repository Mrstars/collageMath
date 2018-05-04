<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/9
 * Time: 9:08
 */

namespace App\Http\Controllers\wx\ppt;


use App\Http\Controllers\Controller;
use App\Models\Admin\ppt\ppt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
class pptController extends Controller
{
    public $ppt;

    public function __construct(){
        $this->ppt = new ppt();
    }

    public function getList(Request $request){
        if($request->isMethod('get')){
           $data =  $this->ppt->userGetList($request->descCol);

           if(sizeof($data)>0){
               return responseToJson(0,'success',$data);
           }else{
               return responseToJson(1,'no page');
           }
        }

    }

    public function getOnePptList(Request $request){
        if($request->isMethod('get')){
            $data = $this->ppt->getOneList($request);
            if(sizeof($data)>0)
                return responseToJson(0,'success',$data);
            else
                return responseToJson(2,'没有数据啦');
        }else{
            return responseToJson(1,'REQUEST ERROR');
        }
    }


    public function getPpt(Request $request){

        if($request->isMethod('get')){

            if(!empty($request->input('id'))){

                $ppt = $this->ppt->getUserPpt($request);
                if(sizeof($ppt) == 1){
                    return responseToJson(0,'success',$ppt);
                }else{
                    return responseToJson(1,'查询出错啦，请刷新下重试');
                }
            }else{
                return responseToJson(1,'No id');
            }
        }else{
            return responseToJson(1,'Request Error');
        }
    }

    public function download(Request $request){
//        dd($request->path);
        $path = $request->path;
        $path = "app\public\ppt\\$path";
//        dd($path);
        return  response()->download(storage_path($path));
    }


}