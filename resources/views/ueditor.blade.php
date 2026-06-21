@php
    $name = $getName();
    $uploadUrl = $getUploadUrl();
    $placeholder = $getPlaceholder();
    $isConcealed = $isConcealed();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $options = $getOptions();
    // Create a safe identifier from statePath for use in JavaScript
    $editorId = str_replace(['.', '[', ']'], ['-', '-', ''], $statePath);
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper
        :valid="! $errors->has($statePath)"
    >
        <div wire:ignore>
            <div
                x-data="ueditorComponent({
        state: $wire.$entangle('{{ $getStatePath() }}'),
        editorId: 'ueditor-{{ $editorId }}',
        options: @js($getOptions())
    })"


            >
                <script type="text/plain"
                    id="ueditor-{{ $editorId }}"
                    name="{{ $name }}"
                    x-model="state"
                ></script>
            </div>
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
