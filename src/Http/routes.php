<?php
use Illuminate\Support\Facades\Route;

Route::get('example', function () {
    return 'Hello World!!!';
});

Route::get('exampleview', function () {

    return \View::make('example::example', ['data'=>'1']);

});