<div class="h-24 bg-gray-500 flex flex-row justify-between items-center px-10">
    <a href="/"><img src="{{asset('images/logo-MyTube.svg')}}" alt="logo" class="h-24"></a>

    <form action="/search" method="POST" class="w-[400px] border-2 border-black rounded-lg text-white flex flex-row justify-center items-center ">
        @csrf
        <input type="text" name="q" id="q" placeholder="Search for something" class="h-12 ml-7 mr-7
        placeholder-white bg-transparent placeholder:text-center w-[400px] outline-none border-none">
        <button type="submit" class="px-3 py-2 bg-black text-white rounded-lg mr-10 ">Search</button>
    </form>
</div>
