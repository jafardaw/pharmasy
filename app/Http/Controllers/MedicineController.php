<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class MedicineController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'=> "تم جلب االادوية بنجاح ",
            "state"=>"true",
            'listMedicine' => Medicine::all()
        ]);
        
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'scientific_name' => 'required|string|max:255',
                'commercial_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'manufacturer' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'expiry_date' => 'required|date|after:today',
                'price' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $medicine = Medicine::create($request->all());

            return response()->json([
                'message' => 'تمت إضافة الدواء بنجاح',
                'medicine' => $medicine
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء الإضافة', 'details' => $e->getMessage()], 500);
        }
    }

    public function show(Medicine $medicine)
    {
        return $medicine;
    }
    public function update(Request $request, Medicine $medicine)
    {
        try {
            $validator = Validator::make($request->all(), [
                'scientific_name' => 'sometimes|string|max:255',
                'commercial_name' => 'sometimes|string|max:255',
                'category' => 'sometimes|string|max:255',
                'manufacturer' => 'sometimes|string|max:255',
                'quantity' => 'sometimes|integer|min:0',
                'expiry_date' => 'sometimes|date|after:today',
                'price' => 'sometimes|numeric|min:0'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $medicine->fill($request->only([
                'scientific_name', 
                'commercial_name', 
                'category', 
                'manufacturer', 
                'quantity', 
                'expiry_date', 
                'price'
            ]));
    
    
            $medicine->save();
    
            return response()->json([
                'message' => 'تم تحديث الدواء بنجاح',
                'medicine' => $medicine
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء التحديث', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $medicine = Medicine::find($id);
    
            if (!$medicine) {
                return response()->json([
                    'error' => 'الدواء غير موجود'
                ], 404);
            }
    
            $medicine->delete();
    
            return response()->json([
                'message' => 'تم حذف الدواء بنجاح'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء الحذف',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function getByCategory(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category = trim($request->category);

        \Log::info('Category received:', ['category' => $category]);

        $medicines = Medicine::whereRaw('LOWER(category) = ?', [strtolower($category)])
                            ->where('quantity', '>', 0)
                            ->orderBy('commercial_name')
                            ->get();

        if ($medicines->isEmpty()) {
            return response()->json([
                'message' => 'لا توجد أدوية في هذا التصنيف أو الكمية صفر'
            ], 404);
        }

        return response()->json([
            'message' => 'تم جلب الأدوية حسب التصنيف بنجاح',
            'data' => $medicines
            
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'حدث خطأ أثناء جلب البيانات',
            'details' => $e->getMessage()
        ], 500);
    }
}
/////////////////////////////////////
public function searchMedicines(Request $request)
{
    $validator = Validator::make($request->all(), [
        'search_term' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $query = Medicine::query();

    if ($request->has('search_term')) {
        $query->where(function($q) use ($request) {
            $q->where('commercial_name', 'LIKE', '%'.$request->search_term.'%')
              ->orWhere('scientific_name', 'LIKE', '%'.$request->search_term.'%');
        });
    }

    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    $medicines = $query->where('quantity', '>', 0)
                      ->orderBy('commercial_name')
                      ->get();

    return response()->json([
        'message' => 'تم جلب نتائج البحث بنجاح',
        'data' => $medicines
        
    ]);
}
    
    
}
