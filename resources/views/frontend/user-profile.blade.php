<div class="flex-grow p-8 text-1xl">  
    <div class="rounded-lg flex justify-around mb-5 text-center">
        <a href="javascript: void(0)" wire:click="setPath('userprofile')" class="{{ $currentModule == 'userprofile' ? 'bg-blue-900 text-white':  'bg-blue-400'}} block px-3 py-2 w-1/4 rounded-xl text-gray-100 hover:bg-blue-900 hover:text-white">
            <span>Profile Setting</span>
        </a>
        <a href="javascript: void(0)" wire:click="setPath('jobs.create')" class="{{ $currentModule == 'jobs.create' ? 'bg-blue-900 text-white':  'bg-blue-400'}} block px-3 py-2 w-1/4 rounded-xl text-gray-100 hover:bg-blue-900 hover:text-white">
            <span>Job Submission</span>
        </a>
        <a href="javascript: void(0)" wire:click="setPath('jobs')" class="{{ $currentModule == 'jobs' ? 'bg-blue-900 text-white':  'bg-blue-400'}} block px-3 py-2 w-1/4 rounded-xl text-gray-100 hover:bg-blue-900 hover:text-white">
            <span>View Jobs</span>
        </a>
    </div>  
    
    <div class="lg:flex flex-wrap justify-around bg-gray-100">

        <div class="mx-auto self-start lg:ml-3 mt-3 w-3/4 lg:w-1/5">   
            <div class="bg-white p-3 border-t-4 border-green-400">
                <div class="image overflow-hidden">
                    <img class="h-auto w-full mx-auto"
                        src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                </div>
                <h1 class="text-gray-900 font-bold text-xl leading-8 my-1">{{ auth()->user()->name }}</h1>
                <h3 class="text-gray-600 font-lg text-semibold leading-6">{{ auth()->user()->email }}</h3>
                <p class="text-sm text-gray-500 hover:text-gray-600 leading-6">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                    Reprehenderit, eligendi dolorum sequi illum qui</p>
                <ul
                    class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                    <li class="flex items-center py-3">
                        <span>Status</span>
                        <span class="ml-auto"><span
                                class="bg-green-500 py-1 px-2 rounded text-white text-sm">Active</span></span>
                    </li>
                    <li class="flex items-center py-3">
                        <span>Member since</span>
                        <span class="text-right">{{ auth()->user()->created_at }}</span>
                    </li>
                    <li class="flex items-center py-3">
                        <span>Role</span>
                        <span class="ml-auto">{{ auth()->user()->role }}</span>
                    </li>
                </ul>
            </div>                    
    
            <div class="my-4"></div>

            <div class="bg-white p-3 hover:shadow">
                <div class="flex items-center space-x-3 font-semibold text-gray-900 text-xl leading-8">
                    <span class="text-green-500">
                        <svg class="h-5 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    <span>Similar Profiles</span>
                </div>
                <div class="grid grid-cols-3">
                    <div class="text-center my-2">
                        <img class="h-16 w-16 rounded-full mx-auto"
                            src="https://cdn.australianageingagenda.com.au/wp-content/uploads/2015/06/28085920/Phil-Beckett-2-e1435107243361.jpg"
                            alt="">
                        <a href="#" class="text-main-color">Kojstantin</a>
                    </div>
                    <div class="text-center my-2">
                        <img class="h-16 w-16 rounded-full mx-auto"
                            src="https://widgetwhats.com/app/uploads/2019/11/free-profile-photo-whatsapp-4.png"
                            alt="">
                        <a href="#" class="text-main-color">James</a>
                    </div>
                    <div class="text-center my-2">
                        <img class="h-16 w-16 rounded-full mx-auto"
                            src="https://lavinephotography.com.au/wp-content/uploads/2017/01/PROFILE-Photography-112.jpg"
                            alt="">
                        <a href="#" class="text-main-color">Natie</a>
                    </div>
                    <div class="text-center my-2">
                        <img class="h-16 w-16 rounded-full mx-auto"
                            src="https://bucketeer-e05bbc84-baa3-437e-9518-adb32be77984.s3.amazonaws.com/public/images/f04b52da-12f2-449f-b90c-5e4d5e2b1469_361x361.png"
                            alt="">
                        <a href="#" class="text-main-color">Casey</a>
                    </div>
                </div>
            </div>

            <div class="my-4"></div>
        </div>
            
        <div class="mx-auto p-3 lg:w-3/4 mt-3">   
            @if($currentModule == 'userprofile')
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
            @elseif($currentModule == 'jobs.create')
                @livewire('frontend.jobs.create-form')
            @elseif($currentModule == 'jobs')
                @livewire('frontend.jobs.show-form')
            @endif
        </div>
    </div>
</div>
