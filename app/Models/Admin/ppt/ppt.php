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
            'ppt_path'=>$name]);
    }

    public function getPpt(Request $request)
    {

        return $this->where('id', $request->input('id'))->first(['ppt_name', 'ppt_path', 'ppt_writer', 'ppt_introduction']);
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