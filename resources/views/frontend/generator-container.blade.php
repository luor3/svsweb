<div>

    <div class="px-5 w-5/6 m-auto flex">    
        <ul>
            <li class="inline">
                <a href="javascript: void(0)" wire:click="setPath('input')" class="{{ $currentModule == 'input' ? 'bg-indigo-900 text-white':  'text-gray-700'}} px-3 py-2 hover:bg-indigo-500 hover:text-white">
                    {{ __('General Input Generator') }}
                </a>
            </li>
            <li class="inline">
                <a href="javascript: void(0)" wire:click="setPath('conf')" class="{{ $currentModule == 'conf' ? 'bg-indigo-900 text-white':  'text-gray-700'}} px-3 py-2 hover:bg-indigo-500 hover:text-white">
                    {{ __('Conf Input Generator') }}
                </a>
            </li>
            <li class="inline">
                <a href="javascript: void(0)" wire:click="setPath('xml')" class="{{ $currentModule == 'xml' ? 'bg-indigo-900 text-white':  'text-gray-700'}} px-3 py-2 hover:bg-indigo-500 hover:text-white">
                    {{ __('XML Input Generator') }}
                </a>
            </li>
        </ul>
    </div>

    @if($currentModule == 'input')
        @livewire('frontend.generators.input-generator', ['templateFilename' => 'input-template.json'])
    @elseif($currentModule == 'conf')
        @livewire('frontend.generators.input-generator', ['templateFilename' => 'input-conf-template.json'])
    @elseif($currentModule == 'xml')
        @livewire('frontend.generators.x-m-l-input-generator')
    @endif

    <x-loading-indicator />

</div>
