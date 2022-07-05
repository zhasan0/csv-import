<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvImportController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $csv_path = request()->file('contact_file')->getRealPath();
        dd($csv_path);
    }
}
