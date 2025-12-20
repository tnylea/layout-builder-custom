<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-100 font-sans">

<div x-data="layoutBuilder()" class="min-h-screen flex flex-col">

    {{-- Main Canvas Area --}}
    <main class="flex-1 py-10">

        {{-- Toolbar (constrained width) --}}
        <div class="max-w-7xl mx-auto mb-6 px-5">
            @include('layout-builder.partials.toolbar')
        </div>

        {{-- Rows Container --}}
        <div class="gap-10 flex flex-col w-full items-stretch justify-stretch px-5">

            {{-- Loop through rows --}}
            <template x-for="(row, rowIndex) in layout" :key="row.id">

                {{-- Row Wrapper (Flex Container) --}}
                <div
                    :class="getRowWrapperClass(row)"
                    class="relative group/row transition-all duration-200"
                >

                    {{--
                        Row Content (Grid or Flex)
                        - Grid mode: when all slots are fluid (col-span-X)
                        - Flex mode: when any slot has fixed width (flex-1 + flex-shrink-0)
                    --}}
                    <div
                        :class="getRowContentClass(row)"
                        :style="getRowContentStyle(row)"
                        class="transition-all duration-300"
                    >

                        {{-- Loop through slots in each row --}}
                        <template x-for="(slot, slotIndex) in row.children" :key="slot.id">

                            {{--
                                Slot Container
                                - :class provides col-span-X (grid) or flex-1/flex-shrink-0 (flex)
                                - :style provides fixed pixel width when in fixed mode
                            --}}
                            <div
                                :class="getSlotColSpanClass(row, slotIndex)"
                                :style="getSlotStyle(row, slotIndex)"
                                class="group/slot relative transition-all duration-200"
                            >
                                {{-- Add Buttons --}}
                                @include('layout-builder.partials.add-buttons')

                                {{-- Slot Width Control (only shows when row has > 1 slot) --}}
                                @include('layout-builder.partials.width-control')

                                {{-- Delete Button --}}
                                <button
                                    @click="deleteSlot(rowIndex, slotIndex)"
                                    x-show="canDelete()"
                                    class="absolute bottom-3 right-3 z-20 w-8 h-8 bg-white border border-slate-200 text-slate-400 rounded-lg flex items-center justify-center shadow-sm cursor-pointer opacity-0 group-hover/slot:opacity-100 transition-all duration-150 hover:bg-red-50 hover:border-red-300 hover:text-red-500"
                                    title="Delete slot"
                                >
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

                    {{-- Row Width Indicator --}}
                    <div
                        class="absolute -left-2 top-1/2 -translate-y-1/2 z-30 opacity-0 group-hover/row:opacity-100 transition-opacity duration-150"
                        x-data="{ showMenu: false }"
                    >
                        <button
                            @click="showMenu = !showMenu"
                            class="flex items-center gap-1 px-2 py-1 text-xs font-mono bg-white border border-slate-200 rounded-lg shadow-sm hover:border-blue-300 hover:text-blue-600 transition-colors"
                            :title="getRowWidthDisplay(row)"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            <span x-text="row.rowWidthMode === 'full' ? 'Full' : (row.rowWidthMode === 'fixed' ? row.fixedWidth + 'px' : row.maxWidth)"></span>
                        </button>

                        <div
                            x-show="showMenu"
                            @click.outside="showMenu = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute left-0 top-full mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50 py-1"
                        >
                            <div class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Width Mode</div>

                            <button
                                @click="setRowWidthMode(rowIndex, 'full'); showMenu = false"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 flex items-center justify-between"
                                :class="{ 'bg-blue-50 text-blue-600': row.rowWidthMode === 'full' }"
                            >
                                <span>Full Width</span>
                                <span class="text-xs text-slate-400">100%</span>
                            </button>

                            <div class="border-t border-slate-100 my-1"></div>
                            <div class="px-3 py-1 text-xs font-semibold text-slate-500 uppercase tracking-wide">Boxed (Max Width)</div>

                            <template x-for="(preset, key) in rowWidthPresets" :key="key">
                                <button
                                    @click="setRowWidthMode(rowIndex, 'boxed', key); showMenu = false"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 flex items-center justify-between"
                                    :class="{ 'bg-blue-50 text-blue-600': row.rowWidthMode === 'boxed' && row.maxWidth === key }"
                                >
                                    <span x-text="key"></span>
                                    <span class="text-xs text-slate-400" x-text="preset.value + 'px'"></span>
                                </button>
                            </template>

                            <div class="border-t border-slate-100 my-1"></div>
                            <div class="px-3 py-1 text-xs font-semibold text-slate-500 uppercase tracking-wide">Fixed Width</div>

                            <div class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <input
                                        type="number"
                                        :value="row.fixedWidth || 960"
                                        @change="setRowWidthMode(rowIndex, 'fixed', $event.target.value)"
                                        @keydown.enter="setRowWidthMode(rowIndex, 'fixed', $event.target.value); showMenu = false"
                                        class="w-full px-2 py-1 text-sm border border-slate-200 rounded focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                                        placeholder="960"
                                        min="200"
                                        max="2000"
                                    >
                                    <span class="text-xs text-slate-400">px</span>
                                </div>
                            </div>
                        </div>
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

    {{-- Toast Notification --}}
    @include('layout-builder.partials.toast')

</div>

</body>
</html>
