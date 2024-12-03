<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::query()->get();

        return view('home',[
            'videos' => $videos
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('video.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'video_file' => 'required|file| mimes:mp4,mov,ogg,qt | max:50000',
        ]);

        // If validation passes, check the uploaded file
//        dd($request->file('video_file'));

        $path = Storage::disk('public')->put('videos', $request['video_file']);


        Video::create([
            'title' => $credentials['title'],
            'description' => $credentials['description'],
            'video' => $path,
        ]);

        $videos = Video::query()->get();

        return redirect('/');

    }


    /**
     * Display the specified resource.
     */
    public function show($filename)
    {
        $path = 'videos/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            dump('it exists');

//            $video = Video::where()
        } else {
            dd('it does not exist');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
