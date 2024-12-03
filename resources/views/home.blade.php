<x-layout>
    <h1 class="text-center text-red-500 text-2xl uppercase">test</h1>
    <div class=" mx-10 gap-4 my-10">
        @foreach($videos as $video)
            <div class=" justify-center items-center min-w-80 min-h-80 bg-green-700 text-center">

                <p class="m-4">{{$video['title']}}</p>
                <video controls width="300" class="">

                    <source src="{{asset('storage/' . $video['video'])}}" type="video/mp4"/>

                    Download the
                    <a href="{{'storage/'. asset($video['video'])}}">MP4</a>
                    video.
                </video>


            </div>
        @endforeach
    </div>
</x-layout>
