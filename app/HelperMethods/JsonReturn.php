<?php

namespace App\HelperMethods;

trait JsonReturn
{
    public function dataJson($data, $messages = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 200,
            'data' => $data,
            'message' => $messages,
        ], 200);
    }

    public function errorJson($data, $code = 400, $messages = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $code,
            'error' => $data,
            'message' => $messages,
        ], $code);
    }
}
