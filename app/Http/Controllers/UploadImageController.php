<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'image'=> 'required|image|mimes:jpeg,png,jpg,webp|max:1000',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        request()->image->move(public_path('storage'), $imageName);
        return response()->json($imageName, 200);
    }
}
