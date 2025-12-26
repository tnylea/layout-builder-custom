<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::get('layout/{layout}', function ($layout) {
    abort_unless(in_array($layout, ['full-width', 'sidebar']), 404);
    $view = "layouts.$layout";
    $html = view($view)->render();
    return response('<pre>'.e($html).'</pre>', 200)->header('Content-Type', 'text/html; charset=UTF-8');
});