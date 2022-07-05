<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CsvImportController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        Excel::import(new UsersImport(), $request->file('file')->store('temp'));
        return back();
    }
}
