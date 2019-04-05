<?php

namespace App\Exceptions;

use Exception;

class ControllerException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render(Exception $error)
    {
        return response()->json([
            'status' => 'error',
            'msg' => $this->getMessage()
        ]);
    }
}
