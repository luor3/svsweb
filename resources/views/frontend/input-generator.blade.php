<div>
    
    <div class="p-5 mt-10 mb-32 w-11/12 bg-blue-50 mx-auto rounded-2xl shadow-2xl overflow-hidden md:w-3/4">
        <h1 class="font-bold text-2xl mb-4">{{$inputInfo['name']}}</h1>
        <div class="my-10 bg-blue-600 h-1 mr-96"></div>
        @foreach($inputInfo['properties'] as $key => $property)
            @if($enableSeq[$key]['main'])
                <x-jet-label class="mt-5 mb-2 text-gray-700" for="{{ $key }}" value="{{ $property['title'] }}" />
                @if($property['type'] === "Select")
                    <select
                        class="block border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg" id="{{ $property['title'] }}"
                        wire:model="inputValue.{{$key}}.value">
                        <option value="null">Select the Value</option>
                        @foreach($property['options'] as $option)
                            <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                        @endforeach
                    </select> 
                @elseif($property['type'] !== "Multiple")
                    <input
                        class='block bg-white text-gray-700 disabled:opacity-20 placeholder-gray-400 shadow-md rounded-lg text-base appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                        type="text" wire:model="inputValue.{{$key}}.value" id="{{ $key }}" {{ (isset($property['required']) && $property['required'])? "required" : "" }}>
                @endif


                @for ($i = 0; $i < $childrenNum[$key]; $i++)
                    @foreach($property['children'] as $cKey => $cProperty)
                        @if($enableSeq[$key][$cKey.'-'.$i])
                            <x-jet-label class="mt-2 mb-2 text-gray-700" for="{{ $cProperty['title'] }}" value="{{ $cProperty['title'] }}" />
                            
                            @if($cProperty['type'] === "Select")
                                <select
                                    class="block border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full my-2 rounded-lg" id="{{ $property['title'] }}"
                                    wire:model="inputValue.{{$key}}.{{$cKey.'-'.$i}}" id="{{ $cProperty['title'] }}">
                                    <option value="null">Select the Value</option>
                                    @foreach($cProperty['options'] as $cOption)
                                        <option value="{{ $cOption['value'] }}">{{ $cOption['title'] }}</option>
                                    @endforeach
                                </select> 
                            @endif
                            @if($cProperty['type'] !== "Select" && isset($cProperty['type']))                                
                                <input
                                    wire:model="inputValue.{{$key}}.{{$cKey.'-'.$i}}"
                                    class='block disabled:opacity-20 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base appearance-none my-2 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                    type="text" id="{{ $cProperty['title']. '-'. $i }}" {{ (isset($cProperty['required']) && $cProperty['required'])? "required" : "" }}>                                                    
                            @endif
                        @endif
                    @endforeach
                    <div class="my-5 h-1"></div>
                @endfor



                <div class="my-10 bg-blue-200 h-1"></div>
            @endif
        @endforeach
    </div>
    <button wire:click="generateFile">Generate Input</button>
</div>


