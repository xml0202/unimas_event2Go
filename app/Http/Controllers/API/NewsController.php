<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        return response()->json(['data' => $news], Response::HTTP_OK);
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
            'event_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'link' => 'nullable',
            // Add more validation rules as needed
        ]);
        
        $attachments = [];
        foreach ($validatedData['link'] as $attachmentData) {
            // Decode the base64-encoded attachment data
            $fileContent = base64_decode($attachmentData);
        
            // Generate a random filename with a file extension
            $fileName = Str::random(20) . '.jpg'; // For example, you can assume it's a JPEG file
        
            // Store the attachment file in the filesystem
            Storage::disk('public')->put($fileName, $fileContent);
        
            // Add the filename to the list of attachments
            $attachments[] = $fileName;
        }
        $validatedData['link'] = $attachments;
        $news = News::create($validatedData);
        return response()->json(['data' => $news, 'message' => 'News created successfully'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $news], Response::HTTP_OK);
    }
    
    public function getEventNews($event_id)
    {
        $news = News::where('event_id', $event_id)->get();
        if (!$news) {
            return response()->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $news], Response::HTTP_OK);
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
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'link' => 'nullable',
            // Add more validation rules as needed
        ]);

        $news->update($validatedData);
        return response()->json(['data' => $news, 'message' => 'News updated successfully'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $news->delete();
        return response()->json(['message' => 'News deleted successfully'], Response::HTTP_OK);
    }
}
