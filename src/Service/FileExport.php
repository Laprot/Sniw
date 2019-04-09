<?php
namespace App\Service;

class FileExport
{
    public static function __callstatic($method, $arguments)
    {
        return forward_static_call_array(array('League\Csv\Writer', $method),$arguments);
    }
}