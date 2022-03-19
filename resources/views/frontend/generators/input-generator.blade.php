<div> 
    <form wire:submit.prevent="generateFile">            
        <div class="p-5 mt-10 w-10/12 mx-auto">
            <h1 class="font-bold text-gray-700 y-500 text-2xl mb-4">{{$inputInfo['name']}}</h1>

            <div class="grid grid-cols-12 gap-x-4">        
                @foreach($propertyWindow as $key => $property)
                <div class="{{ (isset($property['display'])) ? $property['display'] : '' }} col-span-12 {{ isset($property['children']) ? 'md:place-self-stretch' : 'md:col-span-6'}}">
                    @if( (isset($enableSeq[$key]['main']) && $enableSeq[$key]['main']) || (!isset($enableSeq[$key]['main'])) ) 
                    <div x-data="{ open: false }">
                        <x-jet-label class="md:h-16 lg:h-10 inline-block mt-5 text-gray-700 font-bold" for="{{ $key }}" value="{{ $property['title'] }}" />

                        @if(isset($property['hint']))
                        <button type="button" @click="open = ! open">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        @endif

                        @if($property['type'] === "select")
                        <select
                            class="w-full disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-indigo-300 focus:border-transparent form-select block w-full mb-5 rounded-lg align-center"
                            wire:model="inputValue.{{$key}}.main" id="{{ $key }}">
                            <option value={{null}}>Select the Value</option>
                            @foreach($property['options'] as $option)
                            <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                            @endforeach
                        </select> 
                        @elseif($property['type'] !== "multiple")
                        <input
                            class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent'
                            type="{{ $property['htmlType'] }}" step="any" wire:model="inputValue.{{$key}}.main" id="{{ $key }}" {{ (isset($property['required']) && $property['required'])? "required" : "" }}>                      
                            @endif

                            @if(isset($property['hint']))
                            <p :class="{ 'hidden': !open }" class="hidden text-sm text-red-500" x-transition>
                                {{ $property['hint'] }}
                            </p> 
                            @endif
                    </div>



                    <x-jet-input-error for="inputValue.{{$key}}.main" class="mt-2" />



                    @for ($i = 0; $i < ((isset($property['children'])) ? ( (isset($repeatNum[$key]['main'])) ? $repeatNum[$key]['main'] : 1) : 0); $i++)
                    <div class="grid grid-cols-3 gap-x-4">
                        @foreach($property['children'] as $cKey => $cProperty)
                        @if( $cProperty['type'] === "linebreak")

                        @continue
                        @endif 
                        @if( (isset($enableSeq[$key][$cKey]) && $enableSeq[$key][$cKey]) || (!isset($enableSeq[$key][$cKey])) )
                        <div class="{{ (isset($cProperty['display'])) ? $cProperty['display'] : '' }}">
                            @for ($j = 0 ; $j < ((isset($cProperty['_repeat'])) ? $repeatNum[$key]['children'][$i][$cKey] : 1 ) ; $j++)

                            <div x-data="{ open: false }">
                                <x-jet-label class="inline-block mt-5 text-gray-700 font-bold" for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" value="{{ $cProperty['title'] }}" />
                                @if(isset($cProperty['hint']))
                                <button type="button" @click="open = ! open">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                @endif

                                @if($cProperty['type'] === "select")
                                <select
                                    class="w-full border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-indigo-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                    wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}">
                                    <option value={{null}}>Select the Value</option>
                                    @foreach($cProperty['options'] as $option)
                                    <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                    @endforeach
                                </select> 
                                @else
                                <input
                                    class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent'
                                    type="{{ $cProperty['htmlType'] }}" step="any" wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" {{ (isset($cProperty['required']) && $cProperty['required'])? "required" : "" }}>
                                    @endif
                                    @if(isset($cProperty['hint']))
                                    <p :class="{ 'hidden': !open }" class="inline-block hidden  text-sm text-red-500" x-transition>
                                        {{ $cProperty['hint'] }}
                                    </p> 
                                    @endif
                            </div>

                            <x-jet-input-error for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" class="mt-2" />


                            @endfor 
                        </div>
                        @endif                                   
                        @endforeach
                    </div>
                    @endfor
                    @endif
                </div>
                @endforeach
            </div>

        </div> 
        <button type="submit" class="block mx-auto bg-red-600 text-white font-semibold mt-5 py-2 px-4 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate Input File</button>
    </form>

    <div class="flex flex-wrap justify-center">
        @for($i = 0; $i < $totalSize/$windowSize; $i++)
        <a href="javascript: void(0)" wire:click="setCurrentWindow({{ $i }})" class="{{ $currentWindow === $i ? 'bg-gray-600':'bg-indigo-800' }} mt-5 mx-2 bg-indigo text-white font-bold py-2 px-4 rounded hover:bg-red-600">
            {{ $i+1 }}
        </a>
        @endfor
    </div>

</div>