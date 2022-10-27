<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProductController extends Controller
{

    public function show()
    {
        return response()->json(['status' => true, 'message' => 'Success', 'product' => Product::all()], Response::HTTP_OK);
    }

    public function create(Request $request)
    {

        if ($request->get('id')) {
            $product = Product::find($request->get('id'));
        } else {
            $product = new Product;
        }

        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->price = $request->get('price');
        $product->save();

        return response()
            ->json([
                'product' => $product,
            ], Response::HTTP_CREATED);
    }


    public function delete($id)
    {
        Product::find($id)->delete();
        return response()
            ->json([
                'message' => 'Success'
            ], Response::HTTP_OK);
    }
}
