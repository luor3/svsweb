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
    @if(count($demos) == 0)
        <h1 class="mt-10 text-center font-bold uppercase">Sorry. No Demo Available for This Category</h1>
    @else
        <div class="flex flex-wrap justify-center item-center">
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
                            <button class="w-2/5 lg:w-1/4 mb-5 py-3 bg-black text-center text-white font-semibold rounded-full shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , true)" wire:loading.attr="disabled">
                                <span class="align-middle inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>  
                                </span>
                                
                                <span class="align-middle inline-block">
                                    Input
                                </span>
                            </button>

                            <button class="text-center w-2/5 lg:w-1/4 mb-5 py-3 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , false)" wire:loading.attr="disabled">
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
        <div class="mt-3">
            {{$demos->links()}}
        </div>
    @endif
</div>
