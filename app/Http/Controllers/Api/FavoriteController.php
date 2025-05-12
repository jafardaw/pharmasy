<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;
use App\Models\FavoriteMedicine;


class FavoriteController extends Controller
{
  

     public function index()
    {
        $pharmacy = Auth::guard('pharmacy')->user();

        if (!$pharmacy) {
            return response()->json(['message' => 'غير مصادق'], 401);
        }

        return response()->json([
            'data' => $pharmacy->favorites,
            'message' => 'تم جلب قائمة المفضلة'
        ]);
    }

    public function toggle(Request $request)
    {
        $pharmacy = Auth::guard('pharmacy')->user();

        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $pharmacy->favorites()->toggle($request->medicine_id);

        return response()->json([
            'message' => 'تم تحديث المفضلة'
        ]);
    }

    public function check($medicineId)
    {
        $pharmacy = Auth::guard('pharmacy')->user();

        $isFavorite = $pharmacy->favorites()->where('medicine_id', $medicineId)->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
