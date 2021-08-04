<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{


    public function upload(Request $request)
    {
      for($i=0; $i<count($request->upload); $i++){
        if($request->upload[$i]->isValid())
        {
            $path = $request->upload[$i]->store('uploaded');
            echo $path;
            echo '<br>';
        }
      }
      echo '<br>';
      echo 'complete';

    }
}
