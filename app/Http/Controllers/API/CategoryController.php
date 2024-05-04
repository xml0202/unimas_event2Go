<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => CategoryResource::collection($categories)], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories|max:255', // Adjust max length if needed
        ], [
            'category_name.unique' => 'The category name has already been taken.', // Custom message for uniqueness rule
        ]);
    
        $requestData = array_merge($request->all(), ['status' => 1, 'listed' => 1]);
    
        $category = Category::create($requestData);
        return response()->json(['category' => new CategoryResource($category)], 201);
    }

    public function show(Category $category)
    {
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        
        return response()->json(['category' => new CategoryResource($category)], 200);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|unique:categories|max:255', // Adjust max length if needed
        ], [
            'category_name.unique' => 'The category name has already been taken.', // Custom message for uniqueness rule
        ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        $requestData = array_merge($request->all());
    
        $category->update($requestData);
    
        return response()->json(['category' => new CategoryResource($category)], 200);
    }

    public function destroy(Category $category)
    {
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
