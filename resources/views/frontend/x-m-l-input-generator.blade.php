<div>
    <div class="px-5 w-5/6 m-auto flex">
        <ul>
            <li class="inline">
                <x-jet-nav-link href="{{ route('input-generator') }}" :active="request()->routeIs('input-generator')">
                    {{ __('General Input Generator') }}
                </x-jet-nav-link>
            </li>
            <li class="inline">
                <x-jet-nav-link href="{{ route('xml-input-generator') }}" :active="request()->routeIs('xml-input-generator')">
                    {{ __('XML Input Generator') }}
                </x-jet-nav-link>
            </li>
        </ul>
    </div>
    
    <div class="p-5 mt-10 w-10/12 mx-auto">
        <h1 class="font-bold text-gray-700 y-500 text-2xl mb-4">{{$inputInfo['name']}}</h1>

        @foreach($this->iterator as $id => $my_value)
        @if($id == 'inputInfo')
        @continue
        @endif

        <div class="grid grid-cols-12 gap-x-4 mt-5">
            <x-jet-label class="block text-base font-bold mt-5 text-gray-700" for="{{ $id }}" value="{{ $my_value['label'] }}" />

            <div class="col-end-13 col-span-10">
                @if(isset($my_value['value']))
                <x-jet-label class="inline-block mt-5 text-gray-700" for="{{ $id }}" value="{{ $my_value['label'] }}" />
                <input
                    class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent'
                    wire:model="{{ $id. '.' . 'value' }}" id='{{ $id }}'>

                @elseif(isset($my_value['attributes']))
                @foreach($my_value['attributes'] as $attr => $attr_val)
                <x-jet-label class="inline-block mt-5 text-gray-700" for="{{ $id }}" value="{{ $attr }}" />
                <input
                    class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent'
                    wire:model="{{ $id. '.' . 'attributes' . '.' . $attr }}" id='{{ $id }}'>
                @endforeach
                @endif
            </div>
        </div>


        @endforeach

        <button wire:click="XMLGenerator()" type="submit" class="block mx-auto bg-red-600 text-white font-semibold mt-5 py-2 px-4 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate XML File</button>
    </div>
</div>

