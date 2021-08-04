<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait AjaxFile
{

   public  $supportedFiles =  ['application/pdf'=>'.pdf', 'image/png'=>'.png','image/jpeg'=>'.jpg',
   'image/jpg'=>'.jpg','application/x-zip-compressed'=>'.zip'
   ];

    public function saveFile( $file, $folder)
    {
        $unique = Str::random(10);
        $type = $file['type'];
        $decoded_file = $this->base64ToFile($file['data']);
        $file_name = '/'.$folder.'/'. $unique.$this->supportedFiles[$type];
        Storage::disk('local')->put('/public/'.$file_name, $decoded_file);
        return $file_name;
    }

    public function base64ToFile($data)
    {
        $file = explode(',',$data)[1];
        $file = str_replace(' ','+',$file);
        $decoded_file = \base64_decode($file);
        return $decoded_file;
    }

    public function deleteFile($fullPath)
    {
        try{
            Storage::disk('local')->delete('/public/'.$fullPath);
        }catch(FileNotFoundException $e){

        }
        return true;

    }

}


?>
