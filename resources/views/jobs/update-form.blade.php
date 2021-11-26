        <x-jet-form-section submit="update">
            <x-slot name="title">
                {{'Unique ID: '.$job->id}}
            </x-slot>

            <x-slot name="description">
                {{ __('Description: ').$job->description}}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <x-jet-label class="text-2xl text-indigo-500" value="{{ $job->name }}" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="jobAttr.name" />
                    <x-jet-input-error for="jobAttr.name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="jobAttr.category_id" value="{{ __('Category') }}" />
                    <select
                        class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="jobAttr.category_id"
                        wire:model.defer="jobAttr.category_id">
                        <option>Select a Category</option>
                        @foreach($categories as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select> 
                    <x-jet-input-error for="jobAttr.category_id" class="mt-2" />
                </div>


                <div class="col-span-6">
                    <x-jet-label for="description" value="{{ __('Description') }}" />


                    <textarea
                        name="description"
                        class="tinymce mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" 
                        rows="6"
                        wire:model.defer="jobAttr.description">
                    </textarea>

                    
                    <x-jet-input-error for="jobAttr.description" class="mt-2" />
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

                    <x-jet-input-error for="jobAttr.status" class="mt-2" />
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
            </x-slot>
        </x-jet-form-section>

        <div>
           <x-jet-button class="ml-2" wire:click="clearDemo" wire:loading.attr="disabled">
                    {{ __('Back') }}
            </x-jet-button> 
        </div>   