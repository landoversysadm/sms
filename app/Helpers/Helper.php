<?php

namespace App\Helpers;

interface Helper
{
 static function  respond(string $status, string $msg, string $header, $data = null, $error = null);
}

?>
