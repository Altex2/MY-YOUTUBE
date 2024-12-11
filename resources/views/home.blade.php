<x-layout>
    <div class="h-24 bg-fuchsia-400 flex flex-row justify-between items-center px-10 border-b-2 border-black">
        <a href="/"><img src="{{asset('images/logo-MyTube.svg')}}" alt="logo" class="h-24"></a>

        <div class="w-[400px] border-2 border-black rounded-lg flex flex-row justify-center items-center ">
            <input type="text" placeholder="Search for something" class="h-12 ml-7 mr-7
        placeholder-black bg-transparent placeholder:text-center w-[400px] outline-none border-none">
            <button type="submit" class="px-3 py-2 bg-black text-white rounded-lg mr-10 ">Search</button>
        </div>
        <div class="flex justify-center items-center my-6">
            <a href="/videos/new" class="px-6 py-4 bg-black rounded-lg text-white">New Video...</a>
        </div>
    </div>


    <div class="grid grid-cols-4 2xl:grid-cols-5 mx-10 gap-6 my-10">
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

            <a href="/video/{{$video['id']}}" class="flex flex-col  min-w-64 min-h-64 bg-gray-900 p-2 rounded-lg shadow-lg">
                <!-- Thumbnail with text overlay -->
                <div class="relative hover:brightness-50">
                    <img
                        src="{{asset('storage/' . $video['thumbnail'])}}"
                        alt="Thumbnail"
                        class="h-44 w-full object-contain bg-black rounded-t-lg"
                    />

                </div>

                {{--                <!-- Video controls -->--}}
                {{--                <video--}}
                {{--                    controls--}}
                {{--                    class="h-44 w-full bg-black mt-4 rounded-lg"--}}
                {{--                >--}}
                {{--                    <source--}}
                {{--                        src="{{asset('storage/' . $video['video'])}}"--}}
                {{--                        type="video/mp4"--}}
                {{--                    />--}}
                {{--                    Download the--}}
                {{--                    <a href="{{asset('storage/' . $video['video'])}}">MP4</a>--}}
                {{--                    video.--}}
                {{--                </video>--}}

                <!-- Footer -->
                <div
                    class=" my-3 text-white font-bold text-lg">
                    <p>{{$video['title']}}</p>
                </div>
                <div class="text-white text-sm">
                    <p>457K views • 1 month ago</p>
                </div>
            </a>

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
