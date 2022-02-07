<div>
    <div class="">
        <x-jet-label class="mb-2 text-gray-700" for="searchCategory" value="{{ __('Select Demo Category') }}" />
        <select
            class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="searchCategory"
            wire:model="searchCategory">
                <option value="-1">All</option>
                @foreach($categories as $key => $value)
                    <option class="text-gray-700 block px-4 py-2 text-sm" value="{{$key}}">{{$value}}</option>
                @endforeach
        </select> 
    </div>


    <div x-data="{ gridView: true}" class="mt-4">
        <button @click="gridView = true" :class="gridView ? 'bg-gray-300':  'text-gray-700' " class="focus:outline-none inline-block px-1 py-1 rounded-xl hover:bg-gray-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </button>

        <button @click="gridView = false" :class="!gridView ? 'bg-gray-300':  'text-gray-700' " class="focus:outline-none inline-block px-1 py-1 rounded-xl hover:bg-gray-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
        </button>


        @if(count($demos) == 0)
            <h1 class="mt-10 text-center font-bold uppercase">Sorry. No Demo Available for This Category</h1>
        @else
            <div class="flex flex-wrap justify-center item-center" x-show="gridView">
                @foreach($demos as $demo)   
                    <div class='self-center w-11/12 md:w-2/5 mt-10 bg-white mx-auto rounded-2xl shadow-2xl overflow-hidden'>
                        <div>
                            @if(isset($demo->plot_path))
                                <img class="h-72 w-full rounded-lg object-cover transition duration-150 ease-in-out transform hover:scale-125"
                                    src=" {{ asset(Storage::disk('local')->url($demo->plot_path)) }}"
                                    alt="No Image">
                            @endif
                        </div>
                        <div class='relative bg-gray-200 text-center pt-2 text-base'>
                            
                            <div class="opacity-75">
                                <h1 class="font-bold uppercase">
                                    {{ $demo->name }}
                                </h1>
                                <span>
                                    {!! $demo->description !!}
                                </span>
                            </div>

                            <div class="mt-3 flex justify-around">
                                <button class="w-2/5 lg:w-1/4 mb-5 py-3 bg-black text-center text-white font-semibold rounded-xl shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , true)" wire:loading.attr="disabled">
                                    <span class="align-middle inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>  
                                    </span>
                                    
                                    <span class="align-middle inline-block">
                                        Input
                                    </span>
                                </button>

                                <button class="text-center w-2/5 lg:w-1/4 mb-5 py-3 bg-blue-500 text-white font-semibold rounded-xl shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , false)" wire:loading.attr="disabled">
                                    <span class="align-middle inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </span>
                                    
                                    <span class="align-middle inline-block">
                                        Output
                                    </span>
                                </button> 
                            </div>
                        </div>
                    </div>   
                @endforeach
            </div>

            <div class="flex flex-wrap justify-center item-center" x-show="!gridView">
                <div class="mx-auto p-5 antialiased">
                    <div x-data="{count : 0}">
                        @foreach($demos as $demo) 
                        <!-- start  -->
                        <div class="bg-gray-100 mx-auto border rounded-xl text-gray-700 mb-2 h-30">
                            <div class="flex p-3 border-l-8 border-green-600 rounded-xl">
                                <div class="space-y-1 border-r-2 pr-3">
                                <div class="text-sm leading-5 font-semibold"><span class="text-xs leading-4 font-normal text-gray-500"> Name #</span> {{ $demo->name }}</div>
                                <div class="text-sm leading-5 font-semibold"><span class="text-xs leading-4 font-normal text-gray-500"> ID #</span> {{ $demo->id }}</div>
                                <div class="text-sm leading-5 font-semibold">{{ $demo->updated_at }}</div>
                                </div>
                                <div class="flex-1 border-r-2">
                                <div class="ml-3 space-y-1">
                                    <div class="text-base leading-6 font-normal">Description</div>
                                    <div class="text-sm leading-4 font-normal"><span class="text-xs leading-4 font-normal text-gray-500">{{ $demo->description }}</span></div>
                                </div>
                                </div>


                                <div class="flex flex-col ml-3">
                                    
                                    <button class="p-1 bg-black text-center text-white font-semibold shadow-md hover:bg-red-500 rounded focus:outline-none" wire:click="downloadFile( {{$demo->id}} , true)" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>  
                                        Input
                                    </button>
      
                                    <button class="mt-1 p-1 bg-blue-500 text-white font-semibold shadow-md hover:bg-red-500 rounded focus:outline-none" wire:click="downloadFile( {{$demo->id}} , false)" wire:loading.attr="disabled">                                   
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>    
                                        Output                   
                                    </button> 
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{$demos->links()}}
            </div>
        @endif
    </div>
</div>
