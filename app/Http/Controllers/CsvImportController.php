<?php

namespace App\Http\Controllers;

use App\Imports\MobileImport;
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
//        dd($request->file('file'));
        Excel::import(new MobileImport(), $request->file('file')->store('temp'));
        return back()->with('success', "Successfully Uploaded");
    }
}
