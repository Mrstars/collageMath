<?php
//测试专用
Route::group(['prefix'=>'wx'],function (){
    Route::get('booklist','Wx\book\bookController@getList');
    Route::get('classlist','Wx\book\bookController@getClassList');
    Route::get('getOneList','Wx\book\bookController@getOneList');
    Route::get('getBook','Wx\book\bookController@getBook');
    Route::get('pptList','Wx\ppt\pptController@getList');
    Route::get('getOnePptList','Wx\ppt\pptController@getOnePptList');
    Route::get('getPpt','Wx\ppt\pptController@getPpt');
    Route::get('pptDown','Wx\ppt\pptController@download');
    Route::get('vedioList','Wx\vedio\vedioController@getList');
    Route::get('getOneVedioList','Wx\vedio\vedioController@getOneVedioList');
    Route::get('getVedio','Wx\vedio\vedioController@getVedio');

});