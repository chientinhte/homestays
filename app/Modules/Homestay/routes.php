<?php
$namespace = 'App\Modules\Homestay\Controllers';
Route::group(['module' => 'Homestay', 'namespace' => $namespace], function () {
    Route::get('/', 'HomestayController@index');

    Route::get('api/homestays', 'HomestayController@getHomestays');
    Route::get('api/sugguest-homestays-locations', 'HomestayController@getSuggestLocationHomestays');
}
);
