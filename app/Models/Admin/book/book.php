<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/4/3
 * Time: 20:43
 */

namespace App\Models\Admin\book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class book extends Model
{
    protected $table = 'class_book';
    public $timestamps = false;
    protected $fillable = ['book_name', 'recommended', 'book_img', 'book_introduction', 'book_author','class_id'];

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 分页，返回分页信息
     */
    public function getList()
    {
        return $this->where('is_del', 1)->paginate(10);
    }

    public function userGetList($type)
    {
        return $this->where('is_del', 1)->orderBy($type,'desc')->paginate(5, ['id', 'book_name', 'recommended', 'book_img', 'book_introduction', 'book_author', 'upload_time']);
    }

    public function getOneList(Request $request)
    {
        return $this->where('is_del', 1)
            ->where('class_id', $request->type)
            ->orderBy($request->descCol,'desc')->
            paginate(5, ['id', 'book_name', 'recommended', 'book_img', 'book_introduction', 'book_author', 'upload_time']);
    }

    public function delBook(Request $request)
    {
        if (is_array($request->id))
            return $this->whereIn('id', $request->id)->update(['is_del' => 0]);
        else
            return $this->where('id', $request->id)->update(['is_del' => 0]);
    }

    public function drop(Request $request)
    {
        if (is_array($request->id))
            return $this->whereIn('id', $request->id)->delete();
        else
            return $this->where('id', $request->id)->delete();
    }

    /**
     * @param Request $request
     * @param $name 文件名称
     */
    public function addBook(Request $request, $name)
    {

        $this->insert(['book_name' => $request->input('name'),
            'book_author' => $request->input('book_author'),
            'book_introduction' => $request->input('book_introduction'),
            'book_img' => $name, 'upload_time' => time(),'class_id'=>$request->classId]);
    }

    public function getBook(Request $request)
    {
        return $this->where('class_book.id', $request->input('id'))->where('class_book.is_del',1)
            ->first(['book_name', 'book_img', 'book_author', 'book_introduction','class_id']);
    }

    public function getAllBookInfo(Request $request){

        $data = $this->where('id', $request->input('id'))->first(['recommended']);
        $this->where('id', $request->input('id'))->update(['recommended'=>$data->recommended+1]);
        return $this->where('class_book.id', $request->input('id'))->where('class_book.is_del',1)
            ->leftJoin('class','class_book.class_id','=','class.id')
            ->first(['book_name', 'book_img', 'book_author', 'upload_time','book_introduction','recommended','class_name']);
    }


    /**
     * @param Request $request Request类的对象 用于接受发送过来的数据
     * @param bool $type 头像是否经过修改，默认值false，未经过修改
     * @param string $filename 文件名，默认为空
     * @return bool 返回修改了几行数据，根据id来修改吗，因为id已经存在的情况，所以该值应该为1，若不为1则出错
     */
    public function updateBook(Request $request, $type = true, $filename = '')
    {
        if ($type) {
            return $this->where('id', $request->input('id'))
                ->update(['book_name' => $request->input('name'),
                    'book_author' => $request->input('book_author'),
                    'book_introduction' => $request->input('book_introduction')
                    , 'upload_time' => time()]);
        } else {
            return $this->where('id', $request->input('id'))
                ->update(['book_name' => $request->input('name'),
                    'book_author' => $request->input('book_author'),
                    'book_introduction' => $request->input('book_introduction'),
                    'book_img' => $filename,
                    'upload_time' => time()]);
        }
    }

}