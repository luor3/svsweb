<div>
    @if($next)
    <x-jet-form-section submit="add">
        <x-slot name="title">
            {{ __('Continued Job: ').(isset($job)?$job->name:'Null') }}
        </x-slot>

        <x-slot name="description">
            {{ __('File Upload Fields Generated by examing the input file, please upload all input file fields before submission') }}
        </x-slot>

        <x-slot name="form">
            @if(isset($input_files))
            @foreach($uploadFields as $fileType => $extension)
            <div class="col-span-6 sm:col-span-full">
                <x-jet-label class="mt-5" for="{{'input_files.'.$fileType}}"
                    value="{{  'Input '.$fileType.' File'  }}" />
                <x-jet-input id="{{'input_files.'.$fileType}}" type="file" class="mt-1 block w-full"
                    wire:model="input_files.{{$fileType}}" enctype='multipart/form-data' accept="{{'.'.$extension}}" />
                <div class="text-black-500" wire:loading wire:target="input_files.{{$fileType}}">Uploading...</div>
                <x-jet-input-error for="input_files.{{$fileType}}" class="mt-2" />
            </div>
            @endforeach
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="btn-submit-form-has-tinymce">

                {{ __('Create') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
    @else
    <x-form-section submit="registerJob">
        
        <x-slot name="title">
            {{ __('New Job') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Add a new job for submission. You can generate the .input file with our input generator') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label class="text-2xl text-indigo-500" value="{{ __('New Job') }}" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-jet-label for="category_id" value="{{ __('Category') }}" />
                <select
                    class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg"
                    id="category_id" wire:model.defer="category_id">
                    @foreach($categories as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="category_id" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />

                <textarea name="description"
                    class="mt-1 block w-full textarea border-gray-300 focus:ring fcus:ring-indigo-200 focus:ring-opacity-50 rounded-lg"
                    rows="6" wire:model.defer="description">o
                    </textarea>

                <x-jet-input-error for="description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-full">
                <x-jet-label for="input_file" value="{{ __('Input File') }}" />
                <x-jet-input id="input_file" type="file" class="mt-1 block w-full" wire:model="input_file"
                    enctype='multipart/form-data' accept='.input' required />
                <div class="text-black-500" wire:loading wire:target="input_file">Uploading...</div>
                <x-jet-input-error for="input_file" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-jet-label for="sshserver_id" value="{{ __('SSH Server') }}" />
                <select
                    class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg"
                    id="sshserver_id" wire:model.defer="sshserver_id">
                    @foreach($sshservers as $index=>$sshserver)
                    <option value="{{$sshserver->id}}" @if($index==0) selected @endif>
                        {{$sshserver->server_name}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="sshserver_id" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="btn-submit-form-has-tinymce">
                {{ __('Next') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
    @endif
</div>