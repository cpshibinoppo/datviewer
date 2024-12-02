<?php

use App\Exports\DataExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    $data = DB::table('data')->get();
    return view('welcome',compact('data'));
});

Route::get('/download', function () {
    return Excel::download(new DataExport, 'data.xlsx');
});
