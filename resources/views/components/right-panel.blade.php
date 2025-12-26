<div class="w-72 flex flex-col" {{  $attributes }} x-data="{ 
    get selectedElement() { return $store.builder.selectedElement },
    get bodyHtml() { return $store.builder.bodyHtml },
    set bodyHtml(value) { $store.builder.setBodyHtml(value) },
    advanced: false
}">
    <div class="px-2 py-4 flex items-center border-b border-stone-700/60 text-xs">
        <h2>Layout Editor</h2>
        <button @click="advanced = !advanced" :class="{ 'bg-stone-700' : advanced }" class="absolute px-2.5 py-1.5 rounded-md mr-1.5 text-xs right-0">Advanced</button>
    </div>
    <div x-show="advanced" class="flex-1 p-4 overflow-y-auto">
        <template x-if="selectedElement">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tag Name</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded" x-text="selectedElement.tagName.toLowerCase()"></div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Classes</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded break-all" x-text="Array.from(selectedElement.classList).filter(c => !c.startsWith('builder-')).join(' ') || '(none)'"></div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">ID</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded" x-text="selectedElement.id || '(none)'"></div>
                </div>
                <div></div>
                <div class="pt-4 border-t border-stone-700/60">
                    <button
                        @click="window.iframeSelector.unselectElement()"
                        class="w-full px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded transition-colors"
                    >
                        Clear Selection
                    </button>
                </div>
            </div>
        </template>
        <template x-if="!selectedElement">
            <div class="text-sm text-gray-500 text-center py-8">
                Click on an element in the preview to select it
            </div>
        </template>

        {{-- HTML Editor --}}
        <div class="mt-4 pt-4 border-t border-stone-700/60">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Body HTML</label>
            <textarea
                x-model="bodyHtml"
                @input.debounce.300ms="$store.builder.setBodyHtml($event.target.value)"
                class="w-full h-48 text-xs font-mono bg-neutral-800 text-neutral-300 p-2 rounded border border-neutral-700 focus:border-blue-500 focus:outline-none resize-y"
                placeholder="Enter HTML..."
            ></textarea>
        </div>
    </div>
    <div x-show="!advanced" class="flex-1 p-4 overflow-y-auto">
        <template x-if="selectedElement">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tag Name</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded" x-text="selectedElement.tagName.toLowerCase()"></div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Classes</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded break-all" x-text="Array.from(selectedElement.classList).filter(c => !c.startsWith('builder-')).join(' ') || '(none)'"></div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">ID</label>
                    <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded" x-text="selectedElement.id || '(none)'"></div>
                </div>
                <div></div>
                <div class="pt-4 border-t border-stone-700/60">
                    <button
                        @click="window.iframeSelector.unselectElement()"
                        class="w-full px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded transition-colors"
                    >
                        Clear Selection
                    </button>
                </div>
            </div>
        </template>
        <template x-if="!selectedElement">
            <div class="text-sm text-gray-500 text-center py-8">
                Click on an element in the preview to select it
            </div>
        </template>
    </div>
</div>