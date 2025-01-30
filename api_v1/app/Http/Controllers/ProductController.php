<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\FileUploadController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{


    public function getAll()
    {
        try {

            $products = Product::paginate(12);

            return response()->json($products, Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }
    public function getById(string $id)
    {
        try {

            $Product = Product::find($id);
            if(!$Product){
                return response()->json(['message' => 'Product not found!'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($Product, Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }
    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|min:3',
                'brand' => 'required',
                'price' => 'required',
                'discount' => 'nullable',
                'description' => 'nullable|min:3',
                'category_id' => 'exists:categories,id',
                'image' => 'required'
            ]);
            
            $image = FileUploadController::storeImage($request->file('image'), 'uploads/products');
            $data['image'] = $image;
        
            $product = Product::create($data);
            
            return response()->json( ['message' => 'Created successfully', 'product' => $product],Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }
    public function update(Request $request, string $id)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'nullable|string|min:3',
            'brand' => 'nullable|string',
            'price' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'description' => 'nullable|string|min:3',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Ensure valid image format
        ]);

        // Find the product or fail
        $product = Product::findOrFail($id);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $validatedData['image'] = FileUploadController::storeImage($request->file('image'), 'uploads/products');
        }

        // Update the product with only provided fields
        $product->update(array_filter($validatedData)); // `array_filter` removes null values

        // Return the updated product
        return response()->json([
            'message' => 'Updated successfully',
            'product' => $product
        ], Response::HTTP_OK);

    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong!',
            'error' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
    
    public function delete(string $id)
    {
        try {

            $product = Product::find($id);
            if(!$product){
                return response()->json(['message' => 'product not found!'], Response::HTTP_NOT_FOUND);
            }

            $product->delete();

            return response()->json( ['message' => 'Deleted successfully'],Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }


    
    //::::::::::::::::>> Start helper
    protected function handleValidationException(ValidationException $e)
    {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $e->errors(),
        ], Response::HTTP_BAD_REQUEST);
    }

    protected function handleUnexpectedException(\Exception $e)
    {
        return response()->json([
            'message' => 'Server Error',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    //::::::::::::::::>> Start helper
}
    