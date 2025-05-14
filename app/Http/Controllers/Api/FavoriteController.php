<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
use App\Models\Medicine;

class FavoriteController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::guard('pharmacy')->user();

        return response()->json([
            'data' => $pharmacy->favorites,
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $pharmacy = Auth::guard('pharmacy')->user();
        $pharmacy->favorites()->toggle($request->medicine_id);

        return response()->json(['message' => 'تم تعديل المفضلة بنجاح']);
    }
}
