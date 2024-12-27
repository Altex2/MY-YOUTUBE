<x-layout>
    <x-nav></x-nav>


    <div class="flex flex-row justify-center">
        <div class="flex flex-col">
            <div class="flex flex-col w-[900px] h-[450px] bg-gray-600  ml-10 mr-4 mt-10 rounded-lg pb-5">
                <!-- Video controls -->
                <video
                    controls
                    id="myVideo"
                    class="min-h-80 min-w-[400px] m-5  bg-black mt-4 rounded-lg"
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
                <div
                    class="mx-10 text-white font-bold text-lg">
                    <p>{{$video['title']}}</p>
                </div>

            </div>

            <div class="flex flex-row justify-between items-center text-white px-10 h-20 ml-10 mr-4 mt-6 rounded-lg bg-gray-500">
                <div class="flex flex-row justify-center items-center space-x-6">
                    <div class="flex flex-col justify-center items-center">
                        <p class="font-bold text-black">{{$channel->name}}</p>
                        <p>Subscribers: {{$channel->subscribers}}</p>
                    </div>
                    @if(isset($owner))
                        <a href="/" class="bg-green-500 px-5 py-3 rounded-full">Manage Channel</a>
                    @else
                        @if($subscribed)
                            <form action="/unsubscribe" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{$channel->user_id}}">
                                <button type="submit" class=" bg-gray-950 px-5 py-3 rounded-full">UNSUBSCRIBE</button>
                            </form>
                        @else
                            <form action="/subscribe" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{$channel->user_id}}">
                                <button type="submit" id="subscribe-button" class=" bg-red-500 px-5 py-3 rounded-full">SUBSCRIBE</button>
                            </form>
                        @endif
                    @endif

                </div>
                <div class="flex flex-row justify-center items-center text-black space-x-4">
                    <div class="">
                        <form action="/like" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{$video->id}}">
                            <button type="submit" class="bg-gray-300 rounded-full px-6 py-3"><i class="fa-solid fa-thumbs-up"></i> {{$video['likes']}} Likes</button>
                        </form>
                    </div>
                    <div class="">
                        <form action="/dislike" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{$video->id}}">
                            <button type="submit" class="bg-gray-300 rounded-full px-6 py-3"><i class="fa-solid fa-thumbs-down"></i> {{$video['dislikes']}} Dislikes</button>
                        </form>
                    </div>
                    <div class="">
                            <button id="share-btn" data-url="{{ url()->current() }}" class="bg-gray-300 rounded-full px-6 py-3"><i class="fa-solid fa-share"></i> Share</button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-gray-600 h-[500px] p-5 ml-10 mr-4 my-6 rounded-lg">
                <div class="flex flex-row justify-between items-center">
                    <h1 class="text-2xl text-white font-medium">Description</h1>
                    <div class="text-white text-sm mx-10">
                        <p>{{$video['views']}} views • {{$formattedDate}}</p>
                    </div>
                </div>
                <div class="flex flex-col border-2 border-black h-full text-white rounded-lg my-2 p-3">
                    <p>{{$video['description']}}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col w-[350px] min-h-[200px] bg-gray-600 text-white  mr-10 my-10 rounded-lg">
            @foreach($videos as $vid)
                <a href="/video/{{$vid['id']}}" class="m-5 p-2 h-52 bg-gray-500 rounded-lg">
                    <img
                        src="{{asset('storage/' . $vid['thumbnail'])}}"
                        alt="Thumbnail"
                        class="h-32 w-full object-contain bg-black rounded-t-lg"
                    />
                    <div class="flex flex-row justify-between items-center h-10 mx-4">
                        <h1 class="text-l font-bold">{{$vid['title']}}</h1>
                        <p>views</p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>

    <script>
            const shareButton = document.getElementById('share-btn');
            shareButton.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
                shareButton.classList.remove('bg-gray-300')
                shareButton.classList.add('bg-gray-600');
            navigator.clipboard.writeText(url).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
    </script>

    <script>

        window.onload = function () {
            const video = document.getElementById('myVideo');
            setTimeout(() => {
                video.play();
            }, 2000);
        }
    </script>
</x-layout>
