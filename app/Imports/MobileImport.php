<?php

namespace App\Imports;

use App\Models\Mobile;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Concerns\ToModel;

class MobileImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row[0] != null) {
//            dd($row[0]);
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


            $image_url_arr = $row[7];
            $raw_url_1 = str_replace("[", "", $image_url_arr);
            $raw_url = str_replace("]", "", $raw_url_1);
            $urls = explode(',', $raw_url);
            $url = $urls[0];
            $url = str_replace("'", "", $url);
            $image_url = $url;
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
//            dd($image_url);
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
//            dd($row[4]);
            // save record to database
            return new Mobile([
                'id' => $row[0],
                'brand_option_id' => $row[2],
                'model' => $row[4],
//                'announced' => $row[19],
                'weight' => $row[23],
                'chipset' => $row[36],
                'gpu' => $row[38],
//                'release_date' => $row[99],
                'safe_name' => "Test",
//                'battery_capacity_mah' => $row[109],
            ]);

            
        }
    }
}
