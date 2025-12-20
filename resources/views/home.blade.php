<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-100 font-sans">

<div x-data="layoutBuilder()" class="min-h-screen flex flex-col">

    {{-- Main Canvas Area --}}
    <main class="flex-1 py-10">

        {{-- Rows Container --}}
        <div class="gap-10 flex flex-col w-full items-stretch justify-stretch px-5">

            {{-- Loop through rows --}}
            <template x-for="(row, rowIndex) in layout" :key="row.id">

                {{-- Row Wrapper --}}
                <div class="flex w-full justify-center relative group/row">

                    {{-- Row Content (12-column grid) --}}
                    <div class="grid grid-cols-12 gap-10 w-full max-w-7xl">

                        {{-- Loop through slots in each row --}}
                        <template x-for="(slot, slotIndex) in row.children" :key="slot.id">

                            {{--
                                Slot Container
                                Notice: We wrap the slot content in another div
                                so we can position the add/delete buttons relative to it
                            --}}
                            <div
                                :class="getSlotColSpanClass(row, slotIndex)"
                                class="group/slot relative"
                            >
                                {{--
                                    Include the add buttons partial
                                    These buttons appear on hover and call addSlot()
                                    They use rowIndex and slotIndex from the parent x-for loops
                                --}}
                                @include('layout-builder.partials.add-buttons')

                                {{--
                                    Delete Button
                                    - x-show="canDelete()": Only show if we have > 1 slot
                                    - opacity-0 + group-hover/slot:opacity-100: Appear on hover
                                    - Positioned bottom-right of the slot
                                --}}
                                <button
                                    @click="deleteSlot(rowIndex, slotIndex)"
                                    x-show="canDelete()"
                                    class="absolute bottom-3 right-3 z-20 w-8 h-8 bg-white border border-slate-200 text-slate-400 rounded-lg flex items-center justify-center shadow-sm cursor-pointer opacity-0 group-hover/slot:opacity-100 transition-all duration-150 hover:bg-red-50 hover:border-red-300 hover:text-red-500"
                                    title="Delete slot"
                                >
                                    {{-- Trash icon --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                {{-- Slot Content Box --}}
                                <div
                                    class="bg-white rounded-2xl border-2 border-dashed border-slate-300 p-6 flex items-center justify-center transition-all duration-200 hover:border-blue-500 hover:shadow-blue-500/10"
                                    :class="{
                                        'min-h-[100px]': slot.name !== 'Content',
                                        'min-h-[400px]': slot.name === 'Content'
                                    }"
                                >
                                    {{-- Editable Slot Name --}}
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

    {{-- Debug Panel --}}
    <footer class="bg-slate-800 text-slate-300 p-4 font-mono text-xs">
        <details class="group">
            <summary class="cursor-pointer hover:text-white select-none flex items-center gap-2">
                <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Layout Structure (Debug)
            </summary>
            <pre
                class="mt-3 p-4 bg-slate-900 rounded-xl overflow-auto max-h-80 text-emerald-400"
                x-text="JSON.stringify(layout, null, 2)"
            ></pre>
        </details>
    </footer>

</div>

</body>
</html>
