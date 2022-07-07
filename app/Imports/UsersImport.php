<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36",
                    "ignore_errors" => true,
                    "timeout" => (float)30.0,
                ),
                "ssl" => array(
                    "allow_self_signed" => true,
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            )
        );

        // required file size
        $imgArr = [
            array("width" => 40, "height" => 56, "name" => "small"),
            array("width" => 100, "height" => 170, "name" => "medium"),
            array("width" => 100, "height" => 80, "name" => "medium2"),
            array("width" => 260, "height" => 336, "name" => "large"),
            array("width" => 666, "height" => 666, "name" => "extralarge")
        ];

        $image_url = $row[4];
        $id = $row[0]; // $row[0] = mobile record id

        // check the images file is exists
        $image_base_path = public_path("images");
        if (!File::exists($image_base_path)) {
            File::makeDirectory($image_base_path, $mode = 0777, true, true);
        }

        // create file with record id
        $image_save_path = public_path("images/" . $id);
        if (!File::exists($image_save_path)) {
            File::makeDirectory($image_save_path, $mode = 0777, true, true);
        }

        $filename = basename($image_url);

        // save file to public directory
        $image = file_get_contents($image_url, false, $context);
        file_put_contents(($image_save_path . "/" . "original.png"), $image);

        // make 4 images
        $new_image_path = $image_save_path . "/" . "original.png";
        foreach ($imgArr as $item) {
            $img = Image::make($new_image_path);
            $img->resize($item['width'], $item['height']);
            $img->save($image_save_path . "/" . $item['name'] . '.png');
        }

        // save record to database
        return new User([
            'id' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => Hash::make($row[3])
        ]);
    }

}
