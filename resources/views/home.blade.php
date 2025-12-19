<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-100 font-sans">

{{--
    x-data="layoutBuilder()" initializes our Alpine component
    All the data and methods from layout-builder.js are now available
    inside this div and all its children
--}}
<div x-data="layoutBuilder()" class="min-h-screen flex flex-col">

    {{-- Main Canvas Area --}}
    <main class="flex-1 py-10 layout-canvas">

        {{--
            Rows Container
            - gap-10: Space between rows (2.5rem / 40px)
            - flex flex-col: Stack rows vertically
            - px-5: Horizontal padding
        --}}
        <div class="gap-10 flex flex-col w-full items-stretch justify-stretch px-5">

            {{--
                x-for loops through the layout array
                :key="row.id" helps Alpine track which element is which
                (important for animations and updates)
            --}}
            <template x-for="(row, rowIndex) in layout" :key="row.id">

                {{--
                    Row Wrapper
                    - flex w-full: Full width flex container
                    - justify-center: Centers the content (for boxed mode)
                    - group/row: Creates a "group" for hover states (group-hover/row:...)
                --}}
                <div class="flex w-full justify-center relative group/row">

                    {{--
                        Row Content (Grid)
                        - grid grid-cols-12: 12-column grid system
                        - gap-10: Gap between slots/columns
                        - max-w-7xl: Maximum width constraint
                        - w-full: Take full available width up to max
                    --}}
                    <div class="grid grid-cols-12 gap-10 w-full max-w-7xl">

                        {{--
                            Loop through children (slots) in each row
                            Each slot gets its own column span
                        --}}
                        <template x-for="(slot, slotIndex) in row.children" :key="slot.id">

                            {{--
                                Slot Container
                                - :class binds dynamic classes
                                - getSlotColSpanClass() returns 'col-span-X'
                                - group/slot: Another group for slot-specific hover states
                            --}}
                            <div
                                :class="getSlotColSpanClass(row, slotIndex)"
                                class="group/slot relative"
                            >
                                {{--
                                    Slot Content Box
                                    - White background with dashed border
                                    - min-h changes based on slot name (Content is taller)
                                    - Hover effects: blue border and shadow
                                --}}
                                <div
                                    class="bg-white rounded-2xl border-2 border-dashed border-slate-300 p-6 flex items-center justify-center transition-all duration-200 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10"
                                    :class="{
                                        'min-h-[100px]': slot.name !== 'Content',
                                        'min-h-[400px]': slot.name === 'Content'
                                    }"
                                >
                                    {{--
                                        Slot Name (Editable)
                                        - contenteditable="true": Makes it editable
                                        - @blur: When user clicks away, save the new name
                                        - @keydown.enter.prevent: Enter key saves and exits
                                        - x-text: Displays the slot name
                                    --}}
                                    <span
                                        class="font-mono text-sm text-slate-400 select-none outline-none cursor-text transition-all duration-150 focus:bg-slate-100 focus:px-2 focus:py-1 focus:rounded focus:text-slate-700"
                                        contenteditable="true"
                                        @blur="updateSlotName(rowIndex, slotIndex, $el.textContent)"
                                        @keydown.enter.prevent="$el.blur()"
                                        x-text="slot.name"
                                    ></span>
                                </div>
                            </div>

                        </template>

                    </div>

                </div>

            </template>

        </div>

    </main>

    {{--
        Debug Panel - Shows the current layout JSON
        This helps you see the data structure as you interact with it
    --}}
    <footer class="bg-slate-800 text-slate-300 p-4 font-mono text-xs">
        <details class="group">
            <summary class="cursor-pointer hover:text-white select-none flex items-center gap-2">
                <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Layout Structure (Debug)
            </summary>
            {{-- x-text with JSON.stringify shows the live data --}}
            <pre
                class="mt-3 p-4 bg-slate-900 rounded-xl overflow-auto max-h-80 text-emerald-400"
                x-text="JSON.stringify(layout, null, 2)"
            ></pre>
        </details>
    </footer>

</div>

</body>
</html>
