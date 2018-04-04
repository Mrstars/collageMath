<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/4
 * Time: 14:40
 */

namespace App\Http\Controllers\Admin\book;


use App\Http\Controllers\Controller;
use App\Models\Admin\book\book;
use Illuminate\Http\Request;

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
            if (!empty($request->page)) {
                $data = $this->book->getList();
                if (sizeof($data) > 0) {
                    return responseToJson(0, 'get page', $data);
                } else {
                    return responseToJson(1, 'no page', '');
                }
            } else {
                return responseToJson(1, 'No Page');
            }
        } else {
            return responseToJson(1, 'Request Error');
        }
    }

    public function delBook(Request $request){
        if($request->isMethod('post')){

            if(!empty($request->id)){
                $data = $this->book->delBook($request);
                if($data > 0){
                    return responseToJson(0,'success');
                }else{
                    return responseToJson(1,'删除失误，未删除一条数据');
                }
            }else{
                return responseToJson(1,'no id');
            }
        }else{
            return responseToJson(1,'Request Error');
        }
    }


}