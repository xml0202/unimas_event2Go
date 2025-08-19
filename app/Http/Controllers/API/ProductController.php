<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->input('search');
		$query = Product::query();

		if ($search) {
			$query->where('name', 'like', "%{$search}%")
				->orWhere('description', 'like', "%{$search}%")
				->orWhere('type', 'like', "%{$search}%");
		}

		$products = $query->latest()->paginate(9);
		return response()->json($products);
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required',
			'description' => 'required',
			'price' => 'required|numeric',
			'type' => 'required|string',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		if ($request->hasFile('image')) {
			$imagePath = $request->file('image')->store('products', 'public');
			$validatedData['image'] = $imagePath;
		}

		$product = Product::create($validatedData);

		return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
	}

	public function show(Product $product)
	{
		return response()->json($product);
	}

	public function update(Request $request, Product $product)
	{
		$validatedData = $request->validate([
			'name' => 'required',
			'description' => 'required',
			'price' => 'required|numeric',
			'type' => 'required|string',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		if ($request->hasFile('image')) {
			// Delete old image
			if ($product->image) {
				Storage::disk('public')->delete($product->image);
			}
			$imagePath = $request->file('image')->store('products', 'public');
			$validatedData['image'] = $imagePath;
		}

		$product->update($validatedData);

		return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
	}

	public function destroy(Product $product)
	{
		// Delete image if exists
		if ($product->image) {
			Storage::disk('public')->delete($product->image);
		}

		$product->delete();

		return response()->json(['message' => 'Product/Service deleted successfully']);
	}
}
