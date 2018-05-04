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
    protected $fillable = ['video_name', 'video_path', 'video_wirter', 'video_img', 'class_id','vedio_introduction'];

    public function getList()
    {
        return $this->where('is_del', 1)->orderBy('upload_time','desc')->paginate(10, ['video_name', 'id', 'video_wirter', 'pageviews']);
    }

    public function getUserList(){
        return $this->where('is_del', 1)->orderBy('upload_time','desc')->paginate(10, ['video_img','video_path','vedio_introduction','video_name', 'id', 'video_wirter', 'pageviews','upload_time']);
    }

    public function getOneList(Request $request)
    {
        return $this->where('is_del', 1)
            ->where('class_id', $request->type)
            ->orderBy($request->descCol,'desc')->
            paginate(5, ['video_img','vedio_introduction','video_name','video_path','id', 'video_wirter', 'pageviews','upload_time']);
    }

    public function getVedio(Request $request){
        return $this->where('id',$request->id)->where('is_del',1)->first(['id','video_name', 'video_path', 'video_wirter', 'video_img', 'class_id','vedio_introduction']);
    }

    public function getUserVedio(Request $request){
        return $this->where('id',$request->id)->where('is_del',1)->first([ 'video_path','video_img','vedio_introduction']);
    }
    public function del(Request $request)
    {
        if (is_array($request->id))
            return $this->whereIn('id', $request->id)->update(['is_del' => 0]);
        else
            return $this->where('id', $request->input('id'))->update(['is_del' => 0]);
    }

    public function drop(Request $request)
    {
        return $this->where('id', $request->input('id'))->delete();
    }

    public function addVedio(Request $request, $name)
    {
        $this->insert(['video_img' => $name,
                'video_name' => $request->name,
                'video_path' => $request->input('link'),
                'vedio_introduction' => $request->introduction,
                'video_wirter' => $request->vedio_author,
                'class_id' => $request->classId,
                'upload_time'=>time()]
        );

    }

    public function updateVedio(Request $request, $type = true, $filename = ''){

        if($type){
            $this->where('id',$request->id)->update([
                'video_name' => $request->name,
                'video_path' => $request->input('link'),
                'vedio_introduction' => $request->introduction,
                'video_wirter' => $request->vedio_author,
                'class_id' => $request->classId,'upload_time'=>time()]);
        }else{
            $this->where('id',$request->id)->update(['video_img' => $filename,
                'video_name' => $request->name,
                'video_path' => $request->input('link'),
                'vedio_introduction' => $request->vedio_introduction,
                'video_wirter' => $request->vedio_author,
                'class_id' => $request->classId,'upload_time'=>time()]);
        }
    }


}