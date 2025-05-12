<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
        public function index()
    {
        $orders = Order::with('items')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $orders,
            'message' => 'تم جلب الطلبيات بنجاح'
        ]);
    }
     // تغيير حالة الطلب
public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,sent,received',
    ]);

    try {
        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'message' => 'تم تحديث حالة الطلبية بنجاح',
            'order' => $order
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'حدث خطأ أثناء تحديث الحالة',
            'details' => $e->getMessage()
        ], 500);
    }
}

      // تغيير حالة الدفع
    public function updatePaymentStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validator = validator()->make($request->all(), [
            'payment_status' => 'required|in:unpaid,paid'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            'message' => 'تم تغيير حالة الدفع بنجاح',
            'order' => $order
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.medicine_id' => 'required|exists:medicines,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $order = Order::create([
                'pharmacist_id' => auth()->id(),
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'تم إنشاء الطلبية بنجاح',
                'order' => $order->load('items.medicine'),
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء الطلبية',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    // App\Http\Controllers\OrderController.php

public function myOrders(Request $request)
{
    try {
        $pharmacy = auth()->user(); // يفترض أنك مفعل Sanctum للمصادقة

        $orders = $pharmacy->orders()->with(['items.medicine'])->orderByDesc('created_at')->get();

        return response()->json([
            'data' => $orders,
            'message' => 'تم جلب الطلبيات بنجاح'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'حدث خطأ أثناء جلب الطلبيات',
            'details' => $e->getMessage()
        ], 500);
    }
}

}
