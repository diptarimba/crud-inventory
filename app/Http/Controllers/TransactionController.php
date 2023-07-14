<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $transaction = Transaction::create($request->all());

        if ($transaction->customer_id) {
            $this->decreaseProductQuantity($productId, $quantity);
        } elseif ($transaction->supplier_id) {
            $this->increaseProductQuantity($productId, $quantity);
        }

        return response()->json($transaction, 201);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $this->validate($request, [
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $transaction->product_id;
        $previousQuantity = $transaction->quantity;
        $newQuantity = $request->input('quantity');

        $transaction->quantity = $newQuantity;
        $transaction->save();

        if ($transaction->customer_id) {
            $this->updateProductQuantity($productId, $previousQuantity, $newQuantity, 'decrease');
        } elseif ($transaction->supplier_id) {
            $this->updateProductQuantity($productId, $previousQuantity, $newQuantity, 'increase');
        }

        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $productId = $transaction->product_id;
        $quantity = $transaction->quantity;

        if ($transaction->customer_id) {
            $this->increaseProductQuantity($productId, $quantity);
        } elseif ($transaction->supplier_id) {
            $this->decreaseProductQuantity($productId, $quantity);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted']);
    }

    private function increaseProductQuantity($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->quantity += $quantity;
        $product->save();
    }

    private function decreaseProductQuantity($productId, $quantity)
    {
        $product = Product::find($productId);

        if ($product->quantity >= $quantity) {
            $product->quantity -= $quantity;
            $product->save();
        } else {
            return response()->json(['message' => 'Insufficient product quantity'], 400);
        }
    }

    private function updateProductQuantity($productId, $previousQuantity, $newQuantity, $operation)
    {
        $product = Product::find($productId);

        if ($operation === 'increase') {
            $quantityDiff = $newQuantity - $previousQuantity;
            $product->quantity += $quantityDiff;
        } elseif ($operation === 'decrease') {
            $quantityDiff = $previousQuantity - $newQuantity;
            $product->quantity -= $quantityDiff;
        }

        $product->save();
    }
}
