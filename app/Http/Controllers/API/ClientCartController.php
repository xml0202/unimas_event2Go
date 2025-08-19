<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientCartController extends Controller
{
	public function index(Request $request)
	{
		$request->validate([
			'cart' => 'array',
			'cart.*.id' => 'required|exists:products,id',
			'cart.*.quantity' => 'required|integer|min:1',
		]);

		$cart = $request->input('cart', []);
		$cartWithDetails = [];

		foreach ($cart as $item) {
			$product = Product::find($item['id']);
			$cartWithDetails[] = [
				'id' => $product->id,
				'name' => $product->name,
				'quantity' => $item['quantity'],
				'price' => $product->price,
				'subtotal' => $product->price * $item['quantity']
			];
		}

		return response()->json([
			'cart' => $cartWithDetails,
		]);
	}

	public function update(Request $request)
	{
		$request->validate([
			'cart' => 'array',
			'cart.*.id' => 'required|exists:products,id',
			'cart.*.quantity' => 'required|integer|min:1',
		]);

		$updatedCart = $request->input('cart', []);
		return response()->json(['success' => true, 'cart' => $updatedCart]);
	}

	public function summary(Request $request)
	{
		$request->validate([
			'cart' => 'array',
			'cart.*.id' => 'required|exists:products,id',
			'cart.*.quantity' => 'required|integer|min:1',
		]);

		$cart = $request->input('cart', []);
		$cartWithDetails = [];
		$total = 0;

		foreach ($cart as $item) {
			$product = Product::find($item['id']);
			$cartWithDetails[] = [
				'id' => $product->id,
				'name' => $product->name,
				'quantity' => $item['quantity'],
				'price' => $product->price,
				'subtotal' => $product->price * $item['quantity']
			];
			$total += $product->price * $item['quantity'];
		}

		return response()->json([
			'cart' => $cartWithDetails,
			'total' => $total
		]);
	}

	public function add(Request $request, Product $product)
	{
		$request->validate([
			'quantity' => 'required|integer|min:1',
			'cart' => 'array',
		]);

		$cart = $request->input('cart', []);
		$quantity = $request->input('quantity');

		$existingItem = collect($cart)->firstWhere('id', $product->id);

		if ($existingItem) {
			$existingItem['quantity'] += $quantity;
		} else {
			$cart[] = [
				'id' => $product->id,
				'quantity' => $quantity,
			];
		}

		return response()->json([
			'success' => true,
			'message' => 'Product added to cart successfully!',
			'cart' => $cart
		]);
	}

	public function remove(Request $request, Product $product)
	{
		$request->validate([
			'cart' => 'array',
		]);

		$cart = collect($request->input('cart', []))->reject(function ($item) use ($product) {
			return $item['id'] == $product->id;
		})->values()->all();

		return response()->json([
			'success' => true,
			'message' => 'Product removed successfully!',
			'cart' => $cart
		]);
	}

	public function processCheckout(Request $request)
	{
		$request->validate([
			'payment_method' => 'required|string',
			'cart' => 'array',
			'cart.*.id' => 'required|exists:products,id',
			'cart.*.quantity' => 'required|integer|min:1',
		]);

		$cart = $request->input('cart', []);

		if (count($cart) == 0) {
			return response()->json(['error' => 'Your cart is empty.'], 400);
		}

		$total = 0;
		$orderItems = [];

		foreach ($cart as $item) {
			$product = Product::findOrFail($item['id']);
			$subtotal = $product->price * $item['quantity'];
			$total += $subtotal;

			$orderItems[] = [
				'product_id' => $product->id,
				'quantity' => $item['quantity'],
				'price' => $product->price,
			];
		}

		$order = Order::create([
			'user_id' => auth()->id(),
			'total' => $total,
			'payment_method' => $request->payment_method,
			'status' => 'pending',
		]);

		$order->items()->createMany($orderItems);

		$invoice = Invoice::create([
			'order_id' => $order->id,
			'customer_username' => auth()->user()->username,
			'amount' => $total,
			'due_date' => now()->addDays(30),
			'status' => 'unpaid'
		]);

		$invoice->items()->createMany($orderItems);

		return response()->json([
			'success' => true,
			'message' => 'Order placed and invoice generated successfully.',
			'order_id' => $order->id,
			'invoice_id' => $invoice->id
		], 201);
	}
}
