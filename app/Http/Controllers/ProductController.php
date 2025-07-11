<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected string $file_name = "products.json";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $existing_products = [];
        if (Storage::disk('local')->exists($this->file_name)) {
            $existing_products = json_decode(Storage::disk('local')->get($this->file_name), true);

            if (!is_array($existing_products)) $existing_products = [];
        }

        usort($existing_products, function ($a, $b) {
            return strtotime($b['datetime_submitted']) <=> strtotime($a['datetime_submitted']);
        });

        return response()->json($existing_products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form_data = $request->only(['product_name', 'quantity', 'price_per_item']);
        $total_value = $form_data["quantity"] * $form_data["price_per_item"];
        $datetime_submitted = now()->format('Y-m-d H:i:s');

        $product_item = [
            "product_name" => $form_data["product_name"],
            "quantity" => (int)$form_data["quantity"],
            "price_per_item" => (float)$form_data["price_per_item"],
            "total_value" => $total_value,
            "datetime_submitted" => $datetime_submitted
        ];

        $existing_products = [];
        if (Storage::disk('local')->exists($this->file_name)) {
            $existing_products = json_decode(Storage::disk('local')->get($this->file_name), true);

            if (!is_array($existing_products)) $existing_products = [];
        }

        $existing_products[] = $product_item;
        Storage::disk('local')->put($this->file_name, json_encode($existing_products));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
