<x-layout>
    <x-nav></x-nav>


    <div class="flex flex-row justify-center">
        <div class="flex flex-col">
            <div class="flex flex-col w-[900px] h-[450px] bg-gray-600  ml-10 mr-4 mt-10 rounded-lg">
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
                <div class="text-white text-sm mx-10 mb-3">
                    <p>457K views • 1 month ago</p>
                </div>

            </div>

            <div class="flex flex-row justify-between items-center text-white pl-10 h-20 ml-10 mr-4 mt-6 rounded-lg bg-gray-500">
                <div class="flex flex-row justify-center items-center space-x-6">
                    <div class="flex flex-col justify-center items-center">
                        <p class="font-bold text-black">{{$channel->name}}</p>
                        <p>Subscribers: {{$channel->subscribers}}</p>
                    </div>
                    <form action="/subscribe" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{$channel->user_id}}">
                        <button type="submit" id="subscribe-button" class=" bg-red-500 px-5 py-3 rounded-full">SUBSCRIBE</button>
                    </form>
                </div>
            </div>

            <div class="flex flex-col bg-gray-600 h-[500px] p-5 ml-10 mr-4 my-6 rounded-lg">
                <h1 class="text-2xl text-white font-medium">Description</h1>
                <div class="flex flex-col border-2 border-black rounded-lg my-2 p-3">
                    <p>{{$video['description']}}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col w-[350px] min-h-[200px] bg-gray-600  mr-10 my-10 rounded-lg">
            @foreach($videos as $vid)
                <a href="/video/{{$vid['id']}}" class="m-5 p-2 h-44 bg-red-500 rounded-lg">
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
        const subscribeButton = document.getElementById("subscribe-button");

        subscribeButton.addEventListener('click', () =>{
            subscribeButton.classList.add('hidden')
        })

        window.onload = function () {
            const video = document.getElementById('myVideo');
            setTimeout(() => {
                video.play();
            }, 2000);
        }
    </script>
</x-layout>
