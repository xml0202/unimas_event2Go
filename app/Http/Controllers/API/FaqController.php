<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::all();
        return response()->json($faqs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq = Faq::create($validatedData);
        return response()->json(['message' => 'FAQ created successfully', 'data' => $faq], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }
        return response()->json($faq);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        $faq->update($validatedData);
        return response()->json(['message' => 'FAQ updated successfully', 'data' => $faq], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully'], 200);
    }
}
