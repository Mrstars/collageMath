<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/9
 * Time: 9:00
 */

namespace App\Models\Admin\ppt;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ppt extends Model
{
    protected $table = 'class_ppt';
    public $timestamps = false;
    protected $fillable = ['ppt_name', 'ppt_path', 'ppt_writer', 'upload_time', 'download','class_id','ppt_introduction'];

    public function getList(){
        return $this->where('is_del',1)->paginate(10,['ppt_name','id','ppt_writer','download']);
    }

    public function userGetList($type){
        return $this->where('is_del', 1)->orderBy($type,'desc')
            ->paginate(5,['id','ppt_name','ppt_writer','upload_time','download','ppt_introduction','ppt_path']);
    }

    public function getOneList(Request $request){
        return $this->where('is_del', 1)->where('class_id',$request->type)
            ->orderBy($request->descCol,'desc')
            ->paginate(5,['id','ppt_name','ppt_writer','upload_time','download','ppt_introduction','ppt_path']);
    }

    public function del(Request $request){
        if (is_array($request->id))
            return $this->whereIn('id', $request->id)->update(['is_del' => 0]);
        else
            return $this->where('id',$request->input('id'))->update(['is_del'=>0]);
    }

    public function drop(Request $request){
        return $this->where('id',$request->input('id'))->delete();
    }

    public function addPpt(Request $request,$name){
        $this->insert(['ppt_name'=>$request->input('name'),
            'ppt_writer'=>$request->input('ppt_author'),
            'ppt_introduction'=>$request->input('ppt_introduction'),
            'upload_time'=>time(),
            'ppt_path'=>$name,'class_id'=>$request->classId]);
    }

    public function getPpt(Request $request)
    {

        return $this->where('class_ppt.is_del',1)->where('class_ppt.id', $request->input('id'))
            ->first(['ppt_name','ppt_writer','class_id','ppt_introduction']);
    }

    public function getUserPpt(Request $request){
        return $this->where('class_ppt.id', $request->input('id'))->where('class_ppt.is_del',1)
            ->leftJoin('class','class_ppt.class_id','=','class.id')
            ->first(['ppt_name', 'ppt_writer', 'class_id', 'upload_time','ppt_introduction','download','class_name','ppt_path']);
    }

    public function updateBook(Request $request, $type = true,$filename = '')
    {
        if ($type) {
            return $this->where('id', $request->input('id'))
                ->update(['ppt_name' => $request->input('name'),
                    'ppt_writer' => $request->input('ppt_author'),
                    'ppt_introduction' => $request->input('ppt_introduction')]);
        }else{
            return $this->where('id', $request->input('id'))
                ->update(['ppt_name' => $request->input('name'),
                    'ppt_writer' => $request->input('ppt_author'),
                    'ppt_introduction' => $request->input('ppt_introduction'),
                    'ppt_path'=>$filename]);
        }
    }


}