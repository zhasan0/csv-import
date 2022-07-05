<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Storage;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $path = $row[4];
        $filename = basename($path);

        $request = new \Illuminate\Http\Request();
        $request->file('')->move('images', $filename);
        dd($row[4]);
        return new User([
            'id'     => $row[0],
            'name'     => $row[1],
            'email'    => $row[2],
            'password' => Hash::make($row[3])
        ]);
    }

}
