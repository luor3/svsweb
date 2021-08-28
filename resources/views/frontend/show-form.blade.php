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
        <div class="grid grid-cols-12 place-items-center gap-x-9">
            @foreach($demos as $demo)   
                <div class='col-span-12 md:col-span-6 mt-10 mb-32 bg-blue-50 mx-auto rounded-2xl shadow-2xl overflow-hidden w-full'>
                    <div class=''>
                        @if(isset($demo->plot_path))
                            <img class="h-full w-full rounded-lg object-cover transition duration-150 ease-in-out transform hover:scale-125"
                                src=" {{ asset(Storage::disk('local')->url($demo->plot_path)) }}"
                                alt="No Image">
                        @endif
                    </div>
                    <div class='relative bg-blue-50 text-center pt-2 text-base'>
                        <h1 class="font-bold uppercase">
                            {{ $demo->name }}
                        </h1>
                        <div>
                            {{ $demo->description }}
                        </div>

                        <div class="mx-auto">
                            <button class="mt-5 mb-5 py-2 px-4 bg-blue-300 text-white font-semibold rounded-lg shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , true)" wire:loading.attr="disabled">
                                Download Input Files
                            </button>

                            <button class="mt-5 mb-5 py-2 px-4 bg-blue-300 text-white font-semibold rounded-lg shadow-md hover:bg-red-500 focus:outline-none" wire:click="downloadFile( {{$demo->id}} , false)" wire:loading.attr="disabled">
                                Download Output Files
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
