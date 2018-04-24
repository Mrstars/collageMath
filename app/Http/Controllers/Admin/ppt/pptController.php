<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/9
 * Time: 9:08
 */

namespace App\Http\Controllers\Admin\ppt;


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
           $data =  $this->ppt->getList();

           if(sizeof($data)>0){
               return responseToJson(0,'success',$data);
           }else{
               return responseToJson(1,'no page');
           }
        }

    }

    public function delPpt(Request $request){

        if ($request->isMethod('post')) {

            if (!empty($request->id)) {
                $data = $this->ppt->del($request);
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

    public function upload(Request $request){

        if ($request->isMethod('post')) {

            if (!empty($request->input('name')) && !empty($request->input('ppt_author')) && !empty($request->input('ppt_introduction'))) {
                if (!empty($request->file('file'))) {

                    $file = $request->file('file');
                    $type = $file->getClientMimeType();
                    $judge = $type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                    $type === 'application/vnd.ms-powerpoint' ||
                    $type === 'application/msword' ||
                    $type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                    $type === 'application/pdf';
                    if ($judge) {

                        $size = $file->getSize();
                        if ($size < 2048 * 1024*50) {

                            DB::beginTransaction();
                            $filename = getFilename( $file->getClientOriginalName());
                            try {

                                $this->ppt->addPpt($request,$filename);
                                $path = $file->storeAs(
                                    'ppt', $filename, 'public'
                                );
                                DB::commit();
                                return responseToJson(0, 'success');
                            } catch (\Exception $e) {

                                DB::rollBack();
                                return responseToJson(1, '出错啦',$e);
                            }
                        } else {
                            return responseToJson(1, '上传头像图片大小不能超过 100MB!');
                        }
                    } else {
                        return responseToJson(1, '上传文件只能是 ppt、word、ptf文件 !');
                    }
                } else {
                    return responseToJson(1, '无文件!');
                }
            } else {
                return responseToJson(1, '文件信息不全!');
            }

        } else {
            return responseToJson(1, 'Request error');
        }

    }

    public function getPpt(Request $request){

        if($request->isMethod('get')){

            if(!empty($request->input('id'))){

                $ppt = $this->ppt->getPpt($request);
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

    public function updatePpt(Request $request){

        if($request->isMethod('post')){
            if (!empty($request->input('name')) && !empty($request->input('book_author')) && !empty($request->input('book_introduction'))) {
                if ($request->input('ifUpload')) {

                    if (!empty($request->file('file'))) {

                        $file = $request->file('file');
                        $type = $file->getClientMimeType();
                        $judge = $type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                            $type === 'application/vnd.ms-powerpoint' ||
                            $type === 'application/msword' ||
                            $type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                            $type === 'application/pdf';
                        if ($judge) {

                            $size = $file->getSize();
                            if ($size < 2048 * 1024*50) {

                                DB::beginTransaction();
                                $filename = getFilename( $file->getClientOriginalName());
                                try{

                                    try{
                                        $this->ppt->updatePpt($request,false,$filename);
                                        $path = $file->storeAs(
                                            'ppt', $filename, 'public'
                                        );
                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        return responseToJson(1,'出错啦，请重新刷新一下');
                                    }
                                    Storage::disk('public')->delete('/ppt/'.$request->input('oldFileName'));
                                    DB::commit();
                                    return responseToJson(0,'success');
                                }catch (\Exception $e){
                                    Storage::disk('public')->delete('/ppt/'.$filename);
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
                    $data = $this->ppt->updatePpt($request);
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