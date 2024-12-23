<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\User;
use App\Models\Video;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{

    public function subscribe(Request $request)
    {
        if(!Auth::user()){
            return view("auth.login");
        }
        $userId = $request['id'];
        $user = User::find($userId);
        $currentUser = Auth::user();

        $channel = $user->channel;
        $channelId = $channel['id'];


        $alreadySubscribed = $currentUser->subscribedChannels()->where('channel_id', $channelId)->exists();
        if($alreadySubscribed){
            return back();
        }

        $currentUser->subscribedChannels()->attach($channelId);

        $channel->increment('subscribers');

        return back();


    }

    public function unsubscribe(Request $request){
        $ownerID = $request['id'];
        $owner = User::find($ownerID);
        $channel = $owner->channel;

        $user = Auth::user();

        $user->subscribedChannels()->detach($channel['id']);

        $channel->decrement('subscribers');

        return back();
    }

    /**
     * Display a listing of the resource.
     */

    public function individual($id){
        $video = Video::findOrFail($id);
        $videos = Video::query()->get();
        $channel = $video->channel;
        $channelId = $channel["id"];
        $ownerId = $channel["user_id"];



        if(Auth::user()){
            $user = Auth::user();

            if($user['id'] == $ownerId){
                return view('video.individual-video',[
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'owner' => true,
                ]);
            }
            $subscribed = $user->subscribedChannels()->where("channel_id", $channelId)->exists();
            if($subscribed){
                return view('video.individual-video',[
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'subscribed' => true,
                ]);
            }
            else{
                return view('video.individual-video',[
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'subscribed' => false,
                ]);
            }
        }


        return view('video.individual-video',[
            'video' => $video,
            'videos' => $videos,
            'channel' => $channel,
            'subscribed' => false,
        ]);
    }
    public function index()
    {
        $videos = Video::query()->get();

        if(Auth::check()){
            $user = Auth::user();
            $channel = $user->channel;

            return view('home', [
                'videos' => $videos,
                'user' => $user,
                'channel' => $channel,
            ]);
        }

        return view('home', [
            'videos' => $videos
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if($user->channel !== null){
            return view('video.create-video',[
                'channel' => true,
            ]);
        }
        return view('video.create-video',[
            'channel' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//
//        $credentials = $request->validate([
//            'title' => 'required|string',
//            'description' => 'required|string',
//            'video_file' => 'required|file| mimes:mp4,mov,ogg,qt | max:50000',
//        ]);
//
//        $path = Storage::disk('public')->put('videos', $request['video_file']);
//
//        $thumbnail =
//        Video::create([
//            'title' => $credentials['title'],
//            'description' => $credentials['description'],
//            'video' => $path,
//            'thumbnail' =>
//        ]);
//
//        $videos = Video::query()->get();
//
//        return redirect('/');
//
//    }
//


    public function store(Request $request)
    {
        $user = Auth::user();

        $channel = $user->channel;

        // Step 1: Validate the input
        $credentials = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'video_file' => 'required|file|mimes:mp4,mov,ogg,qt|max:50000',
        ]);

        // Step 2: Upload the video file
        try {
            $videoPath = Storage::disk('public')->put('videos', $request->file('video_file'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload video: ' . $e->getMessage()], 500);
        }

        $videoFullPath = storage_path('app/public/' . $videoPath);

        // Step 3: Create a thumbnail directory and path
        $thumbnailDirectory = storage_path('app/public/thumbnails');
        if (!File::exists($thumbnailDirectory)) {
            File::makeDirectory($thumbnailDirectory, 0755, true); // Create directory with write permissions
        }

        // Generate a random string for the thumbnail file name
        $randomString = Str::random(16);
        $thumbnailFileName = $randomString . '.jpg';
        $thumbnailFullPath = $thumbnailDirectory . '/' . $thumbnailFileName;

        // Step 4: Generate a thumbnail after successful video upload
        try {
            $ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => '/opt/homebrew/bin/ffmpeg',
                'ffprobe.binaries' => '/opt/homebrew/bin/ffprobe',
                'timeout'          => 3600,
                'ffmpeg.threads'   => 12,
            ]);

//            $ffmpeg = \FFMpeg\FFMpeg::create([
//                'ffmpeg.binaries'  => 'C:\\ffmpeg\\bin\\ffmpeg.exe', // Path to ffmpeg.exe
//                'ffprobe.binaries' => 'C:\\ffmpeg\\bin\\ffprobe.exe', // Path to ffprobe.exe
//                'timeout'          => 3600, // Set timeout to 1 hour
//                'ffmpeg.threads'   => 12,   // Number of threads
//            ]);


            $video = $ffmpeg->open($videoFullPath);
            $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(0))
                ->save($thumbnailFullPath);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate thumbnail: ' . $e->getMessage()], 500);
        }

        // Step 5: Save the video and thumbnail paths in the database
        Video::create([
            'channel_id' => $channel['id'],
            'title' => $credentials['title'],
            'description' => $credentials['description'],
            'video' => $videoPath, // Relative path to the video
            'thumbnail' => 'thumbnails/' . $thumbnailFileName, // Relative path to the thumbnail
            'likes' => 0,
            'views' => 0,
        ]);

        return redirect('/')->with('success', 'Video uploaded and thumbnail generated successfully!');
    }

//    public function store(Request $request)
//    {
//        // Step 1: Validate the input
//        $credentials = $request->validate([
//            'title' => 'required|string',
//            'description' => 'required|string',
//            'video_file' => 'required|file|mimes:mp4,mov,ogg,qt|max:50000',
//        ]);
//
//        // Step 2: Upload the video file
//        try {
//            $videoPath = Storage::disk('public')->put('videos', $request->file('video_file'));
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'Failed to upload video: ' . $e->getMessage()], 500);
//        }
//
//        $videoFullPath = storage_path('app/public/' . $videoPath);
//
//        // Step 3: Create a thumbnail directory and path
//        $thumbnailDirectory = storage_path('app/public/thumbnails');
//        if (!File::exists($thumbnailDirectory)) {
//            File::makeDirectory($thumbnailDirectory, 0755, true); // Create directory with write permissions
//        }
//
//        $thumbnailFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($request->file('video_file')->getClientOriginalName(), PATHINFO_FILENAME)) . '.jpg';
//        $thumbnailFullPath = $thumbnailDirectory . '/' . $thumbnailFileName;
//
//        // Step 4: Generate a thumbnail after successful video upload
//        try {
//            $ffmpeg = \FFMpeg\FFMpeg::create([
//                'ffmpeg.binaries'  => '/opt/homebrew/bin/ffmpeg',
//                'ffprobe.binaries' => '/opt/homebrew/bin/ffprobe',
//                'timeout'          => 3600,
//                'ffmpeg.threads'   => 12,
//            ]);
//
//            $video = $ffmpeg->open($videoFullPath);
//            $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(0))
//                ->save($thumbnailFullPath);
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'Failed to generate thumbnail: ' . $e->getMessage()], 500);
//        }
//
//        // Step 5: Save the video and thumbnail paths in the database
//        Video::create([
//            'title' => $credentials['title'],
//            'description' => $credentials['description'],
//            'video' => $videoPath, // Relative path to the video
//            'thumbnail' => 'thumbnails/' . $thumbnailFileName, // Relative path to the thumbnail
//        ]);
//
//        return redirect('/')->with('success', 'Video uploaded and thumbnail generated successfully!');
//    }



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
