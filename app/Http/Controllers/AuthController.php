<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // Gọi API của tool.vn để xác thực và lấy thông tin người dùng
    $response = Http::post('http://localhost:8000/api/login', [
        'email' => $request->input('email'),
        'password' => $request->input('password'),
    ]);

    if ($response->successful()) {
        $responseData = $response->json();

        // Lấy token từ phản hồi
        $token = $responseData['token'];

        // Gọi API '/api/user' để lấy thông tin người dùng
        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('http://localhost:8000/api/user');

        if ($userResponse->successful()) {
            $user = $userResponse->json();

            // Lưu thông tin người dùng vào cơ sở dữ liệu hoặc thực hiện các xử lý khác

            return response()->json(['user' => $user], 200);
        }

        return response()->json(['message' => 'Failed to get user information'], 500);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
}

}

