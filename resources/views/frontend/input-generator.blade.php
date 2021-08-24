<div>
    
    <form wire:submit.prevent="generateFile">            
            <div class="p-5 mt-10 w-11/12 bg-blue-50 mx-auto rounded-2xl shadow-2xl">
                <h1 class="font-bold text-2xl mb-4">{{$inputInfo['name']}}</h1>
                <div class="my-10 bg-blue-600 h-1 mr-96"></div>
    
                <div class="">        
                    <div class="">
                        @foreach($inputInfo['properties'] as $key => $property)
                            @if( (isset($enableSeq[$key]['main']) && $enableSeq[$key]['main']) || (!isset($enableSeq[$key]['main'])) ) 
                                <div x-data="{ open: false }">
                                    <x-jet-label class="inline-block mt-5 text-gray-700" for="{{ $key }}" value="{{ $property['title'] }}" />
                                    @if(isset($property['hint']))
                                        <button type="button" class="w-4 h-4 inline-block" @click="open = ! open">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                viewBox="0 0 422.686 422.686" style="enable-background:new 0 0 422.686 422.686;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path style="fill:#010002;" d="M211.343,422.686C94.812,422.686,0,327.882,0,211.343C0,94.812,94.812,0,211.343,0
                                                        s211.343,94.812,211.343,211.343C422.694,327.882,327.882,422.686,211.343,422.686z M211.343,16.257
                                                        c-107.565,0-195.086,87.52-195.086,195.086s87.52,195.086,195.086,195.086c107.574,0,195.086-87.52,195.086-195.086
                                                        S318.917,16.257,211.343,16.257z"/>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path style="fill:#010002;" d="M192.85,252.88l-0.569-7.397c-1.707-15.371,3.414-32.149,17.647-49.227
                                                            c12.811-15.078,19.923-26.182,19.923-38.985c0-14.51-9.112-24.182-27.044-24.467c-10.242,0-21.622,3.414-28.735,8.819
                                                            l-6.828-17.924c9.388-6.828,25.605-11.38,40.692-11.38c32.726,0,47.52,20.2,47.52,41.83c0,19.346-10.811,33.295-24.483,49.511
                                                            c-12.51,14.794-17.07,27.312-16.216,41.83l0.284,7.397H192.85V252.88z M186.583,292.718c0-10.526,7.121-17.923,17.078-17.923
                                                            c9.966,0,16.785,7.397,16.785,17.924c0,9.957-6.544,17.639-17.07,17.639C193.419,310.349,186.583,302.667,186.583,292.718z"/>
                                                    </g>
                                                </g>
                                            </g>
                                            </svg>
                                        </button>
                                            
                                        <div class="overflow-auto mb-2 h-5 leading-5">
                                            <div :class="{ 'hidden': !open }" class="hidden" x-transition>
                                                <span class="text-yellow-500">
                                                    {{ $property['hint'] }}
                                                </span> 
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($property['type'] === "select")
                                    <select
                                        class="border border-transparent disabled:opacity-20 focus:outline-none focus:ring-2 shadow-md focus:ring-blue-300 focus:border-transparent form-select block w-full mt-1 mb-5 rounded-lg"
                                        wire:model="inputValue.{{$key}}.main" id="{{ $key }}">
                                        <option value={{null}}>Select the Value</option>
                                        @foreach($property['options'] as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
                                        @endforeach
                                    </select> 
                                @elseif($property['type'] !== "multiple")
                                    <input
                                        class='bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
                                        type="{{ $property['htmlType'] }}" step="any" wire:model="inputValue.{{$key}}.main" id="{{ $key }}" {{ (isset($property['required']) && $property['required'])? "required" : "" }}>                      
                                @endif
                                <div class = "h-4">
                                    <x-jet-input-error for="inputValue.{{$key}}.main" class="mt-2" />
                                </div>
                                
        

                                @for ($i = 0; $i < ((isset($property['children'])) ? ( (isset($repeatNum[$key]['main'])) ? $repeatNum[$key]['main'] : 1) : 0); $i++)
                                    <div class="mb-5 shadow-md rounded-lg bg-green-50">
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
                                                                <button type="button" class="inline-block w-4 h-4" @click="open = ! open">
                                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        viewBox="0 0 422.686 422.686" style="enable-background:new 0 0 422.686 422.686;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path style="fill:#010002;" d="M211.343,422.686C94.812,422.686,0,327.882,0,211.343C0,94.812,94.812,0,211.343,0
                                                                                s211.343,94.812,211.343,211.343C422.694,327.882,327.882,422.686,211.343,422.686z M211.343,16.257
                                                                                c-107.565,0-195.086,87.52-195.086,195.086s87.52,195.086,195.086,195.086c107.574,0,195.086-87.52,195.086-195.086
                                                                                S318.917,16.257,211.343,16.257z"/>
                                                                        </g>
                                                                        <g>
                                                                            <g>
                                                                                <path style="fill:#010002;" d="M192.85,252.88l-0.569-7.397c-1.707-15.371,3.414-32.149,17.647-49.227
                                                                                    c12.811-15.078,19.923-26.182,19.923-38.985c0-14.51-9.112-24.182-27.044-24.467c-10.242,0-21.622,3.414-28.735,8.819
                                                                                    l-6.828-17.924c9.388-6.828,25.605-11.38,40.692-11.38c32.726,0,47.52,20.2,47.52,41.83c0,19.346-10.811,33.295-24.483,49.511
                                                                                    c-12.51,14.794-17.07,27.312-16.216,41.83l0.284,7.397H192.85V252.88z M186.583,292.718c0-10.526,7.121-17.923,17.078-17.923
                                                                                    c9.966,0,16.785,7.397,16.785,17.924c0,9.957-6.544,17.639-17.07,17.639C193.419,310.349,186.583,302.667,186.583,292.718z"/>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    </svg>
                                                                </button>
                                                                <div  class="overflow-auto mb-2 h-5 leading-5">
                                                                    <div  :class="{ 'hidden': !open }" class="hidden" x-transition>
                                                                        <span class="text-yellow-500">
                                                                            {{ $cProperty['hint'] }}
                                                                        </span> 
                                                                    </div>
                                                                </div>
                                                                
                                                            @endif
                                                        </div>

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
                                                                class='w-1/4 bg-white text-gray-700 disabled:opacity-20 shadow-md rounded-lg appearance-none mb-5 py-2 px-3 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent'
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
            
                                <div class="my-5 bg-blue-200 h-1"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div> 

        <button type="submit" wire:loading.remove class="block mx-auto bg-purple-600 text-white text-base font-semibold mt-5 py-2 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">Generate Input File</button>
    </form>

</div>