<div>
    @if( $jobID === -1)
        <div class="mx-auto overflow-auto">
            @include('frontend.jobs.filterBar')
            <table class="text-center min-w-full leading-normal border-4">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID 
                            @include('backend-order-bar', ['columnName' => 'id'] )
                        </th>

                        @if($permission == 1)
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            User 
                            @include('backend-order-bar', ['columnName' => 'user'] )
                        </th>
                        @endif

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                            @include('backend-order-bar', ['columnName' => 'name'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Category
                            @include('backend-order-bar', ['columnName' => 'category_id'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Progress
                            @include('backend-order-bar', ['columnName' => 'progress'] )
                        </th>
                            
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Submission Date 
                            @include('backend-order-bar', ['columnName' => 'created_at'] )
                            
                        </th>

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                            @include('backend-order-bar', ['columnName' => 'status'] )
                        </th>

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $myJob)
                        <tr>                    
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$myJob->id}}</td>
                            @if($permission == 1)
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$myJob->user_name}}</td>
                            @endif
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$myJob->name}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$this->categories[$myJob->category_id]}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$myJob->progress}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$myJob->created_at}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="{{ $myJob->status?  'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs': 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs' }} py-1 px-3 rounded-full text-xs">
                                    {{$myJob->status?'Yes':'No' }}
                                </span>
                            </td>
                            <td class="flex justify-around px-5 py-5 border-b border-gray-200 bg-white text-sm">

                                <x-jet-secondary-button title="Edit" wire:click="redirecToJob( {{ $myJob->id }} )" wire:loading.attr="disabled">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    
                                </x-jet-secondary-button>

                                @if($myJob->progress === 'Pending' || $myJob->progress === 'Cancelled' || $myJob->progress === 'In Progress' )
                                    <x-jet-secondary-button title="{{ $myJob->progress === 'Pending'|| $myJob->progress === 'In Progress'? 'Withdraw Job' : 'Recover Job' }}" class="{{ $myJob->progress === 'Pending'|| $myJob->progress === 'In Progress'?  'bg-red-200 hover:border-red-600 hover:bg-red-500':'bg-green-200 hover:border-green-600 hover:bg-green-500'}} text-gray-800 font-bold rounded hover:text-white py-1 px-2 inline-flex items-center" wire:click="withdrawJob( {{ $myJob->id }} )" wire:loading.attr="disabled">
                                        @if($myJob->progress === 'Pending' || $myJob->progress === 'In Progress')
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="20" height="20" viewBox="0 0 20 20">
                                                <path fill="currentcolor" 
                                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                            </svg>                     
                                        
                                        @elseif($myJob->progress === 'Cancelled')                                            
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="20" height="20" viewBox="0 0 20 20">
                                                    <path style="fill:#030104;" d="M12.229,0.003c-0.01,0-0.014,0-0.018,0c-0.008,0-0.008,0-0.01,0
                                                        c0,0,0,0-0.003,0l0,0c-0.003,0-0.007,0-0.011,0c-0.002,0-0.002,0-0.002,0s0,0-0.004,0c-0.027-0.006-0.035-0.002-0.055-0.002
                                                        c-3.203,0-6.319,1.27-8.622,3.503L0.972,0.933c-0.123-0.12-0.309-0.158-0.461-0.094c-0.159,0.068-0.265,0.22-0.265,0.394v8.348
                                                        c0,0.235,0.191,0.425,0.423,0.425h8.246c0.005,0,0.014,0,0.02,0c0.234,0,0.424-0.189,0.424-0.425c0-0.156-0.085-0.294-0.212-0.367
                                                        L6.646,6.683c1.483-1.432,3.418-2.216,5.54-2.216c4.33,0.028,7.857,3.573,7.857,7.975c-0.033,4.326-3.58,7.847-7.98,7.847
                                                        l0.01,4.468h0.061c6.779,0,12.333-5.518,12.377-12.376C24.511,5.606,19.007,0.06,12.229,0.003z"/>
                                            </svg>
                                             
                                        @endif                                      
                                    </x-jet-secondary-button>
                                @endif

                                <x-jet-danger-button title="Delete This Job" wire:click="registerJob({{$myJob->id }},true)"
                                    wire:loading.attr="disabled">
                                    <svg fill="currentColor" width="20" height="20" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                   
                                </x-jet-danger-button>

                                @if($myJob->progress === 'Completed')
                                <x-jet-button class="ml-2" wire:click="downloadFile({{$myJob->id }}, false)"
                                    wire:loading.attr="disabled"
                                    style="background-color:green" >
                                    {{ __('Downloads') }}
                                </x-jet-button>
                                @else
                                <x-jet-button class="ml-2"
                                        wire:loading.attr="disabled"
                                        style="background-color:gray" disabled>
                                        {{ __('Downloads') }}
                                    </x-jet-button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $jobs->links() }} 
            </div>
        </div> 
            
        <!-- Delete Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobDeletion">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Delete Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        <!-- Withdraw Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobWithdraw">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Withdraw Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to withdraw job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobWithdraw')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="withdraw()" wire:loading.attr="disabled">
                    {{ __('Withdraw Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        <!-- Recover Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobRecover">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Recover Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to recover job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobRecover')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="recover()" wire:loading.attr="disabled">
                    {{ __('Recover Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        </div>
    </div>
    @else

        <x-jet-form-section submit="update">
        <x-slot name="title">
            {{'Job Unique ID: '.$job->id}}
        </x-slot>

        <x-slot name="description">
            {{ __('Job Description: ').$job->description}}
            <br/>
            {{ __('Please Change the job submission here: ')}}
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
                <div class="col-span-6 sm:col-span-full" style="text-align: left">
                    <x-jet-label class="mt-5" for="{{'inputFiles.'.$fileType}}" value="{{  'Input '.$fileType.' File'  }}" />
                    <x-jet-input id="{{'inputFiles.'.$fileType}}" type="file" class="mt-1 block w-full" wire:model="inputFiles.{{$fileType}}" enctype='multipart/form-data' accept="{{'.'.$extension}}"/>
                    <div class="text-black-500" wire:loading wire:target="inputFiles.{{$fileType}}">Uploading...</div>
                    <x-jet-input-error for="inputFiles.{{$fileType}}" class="mt-2" />

                    @if(isset($inputFileJson['fileName'][$fileType]))
                        <x-jet-label value="{{ $inputFileJson['fileName'][$fileType] }}" />
                    @endif
                </div>
            @endforeach

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
                <x-jet-button class="ml-2" onclick="event.preventDefault();" wire:click="clearJob" wire:loading.attr="disabled">
                        {{ __('Back') }}
                </x-jet-button> 
            </div> 
        </x-slot>
    </x-jet-form-section>

    @endif
</div>
