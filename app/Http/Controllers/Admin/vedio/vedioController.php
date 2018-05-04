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

    public function getVedio(Request $request){

        if($request->isMethod('get')){
            if(!empty($request->id)){

                return responseToJson(0,'success',$this->vedio->getVedio($request));
            }else{
                return responseToJson(1,'no id');
            }
        }else{
            return responseToJson(1,'Error Request');
        }
    }

    public function upload(Request $request)
    {

        if ($request->isMethod('post')) {

            if (!empty($request->input('name')) && !empty($request->input('vedio_author')) && !empty($request->input('vedio_introduction')) && !empty($request->link)) {
                if (!empty($request->file('file'))) {

                    $file = $request->file('file');
                    $type = $file->getClientMimeType();
                    if ($type == 'image/jpeg' || $type == 'image/jpg') {

                        $size = $file->getSize();
                        if ($size < 2048 * 1024) {

                            DB::beginTransaction();
                            $filename = getFilename('jpg');
                            try {

                                $this->vedio->addVedio($request,$filename);
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
    public function updateVedio(Request $request){

        if($request->isMethod('post')){
            if (!empty($request->input('name')) && !empty($request->input('vedio_author')) && !empty($request->input('vedio_introduction')) && !empty($request->link)) {
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
                                        $this->vedio->updateVedio($request,false,$filename);
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
                    $data = $this->vedio->updateVedio($request);
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