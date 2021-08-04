<?php

namespace App\Helpers;

class ResponseHelper implements Helper
{

    public function __construct()
    {

    }

    /**
     * This method formats error response
     *
     * @param string $header
     * @param $error [optional]
     *
     * @return jsonRespone;
     */
    public function withError(string $header, $error = null)
    {
        $status = 'error';
        $msg = 'an error occured';
        $data = null;
        return $this->respond($status,$msg, $header, $data, $error);
    }

    /**
     * This method formats success response
     *
     * @param string $header
     * @param $success [optional]
     *
     * @return jsonRespone;
     */
    public function withSuccess(string $header, $data = null)
    {
        $status = 'success';
        $msg = 'operation successful';
        $error = null;
        return $this->respond($status,$msg, $header, $data, $error);
    }


    /**
     * This method helps format json response
     *
     * @param string $status
     * @param string $msg
     * @param int $header
     * @param mixed $data [optional]
     *
     *
     * @return jsonresponse;
     */
    public static function respond(string $status, string $msg, string $header, $data = null, $error = null)
    {
        return response()->json(['status'=>$status, 'message'=> $msg, 'error'=>$error, 'data'=>$data], $header);
    }

}

?>
