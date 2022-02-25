<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')
        <div class="max-w-4xl flex flex-wrap items-center h-auto lg:h-screen  mx-auto my-32 lg:my-0">
            <div id="profile" class="w-full lg:w-3/5 rounded-lg lg:rounded-l-lg lg:rounded-r-none shadow-xl bg-white opacity-90 mx-6 lg:mx-0">

                <div class="p-4 md:p-12 text-center lg:text-left">
                    <!-- Image for mobile view-->
                    @if($user->profile_photo_path)
                    <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-48 w-48 bg-cover bg-center" style="background-image: url({{ asset(Storage::disk('local')->url($user->profile_photo_path)) }}"></div>
                    @else
                    <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-48 w-48 bg-cover bg-center" style="background-image: url('https://ui-avatars.com/api/?name={{$user->name}}&color=7F9CF5&background=EBF4FF')"></div>
                    @endif
                    <!--User's name and Information-->
                    <h1 class="text-3xl font-bold pt-8 lg:pt-0">{{$user -> name}}</h1>
                    <div class="mx-auto lg:mx-0 w-5/6 pt-3 border-b-2 border-indigo-500 opacity-25"></div>

                    <p class="pt-5 text-base">{{$user -> bio}}</p>

                    <div class="mt-8 pb-16 lg:pb-0 w-4/5 lg:w-full mx-auto flex flex-wrap items-center justify-around lg:justify-start">
                        <!-- Email -->
                        <a href="mailto:{{$user -> email}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-4 fill-current text-red-800 hover:text-red-500" viewBox="0 0 24 24">
                                <title>Email</title>
                                <path d="M12 12.713l-11.985-9.713h23.971l-11.986 9.713zm-5.425-1.822l-6.575-5.329v12.501l6.575-7.172zm10.85 0l6.575 7.172v-12.501l-6.575 5.329zm-1.557 1.261l-3.868 3.135-3.868-3.135-8.11 8.848h23.956l-8.11-8.848z"/>
                            </svg>
                        </a>
                        <!-- Linkedin -->
                        @if($user->linkedin)
                        <a href="{{$user->linkedin}}" target="_blank" rel="noreferrer noopener nofollow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-4 fill-current text-blue-600 hover:text-blue-500" viewBox="0 0 24 24">
                                <title>LinkedIn</title>
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-4 fill-current text-gray-600" viewBox="0 0 24 24">
                            <title>Not Available</title>
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        @endif

                        <!-- Github -->
                        @if($user->github)
                        <a href="{{$user->github}}" target="_blank" rel="noreferrer noopener nofollow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-4 fill-current text-gray-600 hover:text-indigo-900" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title>GitHub</title>
                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                            </svg>
                        </a>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-4 fill-current text-gray-600" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Not Available</title>
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                        @endif

                        
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/5">
                <!-- Big profile image for side bar (desktop) -->

                @if($user->profile_photo_path)
                <img class="rounded-none lg:rounded-lg shadow-2xl hidden lg:block"
                     src="{{ asset(Storage::disk('local')->url($user->profile_photo_path)) }}"
                     alt=""
                     >

                    @else
                    <img class="lg:w-80 rounded-none lg:rounded-lg shadow-2xl hidden lg:block"
                         src="https://ui-avatars.com/api/?name={{$user->name}}&color=7F9CF5&background=EBF4FF"
                         alt="{{$user->name}}"
                         >
                        @endif
            </div>
        </div>
    </div>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>
</x-guest-layout>
