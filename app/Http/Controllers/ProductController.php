<?php

namespace App\Http\Controllers;

use App\Config\Configuration;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
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



}
