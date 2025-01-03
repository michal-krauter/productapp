<?php

namespace App\Http\Controllers;

use App\Config\Configuration;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Create product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Validation of input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // If validation fails, we return errors with HTTP status 400 (Bad Request)
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $product = new Product();
            $product->name = $request->input(Configuration::PRODUCT_PROPERTY_NAME);
            $product->price = $request->input(Configuration::PRODUCT_PROPERTY_PRICE);
            $product->save();

            // creating a successful response
            return response()->json([
                'status' => 'created',
                'product' => $product
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // If an error occurs we return HTTP 500 (Internal Server Error)
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation of input data
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0'
        ]);

        // If validation fails, we return errors with HTTP status 400 (Bad Request)
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Product search by ID
        $product = Product::find($id);

        if (!$product) {
            // If product does not exist, return HTTP status 404 (Not Found)
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Product value updates, if provided
        $product->name = $request->input(Configuration::PRODUCT_PROPERTY_NAME, $product->name);
        $product->price = $request->input(Configuration::PRODUCT_PROPERTY_PRICE, $product->price);

        try {
            $product->save();

            // Creating a successful response with updated product
            return response()->json([
                'status' => 'success',
                'product' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // If an error occurs we return HTTP 500 (Internal Server Error)
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a product by ID.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        // Product search by ID
        $product = Product::find($id);

        // If product does not exist, return HTTP status 404 (Not Found)
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $product->delete();

            // In case of successful deletion, return HTTP status 204 (No Content)
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            // If an error occurs we return HTTP 500 (Internal Server Error)
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get product by ID.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        // Product search by ID
        $product = Product::find($id);

        // If product does not exist, return HTTP status 404 (Not Found)
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // If product exists, return it with HTTP status 200 (OK)
        return response()->json([
            'status' => 'success',
            'product' => $product
        ], Response::HTTP_OK);
    }

    /**
     * Get all products.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Load all products
        $products = Product::all();

        // Return all products with HTTP status 200 (OK)
        return response()->json([
            'status' => 'success',
            'products' => $products
        ], Response::HTTP_OK);
    }

}
