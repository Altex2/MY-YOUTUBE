<x-layout>
    <div>
        <h1 class="text-center m-10 text-2xl font-bold">Create a channel</h1>
        <form action="/channel/create" method="POST">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-6">
                <div class="flex flex-col">
                    <label for="name">Channel Name</label>
                    <input type="text" name="name" id="name"
                           class=" pl-6 w-[400px] h-12 border-2 border-black rounded-lg bg-amber-200"
                           value="{{old('name')}}">
                    @error('name')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <input type="hidden" name="subscribers" id="subscribers" value="0">

                <div class="flex flex-row space-x-6 justify-center items-center">
                    <button type="submit" class="px-6 py-4 bg-fuchsia-300 rounded-lg">Create Channel</button>
                    <a href="/" class="px-6 py-4 bg-red-300 rounded-lg">Cancel</a>
                </div>

            </div>


        </form>

    </div>
</x-layout>