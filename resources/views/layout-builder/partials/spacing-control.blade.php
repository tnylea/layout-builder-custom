{{--
    Spacing Control Component

    A popover interface for controlling gaps between:
    - Rows (vertical spacing between rows)
    - Columns (horizontal spacing between columns in a row)
    - Nested (vertical spacing between nested slots)

    Features:
    - Visual diagram showing what each gap controls
    - Quick presets for common spacing configurations
    - Individual sliders for fine-grained control
--}}

<div x-data="{ open: false }" class="relative">
    {{-- Trigger Button --}}
    <button
        @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:border-slate-300 hover:bg-slate-50 transition-colors"
        :class="{ 'border-blue-300 bg-blue-50 text-blue-600': open }"
    >
        {{-- Spacing icon --}}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3M7 18v3M17 3v3M17 18v3"/>
        </svg>
        <span>Spacing</span>
    </button>

    {{-- Popover Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        @click.outside="open = false"
        class="absolute top-full left-0 mt-2 w-80 bg-white rounded-xl border border-slate-200 shadow-xl z-50 overflow-hidden"
    >
        {{-- Header --}}
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
            <h3 class="text-sm font-semibold text-slate-700">Layout Spacing</h3>
            <p class="text-xs text-slate-500 mt-0.5">Control gaps between elements</p>
        </div>

        {{-- Quick Presets --}}
        <div class="px-4 py-3 border-b border-slate-100">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Quick Presets</div>
            <div class="flex gap-2">
                <button
                    @click="applySpacingPreset('compact')"
                    class="flex-1 px-3 py-2 text-xs font-medium rounded-lg border transition-all"
                    :class="spacing.rows === 'md' && spacing.columns === 'md'
                        ? 'bg-blue-50 border-blue-200 text-blue-700'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                >
                    <div class="flex items-center justify-center gap-1 mb-1">
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                    </div>
                    Compact
                </button>
                <button
                    @click="applySpacingPreset('comfortable')"
                    class="flex-1 px-3 py-2 text-xs font-medium rounded-lg border transition-all"
                    :class="spacing.rows === '2xl' && spacing.columns === '2xl'
                        ? 'bg-blue-50 border-blue-200 text-blue-700'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                >
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                    </div>
                    Comfortable
                </button>
                <button
                    @click="applySpacingPreset('spacious')"
                    class="flex-1 px-3 py-2 text-xs font-medium rounded-lg border transition-all"
                    :class="spacing.rows === '3xl' && spacing.columns === '3xl'
                        ? 'bg-blue-50 border-blue-200 text-blue-700'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                >
                    <div class="flex items-center justify-center gap-3 mb-1">
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                        <div class="w-1.5 h-1.5 bg-current rounded-sm"></div>
                    </div>
                    Spacious
                </button>
            </div>
        </div>

        {{-- Individual Controls --}}
        <div class="p-4 space-y-4">

            {{-- Row Gap Control --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        {{-- Vertical spacing icon --}}
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-slate-100">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Between Rows</span>
                    </div>
                    <span class="text-xs font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded" x-text="gapPresets[spacing.rows]?.label || '40px'"></span>
                </div>
                <div class="flex gap-1">
                    <template x-for="(preset, key) in gapPresets" :key="key">
                        <button
                            @click="setSpacing('rows', key)"
                            class="flex-1 h-2 rounded-full transition-all"
                            :class="Object.keys(gapPresets).indexOf(key) <= Object.keys(gapPresets).indexOf(spacing.rows)
                                ? 'bg-blue-500'
                                : 'bg-slate-200 hover:bg-slate-300'"
                            :title="preset.label"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Column Gap Control --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        {{-- Horizontal spacing icon --}}
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-slate-100">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Between Columns</span>
                    </div>
                    <span class="text-xs font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded" x-text="gapPresets[spacing.columns]?.label || '40px'"></span>
                </div>
                <div class="flex gap-1">
                    <template x-for="(preset, key) in gapPresets" :key="key">
                        <button
                            @click="setSpacing('columns', key)"
                            class="flex-1 h-2 rounded-full transition-all"
                            :class="Object.keys(gapPresets).indexOf(key) <= Object.keys(gapPresets).indexOf(spacing.columns)
                                ? 'bg-blue-500'
                                : 'bg-slate-200 hover:bg-slate-300'"
                            :title="preset.label"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Nested Gap Control --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        {{-- Nested spacing icon --}}
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-slate-100">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 19h16M9 9h6v6H9z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Nested Slots</span>
                    </div>
                    <span class="text-xs font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded" x-text="gapPresets[spacing.nested]?.label || '8px'"></span>
                </div>
                <div class="flex gap-1">
                    <template x-for="(preset, key) in gapPresets" :key="key">
                        <button
                            @click="setSpacing('nested', key)"
                            class="flex-1 h-2 rounded-full transition-all"
                            :class="Object.keys(gapPresets).indexOf(key) <= Object.keys(gapPresets).indexOf(spacing.nested)
                                ? 'bg-blue-500'
                                : 'bg-slate-200 hover:bg-slate-300'"
                            :title="preset.label"
                        ></button>
                    </template>
                </div>
            </div>

        </div>

        {{-- Visual Preview --}}
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Preview</div>
            <div
                class="flex flex-col bg-white rounded-lg border border-slate-200 p-2"
                :class="'gap-' + (gapPresets[spacing.rows]?.value / 4 || 2)"
                :style="'gap: ' + Math.min(gapPresets[spacing.rows]?.value / 4, 8) + 'px'"
            >
                {{-- Preview Row 1 --}}
                <div
                    class="flex"
                    :style="'gap: ' + Math.min(gapPresets[spacing.columns]?.value / 4, 8) + 'px'"
                >
                    <div class="flex-1 h-4 bg-blue-100 rounded"></div>
                </div>
                {{-- Preview Row 2 (split) --}}
                <div
                    class="flex"
                    :style="'gap: ' + Math.min(gapPresets[spacing.columns]?.value / 4, 8) + 'px'"
                >
                    <div class="flex-1 h-4 bg-blue-100 rounded"></div>
                    <div
                        class="flex-1 flex flex-col"
                        :style="'gap: ' + Math.min(gapPresets[spacing.nested]?.value / 4, 4) + 'px'"
                    >
                        <div class="flex-1 h-1.5 bg-blue-200 rounded"></div>
                        <div class="flex-1 h-1.5 bg-blue-200 rounded"></div>
                    </div>
                </div>
                {{-- Preview Row 3 --}}
                <div
                    class="flex"
                    :style="'gap: ' + Math.min(gapPresets[spacing.columns]?.value / 4, 8) + 'px'"
                >
                    <div class="flex-1 h-4 bg-blue-100 rounded"></div>
                </div>
            </div>
        </div>
    </div>
</div>
