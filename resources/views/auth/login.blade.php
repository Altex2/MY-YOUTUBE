<x-layout>

    <div>
        <h1 class="text-center m-10 text-2xl font-bold">Login</h1>

        <!-- Disable autocomplete for the form -->
        <form action="/login" method="POST" autocomplete="off">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-6">

                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <!-- Disable autocomplete specifically for this field -->
                    <input type="email" name="email" id="email" autocomplete="off"
                           class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-amber-200 h-12"
                           value="{{old('email')}}">
                    @error('email')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="password">Password</label>
                    <!-- Disable autocomplete specifically for this field -->
                    <input type="password" name="password" id="password" autocomplete="off"
                           class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-amber-200 h-12">
                    @error('password')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-row space-x-6 justify-center items-center">
                    <button type="submit" class="px-6 py-4 bg-fuchsia-300 rounded-lg">LogIn</button>
                    <a href="/"  class="px-6 py-4 bg-red-300 rounded-lg">Cancel</a>
                </div>

            </div>

        </form>
    </div>
</x-layout>

