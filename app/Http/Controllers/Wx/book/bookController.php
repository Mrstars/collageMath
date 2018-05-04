<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/4
 * Time: 14:40
 */

namespace App\Http\Controllers\Wx\book;


use App\Http\Controllers\Controller;
use App\Models\Admin\book\book;
use App\Models\Admin\course\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class bookController extends Controller
{
    public $book;

    public function __construct()
    {
        $this->book = new book();
    }

    public function getList(Request $request)
    {

        if ($request->isMethod('get')) {
            if (!empty($request->input('page'))) {
                $data = $this->book->userGetList($request->descCol);
                if (sizeof($data) > 0) {
                    return responseToJson(0, 'get page', $data);
                } else {
                    return responseToJson(2, 'no page', '');
                }
            } else {
                return responseToJson(1, 'No Page');
            }
        } else {
            return responseToJson(1, 'Request Error');
        }
    }

    public function getClassList(){

        $class = new Course();

        $data = $class->getClassList();
        $size = sizeof($data);
        if($size>0){
            return responseToJson(0,'success',$data);
        }else if($size == 0){
            return responseToJson(2,'没有数据啦!');
        }else{
            return responseToJson(1,'出错啦');
        }

    }

    public function getOneList(Request $request){
        if($request->isMethod('get')){
            $data = $this->book->getOneList($request);
            if(sizeof($data)>0)
            return responseToJson(0,'success',$data);
            else
                return responseToJson(2,'没有数据啦');
        }else{
            return responseToJson(1,'REQUEST ERROR');
        }
    }

    public function getBook(Request $request){

        if($request->isMethod('get')){

            if(!empty($request->input('id'))){

                $book = $this->book->getAllBookInfo($request);
                if(sizeof($book) == 1){
                    return responseToJson(0,'success',$book);
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


}