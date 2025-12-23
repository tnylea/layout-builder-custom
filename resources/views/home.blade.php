<x-layouts.app>
    <div class="flex h-screen w-screen bg-neutral-100">
        {{-- Main Builder Area --}}
        <div class="flex-1 flex flex-col px-2">
            <div class="h-10 w-full flex items-center justify-between">
                <div>Default (Edit)</div>
                <div class="text-sm text-gray-500">Preview</div>
                <div></div>
            </div>
            <div class="flex-1 bg-white border border-stone-200 border-b-0 rounded-t-xl overflow-hidden">
                <iframe src="/builder" class="w-full h-full border-none"></iframe>
            </div>
        </div>

        {{-- Right Sidebar Panel --}}
        <div class="w-72 bg-white border-l border-stone-200 flex flex-col" x-data="{ get selectedElement() { return $store.builder.selectedElement } }">
            <div class="p-4 border-b border-stone-200">
                <h2 class="font-semibold text-gray-900">Element Inspector</h2>
            </div>
            <div class="flex-1 p-4 overflow-y-auto">
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
                        <div class="pt-4 border-t border-stone-200">
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
    </div>
</x-layouts.app>
