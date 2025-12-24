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
        x-data="{ isRightPanelOpen: true }"
     class="flex items-stretch justify-stretch h-screen w-screen bg-neutral-900 text-stone-400">
        {{-- Main Builder Area --}}
        <div class="flex-1 w-full h-full flex flex-col px-2">
            <header class="h-10 w-full flex items-center justify-between text-xs">
                <div>Default (Edit)</div>
                <div class="text-sm text-gray-500 flex items-center gap-1">
                    @include('partials.responsive-buttons')
                </div>
                <button x-on:click="isRightPanelOpen = !isRightPanelOpen" class="cursor-pointer size-6 p-1 hover:bg-white/5 rounded-md group/rightpanel">
                    <svg class="w-full h-full group-hover/rightpanel:opacity-100 opacity-50" x-show="isRightPanelOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panel-right-close-icon lucide-panel-right-close"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/><path d="m8 9 3 3-3 3"/></svg>
                    <svg class="w-full h-full group-hover/rightpanel:opacity-100 opacity-50" x-show="!isRightPanelOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panel-right-open-icon lucide-panel-right-open"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/><path d="m10 15-3-3 3-3"/></svg>
                </button>
            </header>
            <main class="flex-1 w-full h-full">
                {{-- iframe is dynamically created in app.js --}}
                <div id="builder-iframe-container" class="flex-1 h-full bg-white border border-stone-200 border-b-0 rounded-t-xl overflow-hidden"></div>
            </main>
        </div>

        {{-- Right Sidebar Panel --}}
        <x-right-panel x-show="isRightPanelOpen" x-cloak />
    </div>
</x-layouts.main>
