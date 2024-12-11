<x-layout>
    <div class="h-24 bg-fuchsia-400 flex flex-row justify-between items-center px-10">
        <a href="/"><img src="{{asset('images/logo-MyTube.svg')}}" alt="logo" class="h-24"></a>

        <div class="w-[400px] border-2 border-black rounded-lg flex flex-row justify-center items-center ">
            <input type="text" placeholder="Search for something" class="h-12 ml-7 mr-7
        placeholder-black bg-transparent placeholder:text-center w-[400px] outline-none border-none">
            <button type="submit" class="px-3 py-2 bg-black text-white rounded-lg mr-10 ">Search</button>
        </div>
    </div>
    <div>
        <h1 class="text-center m-10 text-2xl font-bold">Upload a video</h1>


        <form action="/videos/new" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-6">
                <div class="flex flex-col">
                    <label for="title">Video Title</label>
                    <input type="text" name="title" id="title"
                           class=" pl-6 w-[400px] h-12 border-2 border-black rounded-lg bg-amber-200"
                           value="{{old('title')}}">
                    @error('title')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="description">Video Description</label>
                    <textarea type="text" name="description" id="description"
                              class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-amber-200 h-[300px]">{{old('description')}}</textarea>
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

                <button type="submit" class="px-6 py-4 bg-fuchsia-300 rounded-lg">Upload Video</button>

            </div>


        </form>
    </div>
</x-layout>
