<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')
        <div class="max-w-4xl flex items-center h-auto lg:h-screen flex-wrap mx-auto my-32 lg:my-0">
            <div id="profile" class="w-full lg:w-3/5 rounded-lg lg:rounded-l-lg lg:rounded-r-none shadow-2xl bg-white opacity-75 mx-6 lg:mx-0">


                <div class="p-4 md:p-12 text-center lg:text-left">
                    <!-- Image for mobile view-->
                    @if($user->profile_photo_path)
                    <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-48 w-48 bg-cover bg-center" style="background-image: url({{ asset(Storage::disk('local')->url($user->profile_photo_path)) }}"></div>
                    @else
                    <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-48 w-48 bg-cover bg-center" style="background-image: url('https://ui-avatars.com/api/?name={{$user->name}}&color=7F9CF5&background=EBF4FF')"></div>
                    @endif
                    <!-- Image for mobile view-->
                    <h1 class="text-3xl font-bold pt-8 lg:pt-0">{{$user -> name}}</h1>
                    <p>Email: {{$user -> email}}</p>
                    <p class="pt-8 text-sm"></p>

                    <div class="pt-12 pb-8">
                        <a href="mailto:{{$user -> email}}">
                            <button class="bg-blue-700 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-full">
                                Get In Touch
                            </button> 
                        </a>
                    </div>


                </div>
            </div>

            <div class="w-full lg:w-2/5">
                <!-- Big profile image for side bar (desktop) -->
                <div class="rounded-none lg:rounded-lg shadow-2xl hidden lg:block">
                    @if($user->profile_photo_path)
                    <img class="object-cover w-full"
                         src="{{ asset(Storage::disk('local')->url($user->profile_photo_path)) }}"
                         alt=""
                         style="width:400px"
                         >
                    @else
                    <img class="w-full"
                         src="https://ui-avatars.com/api/?name={{$user->name}}&color=7F9CF5&background=EBF4FF"
                         alt="{{$user->name}}"
                         style="width:430px"
                         >
                    @endif
                </div>

            </div>
        </div>

    </div>
    <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
        <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
    </div>
</div>
</x-guest-layout>
