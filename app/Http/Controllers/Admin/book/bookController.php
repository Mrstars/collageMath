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

    public function delBook(Request $request)
    {
        if ($request->isMethod('post')) {

            if (!empty($request->id)) {
                $data = $this->book->delBook($request);
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

    public function getBook(Request $request){

        if($request->isMethod('get')){

            if(!empty($request->input('id'))){

                $book = $this->book->getBook($request);
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

    public function updatebook(Request $request){

        if($request->isMethod('post')){
            if (!empty($request->input('name')) && !empty($request->input('book_author')) && !empty($request->input('book_introduction'))) {
                if ($request->input('ifUpload')) {

                    if (!empty($request->file('file'))) {

                        $file = $request->file('file');
                        $type = $file->getClientMimeType();
                        if ($type == 'image/jpeg' || $type == 'image/jpg') {

                            $size = $file->getSize();
                            if ($size < 2048 * 1024) {

                                DB::beginTransaction();
                                $filename = getFilename('jpg');
                                try{

                                    try{
                                        $this->book->updateBook($request,false,$filename);
                                        $path = $file->storeAs(
                                            'avatars', $filename, 'public'
                                        );
                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        return responseToJson(1,'出错啦，请重新刷新一下');
                                    }
                                    Storage::disk('public')->delete('/avatars/'.$request->input('oldFileName'));
                                    DB::commit();
                                    return responseToJson(0,'success');
                                }catch (\Exception $e){
                                    Storage::disk('public')->delete('/avatars/'.$filename);
                                    DB::rollBack();
                                    return responseToJson(1,'出错啦，请重新刷新一下');
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
                }else{
                    $data = $this->book->updateBook($request);
                    if($data == 1){
                        return responseToJson(0,'success');
                    }else{
                        return responseToJson(1,'出错了，请刷新一下啊');
                    }
                }
            }else{
                return responseToJson(1, '书籍信息不全!');
            }
        }else{
            return responseToJson(1,'Request Error');
        }
    }


}