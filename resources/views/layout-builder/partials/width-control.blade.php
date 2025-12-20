{{--
    Slot Width Control Popover

    A tabbed interface for controlling slot width:
    - Fluid: 12-column grid with visual selector and presets
    - Fixed: Pixel value input with presets

    Only shows when the row has more than 1 slot (single slots
    are always full width, so width control is pointless).

    This partial expects access to:
    - row, rowIndex, slot, slotIndex (from parent x-for loops)
    - All layoutBuilder() methods
--}}

<div
    x-show="row.children.length > 1"
    x-data="{
        open: false,
        activeTab: 'fluid',
        fixedValue: '',
        init() {
            // Initialize tab based on current slot mode
            this.activeTab = slot.widthMode || 'fluid';
            this.fixedValue = slot.fixedWidth || '';
        }
    }"
    class="absolute bottom-3 right-14 z-30 opacity-0 group-hover/slot:opacity-100 transition-all duration-150"
>
    {{--
        Trigger Button
        Shows current width (e.g., "6/12" or "300px")
    --}}
    <button
        @click="open = !open"
        class="flex items-center gap-1.5 h-8 px-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg hover:border-slate-300 hover:text-slate-700 shadow-sm cursor-pointer transition-colors"
        :class="{ 'border-blue-300 bg-blue-50 text-blue-600': open }"
    >
        {{-- Resize icon --}}
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
        </svg>
        <span class="text-xs font-medium">Width</span>
        {{-- Current value display --}}
        <span
            class="text-xs font-mono text-slate-400"
            x-text="slot.widthMode === 'fixed' ? slot.fixedWidth + 'px' : getSlotWidth(row, slotIndex) + '/12'"
        ></span>
    </button>

    {{--
        Popover Panel
        Contains tabbed interface for Fluid/Fixed width modes
    --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        @click.outside="open = false"
        class="absolute bottom-full right-0 mb-2 w-64 bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden"
    >
        {{-- Decorative Arrow --}}
        <div class="absolute -bottom-1.5 right-6 w-3 h-3 bg-white border-r border-b border-slate-200 transform rotate-45"></div>

        {{--
            Tab Header
            Switches between Fluid and Fixed modes
        --}}
        <div class="relative bg-slate-50 border-b border-slate-200 p-1">
            <div class="flex gap-1">
                <button
                    @click="activeTab = 'fluid'"
                    class="flex-1 px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                    :class="activeTab === 'fluid' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                >
                    Fluid
                </button>
                <button
                    @click="activeTab = 'fixed'"
                    class="flex-1 px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                    :class="activeTab === 'fixed' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                >
                    Fixed
                </button>
            </div>
        </div>

        {{--
            Fluid Tab Content
            Visual 12-column grid selector with presets
        --}}
        <div x-show="activeTab === 'fluid'" class="p-3">
            <p class="text-xs text-slate-500 mb-3">Column width relative to 12-column grid</p>

            {{--
                Visual Grid Selector
                12 clickable bars that fill to show current width
                Clicking a bar sets the width to that column count
            --}}
            <div class="grid grid-cols-12 gap-0.5 mb-3 h-6 bg-slate-100 rounded-lg overflow-hidden p-0.5">
                <template x-for="i in 12" :key="i">
                    <button
                        @click="setFluidWidth(rowIndex, slotIndex, i); slot.widthMode = 'fluid'; slot.fixedWidth = null;"
                        class="rounded transition-all"
                        :class="i <= getSlotWidth(row, slotIndex) && slot.widthMode !== 'fixed'
                            ? 'bg-blue-500 hover:bg-blue-600'
                            : 'bg-slate-200 hover:bg-slate-300'"
                    ></button>
                </template>
            </div>

            {{--
                Quick Presets
                Common width ratios for fast selection
            --}}
            <div class="flex flex-wrap gap-1.5">
                <template x-for="preset in [
                    { label: '1/4', value: 3 },
                    { label: '1/3', value: 4 },
                    { label: '1/2', value: 6 },
                    { label: '2/3', value: 8 },
                    { label: '3/4', value: 9 }
                ]" :key="preset.value">
                    <button
                        @click="setFluidWidth(rowIndex, slotIndex, preset.value); slot.widthMode = 'fluid'; slot.fixedWidth = null;"
                        class="px-2 py-1 text-xs font-medium rounded-md border transition-all"
                        :class="getSlotWidth(row, slotIndex) === preset.value && slot.widthMode !== 'fixed'
                            ? 'bg-blue-50 border-blue-200 text-blue-700'
                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                        x-text="preset.label"
                    ></button>
                </template>
            </div>

            {{-- Current Value Display --}}
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Current</span>
                <span class="text-sm font-mono font-medium text-slate-700" x-text="getSlotWidth(row, slotIndex) + ' / 12 columns'"></span>
            </div>
        </div>

        {{--
            Fixed Tab Content
            Pixel value input with presets
        --}}
        <div x-show="activeTab === 'fixed'" class="p-3">
            <p class="text-xs text-slate-500 mb-3">Set an exact pixel width for this column</p>

            {{-- Pixel Input with Unit Label --}}
            <div class="flex items-center gap-2 mb-3">
                <div class="relative flex-1">
                    <input
                        type="number"
                        x-model="fixedValue"
                        @input="if(fixedValue) { setFixedWidth(rowIndex, slotIndex, fixedValue); slot.widthMode = 'fixed'; slot.fixedWidth = fixedValue; }"
                        placeholder="e.g. 300"
                        class="w-full h-9 pl-3 pr-10 text-sm font-mono border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">px</span>
                </div>
            </div>

            {{-- Quick Pixel Presets --}}
            <div class="flex flex-wrap gap-1.5">
                <template x-for="px in [200, 250, 300, 350, 400]" :key="px">
                    <button
                        @click="fixedValue = px; setFixedWidth(rowIndex, slotIndex, px); slot.widthMode = 'fixed'; slot.fixedWidth = px;"
                        class="px-2 py-1 text-xs font-mono rounded-md border transition-all"
                        :class="slot.fixedWidth == px
                            ? 'bg-blue-50 border-blue-200 text-blue-700'
                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                        x-text="px + 'px'"
                    ></button>
                </template>
            </div>

            {{-- Reset to Fluid Button --}}
            <button
                @click="activeTab = 'fluid'; slot.widthMode = 'fluid'; slot.fixedWidth = null; fixedValue = ''; redistributeWidths(rowIndex);"
                class="mt-3 w-full py-2 text-xs font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
            >
                Reset to fluid width
            </button>
        </div>
    </div>
</div>
