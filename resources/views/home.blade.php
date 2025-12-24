<x-layouts.main>

@php
    $layouts = ['full-width', 'sidebar'];
    
    $layoutHtml = [];
    foreach ($layouts as $layout) {
        $layoutHtml[$layout] = trim(view("layouts.{$layout}")->render());
    }

@endphp

    <script>
    window.layout = @json($layoutHtml['full-width']);
    </script>

    <div
        x-data="{ isAdvancedOpen: false }"
     class="flex items-stretch justify-stretch h-screen w-screen bg-neutral-900 text-stone-400">
        {{-- Main Builder Area --}}
        <div class="flex-1 w-full h-full flex flex-col px-2">
            <header class="h-10 w-full flex items-center justify-between text-xs">
                <div>Default (Edit)</div>
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    @include('partials.responsive-buttons')
                </div>
                <button x-on:click="isAdvancedOpen = !isAdvancedOpen" class="cursor-pointer">Advanced</button>
            </header>
            <main class="flex-1 w-full h-full">
                {{-- iframe is dynamically created in app.js --}}
                <div id="builder-iframe-container" class="flex-1 h-full bg-white border border-stone-200 border-b-0 rounded-t-xl overflow-hidden"></div>
            </main>
        </div>

        {{-- Right Sidebar Panel --}}
        <x-advanced-panel x-show="isAdvancedOpen" x-cloak />
    </div>
</x-layouts.main>
