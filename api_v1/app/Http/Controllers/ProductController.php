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
    public function update(Request $request, string $id)
    {
        try {
            // Validate the incoming request
            $data = $request->validate([
                'name' => 'nullable|min:3',
                'brand' => 'nullable',
                'price' => 'nullable|numeric',
                'discount' => 'nullable|numeric',
                'description' => 'nullable|min:3',
                'category_id' => 'exists:categories,id',
                'image' => 'nullable|image'
            ]);
    
            // Find the product by ID
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found!'], Response::HTTP_NOT_FOUND);
            }
    
            // Check if an image file has been uploaded
            if ($request->hasFile('image')) {
                $image = FileUploadController::storeImage($request->file('image'), 'uploads/products');
                if ($image) {
                    $data['image'] = $image; // Update the image if a new one is uploaded
                }
            } else {
                // If no new image is uploaded, keep the existing image
                $data['image'] = $product->image; // Retain the existing image
            }
    
            // Update the product with the new data
            $product->update($data);
    
            // Return the updated product
            return response()->json([
                'message' => 'Updated successfully',
                'product' => $product // Return the updated product
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
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
    