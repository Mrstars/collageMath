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
    protected $fillable = ['book_name', 'recommended', 'book_img', 'book_introduction', 'book_author'];

    public function getList()
    {

        return $this->where('is_del', 1)->paginate(10);
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

}