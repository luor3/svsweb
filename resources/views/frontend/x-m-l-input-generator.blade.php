<div>
    <div class="flex justify-around">
        <x-jet-nav-link href="{{ route('input-generator') }}" :active="request()->routeIs('input-generator')">
            {{ __('General Input Generator') }}
        </x-jet-nav-link>

        <x-jet-nav-link href="{{ route('xml-input-generator') }}" :active="request()->routeIs('xml-input-generator')">
            {{ __('XML Input Generator') }}
        </x-jet-nav-link>
    </div>
    <div class="p-5 mt-10 w-11/12 bg-blue-100 bg-opacity-75 mx-auto rounded-2xl shadow-2xl">
        @foreach($this->iterator as $id => $my_value)
            @if($id == 'inputInfo')
                @continue
            @endif

            <div>
                <div style="margin-left: {{ $this->treeLevel[$id] }}00px; padding-right: 100px">
                    <x-jet-label class="block font-bold mt-5 text-gray-700" for="{{ $id }}" value="{{ $my_value['label'] }}" />

                    <div>
                        @if(isset($my_value['value']))
                            <x-jet-label class="inline-block mt-5 text-gray-700" for="{{ $id }}" value="{{ $my_value['label'] }}" />
                            <input
                                class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                wire:model="{{ $id. '.' . 'value' }}" id='{{ $id }}'>

                        @elseif(isset($my_value['attributes']))
                            @foreach($my_value['attributes'] as $attr => $attr_val)
                                <x-jet-label class="inline-block mt-5 text-gray-700" for="{{ $id }}" value="{{ $attr }}" />
                                <input
                                    class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                    wire:model="{{ $id. '.' . 'attributes' . '.' . $attr }}" id='{{ $id }}'>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        @endforeach

        <button wire:click="XMLGenerator()" type="submit" class="block mx-auto bg-purple-600 text-white font-semibold mt-5 py-2 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate XML File</button>
    </div>
</div>

