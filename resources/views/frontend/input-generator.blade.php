<div>
    
    <form wire:submit.prevent="generateFile">
        <div class="px-4 py-5 bg-white sm:p-6 shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
            
            <div class="p-5 mt-10 mb-32 w-11/12 bg-blue-50 mx-auto rounded-2xl shadow-2xl overflow-hidden md:w-3/4">
                <h1 class="font-bold text-2xl mb-4">{{$inputInfo['name']}}</h1>
                <div class="my-10 bg-blue-600 h-1 mr-96"></div>
                    @foreach($inputInfo['properties'] as $key => $property)
                        @if( (isset($enableSeq[$key]['main']) && $enableSeq[$key]['main']) || (!isset($enableSeq[$key]['main'])) )
                            <x-jet-label class="mt-5 mb-2 text-gray-700" for="{{ $key }}" value="{{ $property['title'] }}" />
                            @if($property['type'] === "select")
                                <select
                                    class="block border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                    wire:model="inputValue.{{$key}}.main" id="{{ $key }}">
                                    <option value={{null}}>Select the Value</option>
                                    @foreach($property['options'] as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                    @endforeach
                                </select> 
                            @elseif($property['type'] !== "multiple")
                                <input
                                    class='block bg-white text-gray-700 disabled:opacity-20 placeholder-gray-400 shadow-md rounded-lg text-base appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                    type="{{ $property['htmlType'] }}" wire:model="inputValue.{{$key}}.main" id="{{ $key }}" {{ (isset($property['required']) && $property['required'])? "required" : "" }}>                      
                            @endif
                            <x-jet-input-error for="inputValue.{{$key}}.main" class="mt-2" />
     

                            @for ($i = 0; $i < ((isset($property['children'])) ? ( (isset($repeatNum[$key]['main'])) ? $repeatNum[$key]['main'] : 1) : 0); $i++)
                                <div class="mb-5 shadow-md rounded-lg bg-green-50">
                                    @foreach($property['children'] as $cKey => $cProperty)
                                        @if( (isset($enableSeq[$key][$cKey]) && $enableSeq[$key][$cKey]) || (!isset($enableSeq[$key][$cKey])) )
                                            <div class="p-2 {{ (isset($cProperty['display'])) ? $cProperty['display'] : '' }}">
                                                @for ($j = 0 ; $j < ((isset($cProperty['_repeat'])) ? $repeatNum[$key]['children'][$i][$cKey] : 1 ) ; $j++)
                                                    
                                                        <x-jet-label class="mt-5 mb-2 text-gray-700" for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" value="{{ $cProperty['title'] }}" />
                                                        @if($cProperty['type'] === "select")
                                                        <select
                                                            class="border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                                            wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}">
                                                            <option value={{null}}>Select the Value</option>
                                                            @foreach($cProperty['options'] as $option)
                                                                <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                                            @endforeach
                                                        </select> 
                                                        @else
                                                            <input
                                                                class='bg-white text-gray-700 disabled:opacity-20 placeholder-gray-400 shadow-md rounded-lg text-base appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                                                type="{{ $cProperty['htmlType'] }}" wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" {{ (isset($cProperty['required']) && $cProperty['required'])? "required" : "" }}>
                                                        @endif
                                                        <x-jet-input-error for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" class="mt-2" />                                                                                               
                                                @endfor 
                                            </div>
                                        @endif                                   
                                    @endforeach
                                </div>
                            @endfor
         
                            <div class="my-5 bg-blue-200 h-1"></div>
                        @endif
                    @endforeach
            </div> 
        </div>

        <div class="flex items-center justify-end bg-white px-4 py-3 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <button type="submit" class="mx-auto bg-purple-600 text-white text-base font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate Input File</button>
        </div>
    </form>

</div>