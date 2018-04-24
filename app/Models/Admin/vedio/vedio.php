<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/13
 * Time: 20:17
 */
namespace App\Models\Admin\vedio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class vedio extends Model
{
    protected $table = 'class_video';
    public $timestamps = false;
    protected $fillable = ['video_name', 'video_path', 'video_wirter', 'video_img','class_id'];

    public function getList(){
        return $this->where('is_del',1)->paginate(10,['video_name','id','video_wirter','pageviews']);
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

    public function addVedio(Request $request,$name){

        $this->insert(['video_img'=>$request->input('name'),
            'video_img'=>$request->input('vedio_author'),
            'video_path'=>$request->input('link'),
            '']);

    }


}