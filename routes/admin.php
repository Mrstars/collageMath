<?php

Route::group(['prefix' => 'admin'], function () {
    Route::get('/menu/get', 'Admin\MenuController@get_menu');
    Route::get('/classlist','Wx\book\bookController@getClassList');
    Route::group(['prefix' => 'weixin'], function () {
        Route::post("/config/set", 'Admin\WeixinController@set_config');
        Route::post("/config/get", 'Admin\WeixinController@get_config');

        Route::post("/template/set", 'Admin\WeixinController@set_wx_template');
        Route::post("/template/get", 'Admin\WeixinController@get_wx_template');

        Route::post("/reply/get", 'Admin\WeixinController@get_reply');
        Route::post("/reply/set", 'Admin\WeixinController@set_reply');
        Route::post("/follow/set", 'Admin\WeixinController@set_follow');
        Route::post("/news/set", 'Admin\WeixinController@set_news');

        Route::post("/reply/create", 'Admin\WeixinController@create_reply');
        Route::post("/reply/delete/{id}", 'Admin\WeixinController@delete_reply');

        Route::post("/industry", 'Admin\WeixinController@set_industry');
        Route::post("/update", 'Admin\WeixinController@add_template');
        Route::post("/auto_update", 'Admin\WeixinController@get_private_templates');
        Route::post("/menu/get", 'Admin\WeixinController@get_menu');
        Route::post("/menu/set", 'Admin\WeixinController@set_menu');

        Route::get('/group/get', 'Admin\WeixinController@get_wx_group');
        Route::get('/group/set', 'Admin\WeixinController@set_wx_group');
        Route::get('/group/move', 'Admin\WeixinController@move_wx_group');
        Route::get('/group/del', 'Admin\WeixinController@del_wx_group');
        Route::get('/group/menu/a/set', 'Admin\WeixinController@set_wx_group_menu_a');
        Route::get('/group/menu/b/set', 'Admin\WeixinController@set_wx_group_menu_b');
        Route::get('/menu/get', 'Admin\WeixinController@get_wx_menu');
        Route::get('/user/menu/get', 'Admin\WeixinController@get_wx_user_mennu');
    });

    Route::group(['prefix' => 'book'],function (){
        Route::get('getList','Admin\book\bookController@getList');
        Route::post('delete','Admin\book\bookController@delBook');
        Route::post('upload','Admin\book\bookController@upload');
        Route::get('getBook','Admin\book\bookController@getBook');
        Route::post('update','Admin\book\bookController@updatebook');
    });

    Route::group(['prefix'=>'ppt','namespace'=>'Admin\ppt'],function (){
        Route::get('getList','pptController@getList');
        Route::post('delete','pptController@delPpt');
        Route::post('upload','pptController@upload');
        Route::get('getPpt','pptController@getPpt');
    });

    Route::group(['prefix'=>'vedio','namespace'=>'Admin\vedio'],function (){
        Route::get('getList','vedioController@getList');
        Route::post('delete','vedioController@delVedio');
        Route::post('upload','vedioController@upload');
        Route::post('update','vedioController@updateVedio');
        Route::get('getVedio','vedioController@getVedio');
    });


});
/*Route::group(['prefix' => 'weixin'], function () {
    Route::post("/config/set", 'Admin\WeixinController@set_config');
    Route::post("/config/get", 'Admin\WeixinController@get_config');

    Route::post("/template/set", 'Admin\WeixinController@set_wx_template');
    Route::post("/template/get", 'Admin\WeixinController@get_wx_template');

    Route::post("/reply/get", 'Admin\WeixinController@get_reply');
    Route::post("/reply/set", 'Admin\WeixinController@set_reply');
    Route::post("/follow/set", 'Admin\WeixinController@set_follow');
    Route::post("/news/set", 'Admin\WeixinController@set_news');

    Route::post("/reply/create", 'Admin\WeixinController@create_reply');
    Route::post("/reply/delete/{id}", 'Admin\WeixinController@delete_reply');

    Route::post("/industry", 'Admin\WeixinController@set_industry');
    Route::post("/update", 'Admin\WeixinController@add_template');
    Route::post("/auto_update", 'Admin\WeixinController@get_private_templates');
    Route::post("/menu/get", 'Admin\WeixinController@get_menu');
    Route::post("/menu/set", 'Admin\WeixinController@set_menu');

    Route::get('/group/get', 'Admin\WeixinController@get_wx_group');
    Route::get('/group/set', 'Admin\WeixinController@set_wx_group');
    Route::get('/group/move', 'Admin\WeixinController@move_wx_group');
    Route::get('/group/del', 'Admin\WeixinController@del_wx_group');
    Route::get('/group/menu/a/set', 'Admin\WeixinController@set_wx_group_menu_a');
    Route::get('/group/menu/b/set', 'Admin\WeixinController@set_wx_group_menu_b');
    Route::get('/menu/get', 'Admin\WeixinController@get_wx_menu');
    Route::get('/user/menu/get', 'Admin\WeixinController@get_wx_user_mennu');
});*/