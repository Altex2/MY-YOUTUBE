<x-layout>
    <h1 class="text-center text-red-500 text-2xl uppercase">test</h1>
    <div class="flex justify-center items-center my-6">
        <a href="/videos/new" class="px-6 py-4 bg-blue-400 rounded-lg">New Video...</a>
    </div>
    <div class="grid grid-cols-4 mx-10 gap-6 my-10">
        @foreach($videos as $video)
{{--            <div class="flex flex-col min-w-64 min-h-64 bg-green-700 p-6">--}}

{{--                <p class="">{{$video['title']}}</p>--}}
{{--                <video controls class=" h-44 w-64 bg-black">--}}

{{--                    <source src="{{asset('storage/' . $video['video'])}}" type="video/mp4"/>--}}

{{--                    Download the--}}
{{--                    <a href="{{asset('storage/' . $video['video'])}}">MP4</a>--}}
{{--                    video.--}}
{{--                </video>--}}
{{--            </div>--}}
            <div class="flex flex-col min-w-64 min-h-64 bg-gray-900 p-2 rounded-lg shadow-lg">
                <!-- Thumbnail with text overlay -->
                <div class="relative">
                    <img
                        src="{{asset('storage/' . $video['thumbnail'])}}"
                        alt="Thumbnail"
                        class="h-44 w-full object-cover rounded-t-lg"
                    />
                    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 text-white font-bold text-lg">
                        <p>{{$video['title']}}</p>
                    </div>
                </div>

                <!-- Video controls -->
                <video
                    controls
                    class="h-44 w-full bg-black mt-4 rounded-lg"
                >
                    <source
                        src="{{asset('storage/' . $video['video'])}}"
                        type="video/mp4"
                    />
                    Download the
                    <a href="{{asset('storage/' . $video['video'])}}">MP4</a>
                    video.
                </video>

                <!-- Footer or description -->
                <div class="mt-4 text-white text-sm">
                    <p>457K views • 1 month ago</p>
                </div>
            </div>

        @endforeach
    </div>

    <script>
        const videos = document.querySelectorAll('.video');

        videos.forEach(video => {
            video.addEventListener('mouseenter', () => {
                video.play();
            });

            video.addEventListener('mouseleave', () => {
                video.pause();
                video.currentTime = 0;
            });
        });
    </script>
</x-layout>
