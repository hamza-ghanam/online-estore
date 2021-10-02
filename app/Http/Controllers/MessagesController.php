<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessagesController extends Controller
{
    static public function responder($code, $data, $msg_ = '')
    {
        switch ($code) {
            case 200:
                return response(["code" => $code, "msg" => "Success." . ($msg_ !== '' ? ' ' . $msg_ : ''), "results" => $data], "200");
                break;
            case 201:
                return response(["code" => $code, "msg" => "Created." . ($msg_ !== '' ? ' ' . $msg_ : ''), "results" => $data], "201");
                break;
            case 400 :
                return response(["code" => $code, "msg" => "Bad Request." . ($msg_ !== '' ? ' ' . $msg_ : ''), "results" => $data], "400");
                break;
            case 404:
                return response(["code" => $code, "msg" => "Not found." . ($msg_ !== '' ? ' ' . $msg_ : ''), "results" => $data], "404");
                break;
            case 500:
                return response(["code" => $code, "msg" => "Internal server error." . ($msg_ !== '' ? ' ' . $msg_ : ''), "results" => $data], "500");
                break;
        }
    }
}
