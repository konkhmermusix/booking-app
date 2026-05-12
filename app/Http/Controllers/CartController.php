<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $id = $request->id;
        $roomType = RoomType::findOrFail($id);

        // ទាញយក cart ពី session
        $cart = session()->get('cart', []);

        // បើមានក្នុង cart រួចហើយ មិនបាច់ថែមទេ
        if (isset($cart[$id])) {
            return response()->json(['message' => 'បន្ទប់នេះមានក្នុងបញ្ជីរួចហើយ', 'status' => 'warning']);
        }

        // បន្ថែមទៅក្នុង cart session
        $cart[$id] = [
            "id" => $roomType->id,
            "name" => $roomType->name,
            "price" => $roomType->base_price,
            "image" => $roomType->images->first()->image_path ?? ''
        ];

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'បន្ថែមទៅក្នុងបញ្ជីជោគជ័យ',
            'cart_count' => count($cart),
            'status' => 'success'
        ]);
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}
