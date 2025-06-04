<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::where('product_id', $request->product_id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if ($product->quantity_in_stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->quantity_in_stock
            ], 400);
        }

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;

            if ($newQuantity > $product->quantity_in_stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. Stock limit: ' . $product->quantity_in_stock
                ], 400);
            }

            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'max_stock' => $product->quantity_in_stock
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart' => $this->getCartSummary($cart)
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not in cart'
            ], 404);
        }

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::where('product_id', $request->product_id)->first();

            if ($request->quantity > $product->quantity_in_stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity exceeds available stock'
                ], 400);
            }

            $cart[$productId]['quantity'] = $request->quantity;
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart' => $this->getCartSummary($cart)
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart' => $this->getCartSummary($cart)
        ]);
    }

    public function getCart()
    {
        $cart = Session::get('cart', []);
        return response()->json([
            'success' => true,
            'cart' => $this->getCartSummary($cart)
        ]);
    }

    public function clearCart()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart' => $this->getCartSummary([])
        ]);
    }

    private function getCartSummary($cart)
    {
        $items = [];
        $total = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $total += $itemTotal;
            $totalItems += $item['quantity'];

            $items[] = [
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $itemTotal,
                'max_stock' => $item['max_stock']
            ];
        }

        return [
            'items' => $items,
            'total' => round($total, 2),
            'total_items' => $totalItems,
            'item_count' => count($items)
        ];
    }
}
