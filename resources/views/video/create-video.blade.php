<x-layout>
    @if($channel != false)
        <div>
            <h1 class="text-center m-10 text-2xl font-bold">Upload a video</h1>


            <form action="/videos/new" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col justify-center items-center space-y-6">
                    <div class="flex flex-col">
                        <label for="title">Video Title</label>
                        <input type="text" name="title" id="title"
                               class=" pl-6 w-[400px] h-12 border-2 border-black rounded-lg bg-gray-800"
                               value="{{old('title')}}">
                        @error('title')
                        <p class="text-red-500 text-sm">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <label for="description">Video Description</label>
                        <textarea type="text" name="description" id="description"
                                  class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-gray-800 h-[300px]">{{old('description')}}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <label for="video_file">Video</label>
                        <div class="h-12 border-2 border-black rounded-lg flex flex-col justify-center items-center w-[400px]">
                            <input type="file" name="video_file" id="video_file" >

                        </div>
                        @error('video_file')
                        <p class="text-red-500 text-sm">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-row space-x-6 justify-center items-center">
                        <a href="/"  class="px-6 py-4 bg-red-400 rounded-lg">Cancel</a>
                        <button type="submit" class="px-6 py-4 bg-black rounded-lg">Upload Video</button>
                    </div>

                </div>


            </form>
        </div>
    @else
        <div class="flex flex-col justify-center items-center my-10 space-y-6">
            <h1 class="text-2xl text-red-500">You need to create a channel to post a video</h1>
            <a href="/channel/create" class="px-6 py-4 bg-black rounded-lg text-white">Create Channel</a>
        </div>
    @endif

</x-layout>
