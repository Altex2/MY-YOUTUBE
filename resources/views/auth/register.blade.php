<x-layout>

    <div>
        <h1 class="text-center m-10 text-2xl font-bold">Create an account</h1>

        <!-- Disable autocomplete for the form -->
        <form action="/register" method="POST" autocomplete="off">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-6">
                <div class="flex flex-col">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name"
                           class=" pl-6 w-[400px] h-12 border-2 border-black rounded-lg bg-amber-200"
                           value="{{old('first_name')}}">
                    @error('first_name')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name"
                           class=" pl-6 w-[400px] h-12 border-2 border-black rounded-lg bg-amber-200"
                           value="{{old('last_name')}}">
                    @error('last_name')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
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
                    <input type="password" name="password" id="password" autocomplete="new-password"
                           class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-amber-200 h-12">
                    @error('password')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                           class="pl-6 w-[400px] border-2 border-black rounded-lg resize-none bg-amber-200 h-12">
                    @error('password')
                    <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex flex-row space-x-6 justify-center items-center">
                    <button type="submit" class="px-6 py-4 bg-fuchsia-300 rounded-lg">Create Account</button>
                    <a href="/"  class="px-6 py-4 bg-red-300 rounded-lg">Cancel</a>
                </div>

            </div>

        </form>
    </div>
</x-layout>

