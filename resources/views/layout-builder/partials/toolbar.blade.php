{{--
    Toolbar Component

    Displays:
    - Layout name with edit button
    - Slot and row counts
    - Undo/Redo buttons
    - Menu dropdown (Reset, Export JSON)

    This partial expects to be inside an x-data="layoutBuilder()" context.
--}}

<header class="flex items-center justify-between z-50">
    {{-- Left side: Layout name --}}
    <div class="flex items-center">
        <div class="px-2 py-1 text-lg mr-2 rounded-lg font-medium">Default</div>
        {{-- Edit button (placeholder for future functionality) --}}
        <button class="size-6 flex items-center justify-center rounded-full hover:bg-stone-100 bg-stone-100/50 border border-transparent hover:border-stone-200 hover:text-blue-600 text-stone-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
            </svg>
        </button>
    </div>

    {{-- Right side: Stats, Undo/Redo, Menu --}}
    <div class="flex items-center gap-2">

        {{--
            Stats Display
            - x-text binds reactive data to the element's text content
            - getTotalSlots() and layout.length are called every time they change
        --}}
        <div class="flex items-center gap-2 text-xs">
            <span class="font-mono text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                <span x-text="getTotalSlots()"></span> slots
            </span>
            <span class="font-mono text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                <span x-text="layout.length"></span> rows
            </span>
        </div>

        {{-- Spacing Control --}}
        @include('layout-builder.partials.spacing-control')

        {{--
            Undo/Redo Buttons
            - :disabled binds to canUndo()/canRedo() - disables when no history
            - disabled: styles make the button appear faded when disabled
        --}}
        <div class="flex items-center border-r border-slate-200 pr-3 mr-1">
            <button
                @click="undo()"
                :disabled="!canUndo()"
                class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                title="Undo (Cmd+Z)"
            >
                {{-- Undo arrow icon --}}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
            </button>
            <button
                @click="redo()"
                :disabled="!canRedo()"
                class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                title="Redo (Cmd+Shift+Z)"
            >
                {{-- Redo arrow icon --}}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/>
                </svg>
            </button>
        </div>

        {{--
            Menu Dropdown
            - x-data="{ open: false }" creates local state for this dropdown
            - @click.outside closes the menu when clicking elsewhere
        --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
                title="More options"
            >
                {{-- Three dots icon --}}
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="1.5"/>
                    <circle cx="19" cy="12" r="1.5"/>
                    <circle cx="5" cy="12" r="1.5"/>
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute top-full right-0 mt-2 w-48 p-2 bg-white rounded-lg border border-stone-200/60 shadow-lg z-50"
            >
                {{-- Reset Button --}}
                <button
                    @click="resetLayout(); open = false"
                    class="px-3 py-2 text-sm font-medium text-slate-600 w-full text-center bg-slate-100 hover:text-slate-800 hover:bg-slate-200 rounded-lg transition-colors mb-2"
                >
                    Reset Layout
                </button>

                {{-- Export JSON Button --}}
                <button
                    @click="copyToClipboard(); open = false"
                    class="px-4 py-2 text-sm font-medium w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2"
                >
                    {{-- Copy icon --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Export JSON</span>
                </button>
            </div>
        </div>

    </div>
</header>
