<div> 
    <div class="flex justify-around">
        <x-jet-nav-link href="{{ route('input-generator') }}" :active="request()->routeIs('input-generator')">
            {{ __('General Input Generator') }}
        </x-jet-nav-link>

        <x-jet-nav-link href="{{ route('xml-input-generator') }}" :active="request()->routeIs('xml-input-generator')">
            {{ __('XML Input Generator') }}
        </x-jet-nav-link>
    </div>

    <form wire:submit.prevent="generateFile">            
            <div class="p-5 mt-10 w-11/12 bg-blue-100 bg-opacity-75 mx-auto rounded-2xl shadow-2xl">
                <h1 class="font-bold text-2xl mb-4">{{$inputInfo['name']}}</h1>
                <div class="my-10 bg-blue-600 h-1"></div>
    
                <div class="grid grid-cols-12 gap-x-4">        
                    @foreach($propertyWindow as $key => $property)
                    <div class="text-center col-span-12 {{ isset($property['children']) ? 'md:place-self-center' : 'md:col-span-4'}}">
                        @if( (isset($enableSeq[$key]['main']) && $enableSeq[$key]['main']) || (!isset($enableSeq[$key]['main'])) ) 
                            <div x-data="{ open: false }">
                                <x-jet-label class="lg:h-10 inline-block mt-5 text-gray-700" for="{{ $key }}" value="{{ $property['title'] }}" />
                                @if(isset($property['hint']))
                                    <button type="button" @click="open = ! open">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                        
                                    <div class="mb-2 h-5 leading-5">
                                        <div :class="{ 'hidden': !open }" class="hidden" x-transition>
                                            <span class="text-red-500">
                                                {{ $property['hint'] }}
                                            </span> 
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($property['type'] === "select")
                                <select
                                    class="w-full border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                    wire:model="inputValue.{{$key}}.main" id="{{ $key }}">
                                    <option value={{null}}>Select the Value</option>
                                    @foreach($property['options'] as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                    @endforeach
                                </select> 
                            @elseif($property['type'] !== "multiple")
                                <input
                                    class='w-full bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                    type="{{ $property['htmlType'] }}" step="any" wire:model="inputValue.{{$key}}.main" id="{{ $key }}" {{ (isset($property['required']) && $property['required'])? "required" : "" }}>                      
                            @endif
                            <div class = "h-4">
                                <x-jet-input-error for="inputValue.{{$key}}.main" class="mt-2" />
                            </div>
    

                            @for ($i = 0; $i < ((isset($property['children'])) ? ( (isset($repeatNum[$key]['main'])) ? $repeatNum[$key]['main'] : 1) : 0); $i++)
                                <div class="mb-5 shadow-md rounded-lg bg-yellow-50 bg-opacity-25">
                                    @foreach($property['children'] as $cKey => $cProperty)
                                        @if( $cProperty['type'] === "linebreak")
                                            <div></div>
                                            @continue
                                        @endif 
                                        @if( (isset($enableSeq[$key][$cKey]) && $enableSeq[$key][$cKey]) || (!isset($enableSeq[$key][$cKey])) )
                                            <div class="p-2 {{ (isset($cProperty['display'])) ? $cProperty['display'] : '' }}">
                                                @for ($j = 0 ; $j < ((isset($cProperty['_repeat'])) ? $repeatNum[$key]['children'][$i][$cKey] : 1 ) ; $j++)
            
                                                    <div x-data="{ open: false }">
                                                        <x-jet-label class="inline-block mt-5 text-gray-700" for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" value="{{ $cProperty['title'] }}" />
                                                        @if(isset($cProperty['hint']))
                                                            <button type="button" class="inline-block" @click="open = ! open">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </button>
                                                            <div class="mb-2 h-5 leading-5">
                                                                <div :class="{ 'hidden': !open }" class="hidden" x-transition>
                                                                    <span class="text-red-500">
                                                                        {{ $cProperty['hint'] }}
                                                                    </span> 
                                                                </div>
                                                            </div>
                                                            
                                                        @endif
                                                    </div>

                                                    @if($cProperty['type'] === "select")
                                                        <select
                                                            class="w-1/2 border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                                            wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}">
                                                            <option value={{null}}>Select the Value</option>
                                                            @foreach($cProperty['options'] as $option)
                                                                <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                                            @endforeach
                                                        </select> 
                                                    @else
                                                        <input
                                                            class='w-1/2 bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                                            type="{{ $cProperty['htmlType'] }}" step="any" wire:model="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" id="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" {{ (isset($cProperty['required']) && $cProperty['required'])? "required" : "" }}>
                                                    @endif
                                                    <div class="h-4">
                                                        <x-jet-input-error for="inputValue.{{ $key }}.children.{{$i}}.{{$cKey}}{{ (isset($cProperty['_repeat']))? '.'.$j : '' }}" class="mt-2" />
                                                    </div>
                                                                                                                                                    
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
        <button type="submit" class="block mx-auto bg-purple-600 text-white font-semibold mt-5 py-2 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate Input File</button>
    </form>

    <div class="flex flex-wrap justify-center">
    @for($i = 0; $i < $totalSize/$windowSize; $i++)
        <a href="javascript: void(0)" wire:click="setCurrentWindow({{ $i }})" class="{{ $currentWindow === $i ? 'bg-blue-500':'bg-blue-300' }} mt-5 mx-2 bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded hover:bg-blue-500">
            {{ $i }}
        </a>
    @endfor
    </div>

</div>