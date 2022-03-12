<div>

    <x-slot name="title">
        {{ __('New Job') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add a new job for submission. You can generate the .input file with our input generator') }}
    </x-slot>
    @if(!$next)
    <x-form-section submit="registerJob">


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
                <select class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="category_id" wire:model.defer="category_id">
                    @foreach($categories as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="category_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="sshserver_id" value="{{ __('SSH Server') }}" />

                <select class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="sshserver_id" wire:model="sshserver_id">
                    @foreach($sshservers as $index=>$sshserver)
                    <option value="{{$sshserver['sshserver']['id']}}" @if($index==0 ) selected @endif>
                        {{$sshserver['sshserver']['server_name']}} [CPU usage: {{$sshserver['cpu']}}] [Memory usage: {{$sshserver['memory']}}]
                    </option>
                    @endforeach
                    <option value="custom">custom server</option>
                </select>
                <x-jet-input-error for="sshserver_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="jobs_solvers" value="{{ __('Solver') }}" />
                <select class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="jobs_solvers" wire:model.defer="jobs_solvers">
                    @foreach($solvers as $index=>$solver)
                    <option value="{{$solver->id}}" @if($index==0) selected @endif>{{$solver->name}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="category_id" class="mt-2" />
            </div>
            @if(!strcmp($sshserver_id,"custom"))
            <div class="col-span-6 sm:col-span-6" id="sshserver-form">
                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="server_name" value="{{ __('Server_name') }}" />
                    <x-jet-input id="server_name" type="text" class="mt-1 block w-full" wire:model.defer="server_name" autofocus />
                    <x-jet-input-error for="server_name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="host" value="{{ __('Host') }}" />
                    <x-jet-input id="host" type="text" class="mt-1 block w-full" wire:model.defer="host" />
                    <x-jet-input-error for="host" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="port" value="{{ __('Port') }}" />
                    <x-jet-input id="port" type="text" class="mt-1 block w-full" wire:model.defer="port" />
                    <x-jet-input-error for="port" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="username" value="{{ __('Username') }}" />
                    <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model.defer="username" />
                    <x-jet-input-error for="username" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" type="text" class="mt-1 block w-full" wire:model.defer="password" />
                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </div>
            @endif
            <div class="col-span-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />

                <textarea name="description" class="mt-1 block w-full textarea border-gray-300 focus:ring fcus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" rows="6" wire:model.defer="description">o
                </textarea>

                <x-jet-input-error for="description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-full">
                <x-jet-label for="input_file" value="{{ __('Input File') }}" />
                @if($jobs_solvers == 1)
                <x-jet-input id="input_file" type="file" class="mt-1 block w-full" wire:model="input_file" enctype='multipart/form-data' accept='.conf' required />
                @else
                <x-jet-input id="input_file" type="file" class="mt-1 block w-full" wire:model="input_file" enctype='multipart/form-data' accept='.input' required />
                @endif
                <div class="text-black-500" wire:loading wire:target="input_file">Uploading...</div>
                <x-jet-input-error for="input_file" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="btn-submit-form-has-tinymce">
                {{ __('Next') }}
            </x-jet-button>
        </x-slot>


    </x-form-section>
    @else($next)
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
                    <x-jet-label class="mt-5" for="{{'input_files.'.$fileType}}" value="{{  'Input '.$fileType.' File'  }}" />
                    <x-jet-input id="{{'input_files.'.$fileType}}" type="file" class="mt-1 block w-full" wire:model="input_files.{{$fileType}}" enctype='multipart/form-data' accept="{{'.'.$extension}}" />
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

    @endif

    <x-jet-confirmation-modal wire:model="confirmingJobDeletion">
        <x-slot name="title">
            <span class="font-bold uppercase">
                {{ __('Warning') }}
            </span>
        </x-slot>

        <x-slot name="content">
            {{ __('Connection failed ') }}
        </x-slot>

        <x-slot name="footer">

            <x-jet-danger-button class="ml-2" wire:click="updateConfirmingJobDeletion()" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>

<script>
    var input_file = document.getElementById("input_file");
    var jobs_solvers = document.getElementById("jobs_solvers");
    jobs_solvers.addEventListener("change", function(event) {
        if (jobs_solvers.value == 1) {
            input_file.setAttribute("accept", ".conf");
        } else {
            input_file.setAttribute("accept", ".input");
        }

    })
</script>