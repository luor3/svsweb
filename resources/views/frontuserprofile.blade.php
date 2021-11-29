<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')

        <div class="flex-grow p-8 text-1xl">  
            <div class="rounded-lg flex justify-around mb-5 text-center">
                <a href="{{ route('jobs.create') }}" class="bg-blue-400 block px-3 py-2 w-1/3 rounded-xl text-gray-100 hover:bg-blue-900 hover:text-white">
                    <span>Job Submission</span>
                </a>
                <a href="{{ route('jobs') }}" class="bg-blue-400 block px-3 py-2 w-1/3 rounded-xl text-gray-100 hover:bg-blue-900 hover:text-white">
                    <span>View Jobs</span>
                </a>
            </div>      
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif
            
            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

        </div>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>