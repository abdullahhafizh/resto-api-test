<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private function defaultJson($data, $code)
    {
        if (!is_string($data)) $data = [ 'data' => $data];
        if (is_string($data)) $data = [ 'message' => $data ];

        $data = array_merge(['code' => $code], $data);

        return response()->json($data, $code, [], JSON_UNESCAPED_SLASHES);
    }

    public function jsonSuccess($data, $code = 200)
    {
        if (substr($code, 0, 1) != 2) throw new \Exception('Invalid http success code provided');
        
        return $this->defaultJson($data, $code);
    }
    
    public function jsonValidate($data)
    {
        return $this->defaultJson($data, 422);
    }
    
    public function jsonNotFound($message)
    {
        return $this->defaultJson($message, 404);
    }
    
    public function jsonGeneralClientError($message = 'General Error', $code = 400)
    {
        if (substr($code, 0, 1) != 4) throw new \Exception('Invalid http client error code provided');
        
        return $this->defaultJson($message, $code);
    }

    public function jsonGeneralError($message = 'General Error', $code = 500)
    {
        if (substr($code, 0, 1) != 5) throw new \Exception('Invalid http server error code provided');
        
        return $this->defaultJson($message, $code);
    }
}
