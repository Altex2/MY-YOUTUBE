<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\User;
use App\Models\Video;

use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Laravel\Prompts\form;

class VideoController extends Controller
{
    public function search(Request $request)
    {

        $videos = Video::with(['channel'])->where('title', 'like', '%' . $request['q'] . '%')->get();


        if (Auth::check()) {
            $user = Auth::user();
            $channel = $user->channel;

            return view('video.search-result', [
                'videos' => $videos,
                'user' => $user,
                'channel' => $channel,
            ]);
        }

        return view('video.search-result', [
            'videos' => $videos
        ]);
    }

    public function subscribe(Request $request)
    {
        if (!Auth::user()) {
            return view("auth.login");
        }
        $userId = $request['id'];
        $user = User::find($userId);
        $currentUser = Auth::user();

        $channel = $user->channel;
        $channelId = $channel['id'];


        $alreadySubscribed = $currentUser->subscribedChannels()->where('channel_id', $channelId)->exists();
        if ($alreadySubscribed) {
            return back();
        }

        $currentUser->subscribedChannels()->attach($channelId);

        $channel->increment('subscribers');

        return back();


    }

    public function unsubscribe(Request $request)
    {
        $ownerID = $request['id'];
        $owner = User::find($ownerID);
        $channel = $owner->channel;

        $user = Auth::user();

        $user->subscribedChannels()->detach($channel['id']);

        $channel->decrement('subscribers');

        return back();
    }

    public function like(Request $request)
    {
        if (!Auth::user()) {
            return view('auth.login');
        }

        $user = Auth::user();

        $videoID = $request['id'];
        $video = Video::find($videoID);

        if ($user->likedVideos()->where('video_id', $videoID)->exists()) {
            $user->likedVideos()->detach($videoID);
            $video->decrement('likes');
            return back();
        }
        if($user->dislikedVideos()->where('video_id', $videoID)->exists()){
            $user->dislikedVideos()->detach($videoID);
            $video->decrement('dislikes');

            $user->likedVideos()->attach($videoID);
            $video->increment('likes');
            return back();
        }
        $user->likedVideos()->attach($videoID);
        $video->increment('likes');
        return back();

    }

    public function dislike(Request $request){
        if(!Auth::user()){
            return view('auth.login');
        }
        $user = Auth::user();
        $videoID = $request['id'];
        $video = Video::find($videoID);

        if($user->dislikedVideos()->where('video_id', $videoID)->exists()){
            $user->dislikedVideos()->detach($videoID);
            $video->decrement('dislikes');
            return back();
        }
        if($user->likedVideos()->where('video_id', $videoID)->exists()){
            $user->likedVideos()->detach($videoID);
            $video->decrement('likes');

            $user->dislikedVideos()->attach($videoID);
            $video->increment('dislikes');
            return back();
        }
        $user->dislikedVideos()->attach($videoID);
        $video->increment('dislikes');
        return back();
    }

    /**
     * Display a listing of the resource.
     */

    public function individual($id)
    {
        $video = Video::findOrFail($id);

        $video->increment('views');

        $formattedDate = Carbon::parse($video["created_at"])->format('Y-m-d');

        $videos = Video::query()->get();
        $channel = $video->channel;
        $channelId = $channel["id"];
        $ownerId = $channel["user_id"];


        if (Auth::user()) {
            $user = Auth::user();

            if ($user['id'] == $ownerId) {
                return view('video.individual-video', [
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'formattedDate' => $formattedDate,
                    'owner' => true,
                ]);
            }
            $subscribed = $user->subscribedChannels()->where("channel_id", $channelId)->exists();
            if ($subscribed) {
                return view('video.individual-video', [
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'formattedDate' => $formattedDate,
                    'subscribed' => true,
                ]);
            } else {
                return view('video.individual-video', [
                    'video' => $video,
                    'videos' => $videos,
                    'channel' => $channel,
                    'formattedDate' => $formattedDate,
                    'subscribed' => false,
                ]);
            }
        }


        return view('video.individual-video', [
            'video' => $video,
            'videos' => $videos,
            'channel' => $channel,
            'formattedDate' => $formattedDate,
            'subscribed' => false,
        ]);
    }

    public function index()
    {
        $videos = Video::query()->get();

        if (Auth::check()) {
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

        if ($user->channel !== null) {
            return view('video.create-video', [
                'channel' => true,
            ]);
        }
        return view('video.create-video', [
            'channel' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

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
                'ffmpeg.binaries' => '/opt/homebrew/bin/ffmpeg',
                'ffprobe.binaries' => '/opt/homebrew/bin/ffprobe',
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
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
            'dislikes' => 0,
            'views' => 0,
        ]);

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
