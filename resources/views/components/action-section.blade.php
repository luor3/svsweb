<div class="" {{ $attributes }}>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>

    <div class="mt-10 md:mt-5">
        <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
            {{ $content }}
        </div>
    </div>
</div>
