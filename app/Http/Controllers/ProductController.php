<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a page of listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->with('supplier')->paginate(5);

        if ($products) {
            return MessagesController::responder(200, $products);
        }

        return MessagesController::responder(500, [], 'Cannot fetch products.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $products = Product::orderBy('created_at', 'DESC')->with('supplier')->get();

        if ($products) {
            return MessagesController::responder(200, $products);
        }

        return MessagesController::responder(500, [], 'Cannot fetch products.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $product = Product::find($id)->load('supplier');

        if ($product) {
            return MessagesController::responder(200, $product);
        }

        return MessagesController::responder(404, [], 'No such product.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'productName' => 'required|string',
            'supplier_id' => 'required|integer',
            'unitPrice' => 'required|numeric|gt:0'
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'integer' => 'The :attribute field should be integer.',
            'numeric' => 'The :attribute field should be numeric.',
            'gt:0' => 'The :attribute field should be positive.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return MessagesController::responder(400, $validator->errors(), 'Some invalid fields.');
        }

        if (!Supplier::find($request->supplier_id)) {
            return MessagesController::responder(404, [], 'No such supplier.');
        }

        $newProduct = new Product;
        $newProduct->productName = $request->productName;
        $newProduct->supplier_id = $request->supplier_id;
        $newProduct->unitPrice = $request->unitPrice;

        $saved = $newProduct->save();

        if ($saved) {
            return MessagesController::responder(201, $newProduct);
        }

        return MessagesController::responder(500, [], 'Cannot create product.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'productName' => 'required|string',
            'supplier_id' => 'required|integer',
            'unitPrice' => 'required|numeric|gt:0'
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'integer' => 'The :attribute field should be integer.',
            'numeric' => 'The :attribute field should be numeric.',
            'gt:0' => 'The :attribute field should be positive.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return MessagesController::responder(400, $validator->errors(), 'Some invalid fields.');
        }

        $existingProduct = Product::find($id);

        if ($existingProduct === null) {
            return MessagesController::responder(404, [], 'No such product.');
        }

        $existingProduct->productName = $request->productName;
        $existingProduct->supplier_id = $request->supplier_id;
        $existingProduct->unitPrice = $request->unitPrice;

        $saved = $existingProduct->save();

        if ($saved) {
            return MessagesController::responder(200, $existingProduct);
        }

        return MessagesController::responder(500, [], 'Cannot update product.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingProduct = Product::find($id);

        if ($existingProduct === null) {
            return MessagesController::responder(404, [], 'No such product.');
        }

        $deleted = $existingProduct->delete();

        if ($deleted) {
            return MessagesController::responder(200, [], 'Product deleted.');
        }

        return MessagesController::responder(500, [], 'Cannot delete product.');
    }
}
