<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/5/1
 * Time: 17:16
 */

namespace App\Models\Admin\course;


use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'class';
    public $timestamps = false;
    protected $fillable = ['class_name', 'introduction'];


    public  function getClassList(){

        return $this->where('is_del',0)->get(['id','class_name']);
    }

}