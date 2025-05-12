<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Pharmacy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PharmacyAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'pharmacy_name' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'phone' => 'required|string|unique:pharmacies|regex:/^09[0-9]{8}$/',
                'password' => 'required|string|min:6|confirmed'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $pharmacy = Pharmacy::create([
                'name' => $request->name,
                'pharmacy_name' => $request->pharmacy_name,
                'region' => $request->region,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
    
            $token = $pharmacy->createToken('pharmacy-token')->plainTextToken;
    
            return response()->json([
                'message' => 'تم تسجيل الصيدلية بنجاح',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'pharmacy' => $pharmacy
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);
   
        $pharmacy = Pharmacy::where('phone', $request->phone)->first();

        if (!$pharmacy || !Hash::check($request->password, $pharmacy->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $pharmacy->createToken('pharmacy-token')->plainTextToken;

        return response()->json([
            'message'=>'تم تسجيل دخولك بنجاح',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'pharmacy' => $pharmacy
        ]);
    }

    public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'message' => 'تم تسجيل الخروج بنجاح'
    ]);
}
}   