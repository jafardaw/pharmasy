<?php


// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Medicine;
// use Illuminate\Http\Request;

// class PreferingModel extends Controller
// {
//             public function index()
//     {
//         $pharmacy = Auth::guard('pharmacy')->user();

//         return response()->json([
//             'data' => $pharmacy->favorites,
//         ]);
//     }

//     public function toggleFavorite(Request $request)
//     {
//         $request->validate([
//             'medicine_id' => 'required|exists:medicines,id',
//         ]);

//         $pharmacy = Auth::guard('pharmacy')->user();
//         $pharmacy->favorites()->toggle($request->medicine_id);

//         return response()->json(['message' => 'تم تعديل المفضلة بنجاح']);
//     }
// }
