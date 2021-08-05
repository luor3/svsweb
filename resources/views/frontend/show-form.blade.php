<div>
    <div class="col-span-6 sm:col-span-3">
        <x-jet-label class="mb-2 text-gray-700" for="searchCategory" value="{{ __('Select Demo Category') }}" />
        <select
            class="form-select block w-full mt-1 rounded-lg" id="searchCategory"
            wire:model="searchCategory">
                <option value="-1">All</option>
                @foreach($categories as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
        </select> 
    </div>
    @if(count($demos) == 0)
        <h1 class="mt-10 text-center font-bold uppercase">Sorry. No Demo Available for This Category</h1>
    @else
        @foreach($demos as $demo)
        <div class='mt-10 mb-32 w-11/12 bg-blue-50 mx-auto rounded-2xl shadow-2xl overflow-hidden md:w-1/2'>
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
                <button class="mt-5 mb-5 py-2 px-4 bg-blue-300 text-white font-semibold rounded-lg shadow-md hover:bg-red-500 focus:outline-none" wire:click="generateZip({{$demo->id}})" wire:loading.attr="disabled">
                    Generate Download Link
                </button>
                @if($displayLink == $demo->id)
                    <a class="block mt-2 mx-auto w-2/3 p-2 bg-green-300 text-white font-semibold rounded-lg shadow-md hover:bg-red-500 focus:outline-none" href=" {{ asset(Storage::disk('local')->url('demos'. '/' . $demo->id . '/' . 'inputs.zip')) }} ">Download Input Files</a>
                    <a class="block mt-2 mb-2 mx-auto w-2/3 p-2 bg-green-300 text-white font-semibold rounded-lg shadow-md hover:bg-red-500 focus:outline-none" href=" {{ asset(Storage::disk('local')->url('demos'. '/' . $demo->id . '/' . 'outputs.zip')) }} ">Download Output Files</a>
                @endif
            </div>
        </div> 
        @endforeach
        <div class="mt-3">
            {{$demos->links()}}
        </div>
    @endif
</div>