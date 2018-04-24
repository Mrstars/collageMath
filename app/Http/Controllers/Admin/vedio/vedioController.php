<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/13
 * Time: 20:46
 */
namespace App\Http\Controllers\Admin\vedio;

use App\Http\Controllers\Controller;
use App\Models\Admin\vedio\vedio;
use Illuminate\Http\Request;
class vedioController extends Controller
{
    public $vedio;

    public function __construct(){
        $this->vedio = new vedio();
    }

    public function getList(Request $request){
        if($request->isMethod('get')){
            $data =  $this->vedio->getList();

            if(sizeof($data)>0){
                return responseToJson(0,'success',$data);
            }else{
                return responseToJson(1,'no page');
            }
        }

    }

    public function delVedio(Request $request){

        if ($request->isMethod('post')) {

            if (!empty($request->id)) {
                $data = $this->vedio->del($request);
                if ($data > 0) {
                    return responseToJson(0, 'success');
                } else {
                    return responseToJson(1, '删除失误，未删除一条数据');
                }
            } else {
                return responseToJson(1, 'no id');
            }
        } else {
            return responseToJson(1, 'Request Error');
        }
    }

    public function upload(Request $request)
    {

        if ($request->isMethod('post')) {

            if (!empty($request->input('name')) && !empty($request->input('book_author')) && !empty($request->input('book_introduction'))) {
                if (!empty($request->file('file'))) {

                    $file = $request->file('file');
                    $type = $file->getClientMimeType();
                    if ($type == 'image/jpeg' || $type == 'image/jpg') {

                        $size = $file->getSize();
                        if ($size < 2048 * 1024) {

                            DB::beginTransaction();
                            $filename = getFilename('jpg');
                            try {

                                $this->book->addBook($request,$filename);
                                $path = $file->storeAs(
                                    'avatars', $filename, 'public'
                                );
                                DB::commit();
                                return responseToJson(0, 'success');
                            } catch (\Exception $e) {

                                DB::rollBack();
                                return responseToJson(1, '出错啦');
                            }
                        } else {
                            return responseToJson(1, '上传头像图片大小不能超过 2MB!');
                        }
                    } else {
                        return responseToJson(1, '上传头像图片只能是 JPG 格式!');
                    }
                } else {
                    return responseToJson(1, '无文件!');
                }
            } else {
                return responseToJson(1, '书籍信息不全!');
            }

        } else {
            return responseToJson(1, 'Request error');
        }

    }
}