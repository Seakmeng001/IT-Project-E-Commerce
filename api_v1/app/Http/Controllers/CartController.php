<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        // Validate the request input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if the product already exists in the cart for the authenticated user
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            // If the product is already in the cart, increment the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // If the product is not in the cart, create a new cart item with the specified quantity
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Return the updated cart or success message
        $updatedCart = Cart::where('user_id', Auth::id())
                           ->with('product')
                           ->get();

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $updatedCart
        ]);
    }

    /**
     * View all cart items
     */
    public function viewCart()
    {
        // Get all cart items for the authenticated user, including the product details
        $cartItems = Cart::where('user_id', Auth::id())
                         ->with('product') // Assuming each cart item is related to a product
                         ->get();

        // Format the response to include product details, quantity, and price after discount
        $formattedCart = $cartItems->map(function($cartItem) {
            $product = $cartItem->product;
            $priceAfterDiscount = $product->price - ($product->price * $product->discount / 100);

            return [
                'id' => $cartItem->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'brand' => $product->brand,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'discount' => $product->discount,
                'price_after_discount' => $priceAfterDiscount,
                'total' => $priceAfterDiscount * $cartItem->quantity,
                'image' => $product->image,
            ];
        });

        return response()->json($formattedCart);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        // Find the cart item by ID for the authenticated user
        $cartItem = Cart::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if ($cartItem) {
            // Check if the quantity is greater than 1
            if ($cartItem->quantity > 1) {
                // Decrease the quantity by 1
                $cartItem->quantity -= 1;
                $cartItem->save(); // Save the updated cart item
                return response()->json([
                    'message' => 'Item quantity decreased',
                    'updated_quantity' => $cartItem->quantity,
                    'cart' => Cart::where('user_id', Auth::id())->with('product')->get()
                ]);
            } else {
                // If quantity is 1, remove the item completely
                $cartItem->delete();
                return response()->json([
                    'message' => 'Item removed from cart',
                    'cart' => Cart::where('user_id', Auth::id())->with('product')->get()
                ]);
            }
        }

        // If the item doesn't exist, return an error message
        return response()->json(['message' => 'Item not found'], 404);
    }
}
