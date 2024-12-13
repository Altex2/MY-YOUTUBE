<x-layout>
    <div class="h-24 bg-fuchsia-400 flex flex-row justify-between items-center px-10 border-b-2 border-black">
        <a href="/"><img src="{{asset('images/logo-MyTube.svg')}}" alt="logo" class="h-24"></a>

        <div class="w-[400px] border-2 border-black rounded-lg flex flex-row justify-center items-center ">
            <input type="text" placeholder="Search for something" class="h-12 ml-7 mr-7
        placeholder-black bg-transparent placeholder:text-center w-[400px] outline-none border-none">
            <button type="submit" class="px-3 py-2 bg-black text-white rounded-lg mr-10 ">Search</button>
        </div>

        <div class="flex flex-row justify-start items-center my-6 space-x-4 h-16 w-72">
            @guest
                <a href="/register" class="px-6 py-4 bg-black rounded-lg text-white">
                    Create Account
                </a>
                <a href="/login" class="px-6 py-4 bg-black rounded-lg text-white">
                    Login
                </a>
            @endguest
            @auth
                @if($channel != null)
                    <div class="flex flex-col">
                        <button id="active-channel-button"
                                class="relative  px-5 py-3 w-44 max-w-44 overflow-x-hidden rounded-xl bg-blue-600">{{$channel['name']}}</button>
                        <div class="hidden absolute translate-y-16 space-y-4" id="active-channel-div">
                            <a href="/videos/new" class="px-6 py-4 bg-black rounded-lg text-white">New Video...</a>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-4 bg-black rounded-lg text-white">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col">
                        <button id="active-channel-button"
                                class="relative  px-5 py-3 w-44 max-w-44 overflow-x-hidden rounded-xl bg-blue-600">No Channel!</button>
                        <div class="hidden absolute translate-y-16 space-y-4" id="active-channel-div">
                            <a href="/channel/create" class="px-6 py-4 bg-black rounded-lg text-white">New Channel...</a>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-4 bg-black rounded-lg text-white">Log Out</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>


    </div>


    @if($videos->all() != null)
        <div class="grid grid-cols-4 2xl:grid-cols-5 mx-10 gap-6 my-10">
            @foreach($videos as $video)
                <a href="/video/{{$video['id']}}"
                   class="flex flex-col  min-w-64 min-h-64 bg-gray-900 p-2 rounded-lg shadow-lg">
                    <div class="relative hover:brightness-50">
                        <img
                            src="{{asset('storage/' . $video['thumbnail'])}}"
                            alt="Thumbnail"
                            class="h-44 w-full object-contain bg-black rounded-t-lg"
                        />

                    </div>
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
    @else
        <div class="flex flex-col justify-center items-center  text-center mt-10 mb-6">
            <h1 class="text-2xl font-medium italic text-red-500">There are NO videos on the website</h1>
            <p class="text-cyan-500">Add a video right now :3</p>
        </div>
    @endif


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


        const no_channel = document.getElementById('no-channel-button');
        const no_channel_div = document.getElementById('no-channel-div');


        if (no_channel) {
            no_channel.addEventListener('click', () => {
                no_channel_div.classList.toggle('hidden');
            })
        }

        const active_channel = document.getElementById('active-channel-button');
        const active_channel_div = document.getElementById('active-channel-div');

        if (active_channel) {
            active_channel.addEventListener('click', () => {
                active_channel_div.classList.toggle('hidden');
            })
        }

    </script>
</x-layout>
