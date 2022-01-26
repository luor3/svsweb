<div>
    @if(!isset($demo) || $demoID === -1)
        @include('demos.filterBar')
        <table class="min-w-full leading-normal text-center">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Demo ID
                        @include('backend-order-bar', ['columnName' => 'id'] )
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Name
                        @include('backend-order-bar', ['columnName' => 'name'] )
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Category 
                        @include('backend-order-bar', ['columnName' => 'category_name'] )
                    </th>
                        
                    <th
                        class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Display 
                        @include('backend-order-bar', ['columnName' => 'status'] )
                    </th>
                        
                    <th
                        class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($demos as $myDemo)
                    <tr class="bg-white">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $myDemo->id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $myDemo->name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $myDemo->category_name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="{{ $myDemo->status?  'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs': 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs' }} py-1 px-3 rounded-full text-xs">
                                {{$myDemo->status?'Yes':'No' }}
                            </span>
                        </td>

                        <td class="px-5 py-5 border-b border-gray-200 text-sm">


                            <div class="flex justify-center">

                                @if(auth()->user()->hasPermission(2))
                                <button class="inline-flex items-center px-1 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition" title="Edit Demo" wire:click="redirecToDemo( {{ $myDemo->id }} )" wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @endif

                                @if(auth()->user()->hasPermission(3))
                                <button class="ml-2 inline-flex items-center px-1 py-1 border border-red-300 rounded-md bg-red-500 font-semibold text-xs text-white tracking-widest shadow-sm hover:bg-red-300 focus:outline-none focus:ring disabled:opacity-25 transition" title="Delete This Demo" wire:click="registerDemo({{ $myDemo->id }},true)"
                                    wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
           {{ $demos->links() }} 
        </div>

        <!-- Delete Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingDemoDeletion">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Delete Demo') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete demo: ') }} <strong>{{ isset($demo) ? $demo->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingDemoDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete Demo') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

    @else
        <x-jet-form-section submit="update">
            <x-slot name="title">
                {{'Unique ID: '.$demo->id}}
            </x-slot>

            <x-slot name="description">
                {{ __('Description: ').$demo->description}}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <x-jet-label class="text-2xl text-indigo-500" value="{{ $demo->name }}" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="demoAttr.name" />
                    <x-jet-input-error for="demoAttr.name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="demoAttr.category_id" value="{{ __('Category') }}" />
                    <select
                        class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="demoAttr.category_id"
                        wire:model.defer="demoAttr.category_id">
                        <option>Select a Category</option>
                        @foreach($categories as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select> 
                    <x-jet-input-error for="demoAttr.category_id" class="mt-2" />
                </div>


                <div class="col-span-6">
                    <x-jet-label for="description" value="{{ __('Description') }}" />


                    <textarea
                        name="description"
                        class="tinymce mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" 
                        rows="6"
                        wire:model.defer="demoAttr.description">
                    </textarea>

                    
                    <x-jet-input-error for="demoAttr.description" class="mt-2" />
                </div>

                


                @foreach($uploadFields as $fileType => $extension)
                    <div class="col-span-6 sm:col-span-full">
                        <x-jet-label class="mt-5" for="{{'inputFiles.'.$fileType}}" value="{{  'Input '.$fileType.' File'  }}" />
                        <x-jet-input id="{{'inputFiles.'.$fileType}}" type="file" class="mt-1 block w-full" wire:model="inputFiles.{{$fileType}}" enctype='multipart/form-data' accept="{{'.'.$extension}}"/>
                        <div class="text-black-500" wire:loading wire:target="inputFiles.{{$fileType}}">Uploading...</div>
                        <x-jet-input-error for="inputFiles.{{$fileType}}" class="mt-2" />

                        @if(isset($inputFileJson['fileName'][$fileType]))
                            <x-jet-label value="{{ $inputFileJson['fileName'][$fileType] }}" />
                        @endif
                    </div>
                @endforeach


                <div class="col-span-6 sm:col-span-full">
                    <x-jet-label class="mt-5" for="outputFiles" value="{{ __('Output Files (select multiple files)') }}" />
                    <x-jet-input id="outputFiles" type="file" class="mt-1 block w-full" wire:model="outputFiles" enctype='multipart/form-data' multiple/>
                    <div class="text-black-500" wire:loading wire:target="outputFiles">Uploading Multiple Files...</div>
                    <x-jet-input-error for="outputFiles.*" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-full">
                    @foreach($outputFileJson['fileName'] as $outputFileName)
                        <x-jet-label value="{{ substr($outputFileName, 36) }}" /> 
                    @endforeach
                </div>


                <div class="col-span-6 sm:col-span-full">
                    <x-jet-label class="mt-5" for="plotFile" value="{{ __('Plot Image File (max:10MB)') }}" />
                    <x-jet-input id="plotFile" type="file" class="mt-1 block w-full" wire:model="plotFile" enctype='multipart/form-data' accept='.jpg,.jpeg,.png,.gif'/>
                    <div class="text-black-500" wire:loading wire:target="plotFile">Uploading...</div>
                    <x-jet-input-error for="plotFile" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-full">
                    @if(isset($plotFile))
                        <x-jet-label class="my-5" for="plot_image" value="{{ __('Uploaded Image: ').$plotFile->getClientOriginalName() }}" />
                        <img id="plot_image" src="{{ $plotFile->temporaryUrl() }}" alt="Something Wrong to Display Image">
                        
                    @elseif($demo->plot_path)
                        <x-jet-label class="my-5" for="plot_image" value="{{ __('Plot Image: ') }}" />
                        <img id="plot_image" src="{{ asset(Storage::disk('local')->url($demo->plot_path)) }}" alt="Something Wrong to Display Image">
                    @endif
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="fld-status" value="{{ __('Display') }}" />
                    <select id="fld-status" wire:model.defer="demoAttr.status" {{ $displayEditable? "" : "disabled" }} class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                        <option value="">Select display status</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>

                    <x-jet-input-error for="demoAttr.status" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button class="btn-submit-form-has-tinymce">
                    <svg 
                        viewBox="0 0 20 20" 
                        fill="currentColor" 
                        class="w-4 h-4 mr-1">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg> 
                    {{ __('Save') }}
                </x-jet-button>
                <div style="text-align: left">
                    <x-jet-button class="ml-2" onclick="event.preventDefault();" wire:click="clearDemo" wire:loading.attr="disabled">
                            {{ __('Back') }}
                    </x-jet-button> 
                </div> 

            </x-slot>
        </x-jet-form-section> 
    @endif
</div>



















