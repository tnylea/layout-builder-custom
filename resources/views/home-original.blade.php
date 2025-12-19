<x-layouts.app>
    <section x-data="{
        layout: [],
        history: [],
        message: 'hello',
        historyIndex: -1,
        getDefaultLayout() {
            return [
                {
                    id: this.generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',      // 'full' | 'boxed'
                    maxWidth: '7xl',            // Preset key (e.g., '7xl') or null
                    children: [
                        { id: this.generateId(), type: 'slot', name: 'Header', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: this.generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    children: [
                        { id: this.generateId(), type: 'slot', name: 'Content', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: this.generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    children: [
                        { id: this.generateId(), type: 'slot', name: 'Footer', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                }
            ];
        },
        saveHistory() {
            // Trim future history
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(JSON.parse(JSON.stringify(this.layout)));
            this.historyIndex++;

            // Limit history to 50 entries
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },
        generateId() {
            return 'slot_' + Math.random().toString(36).substring(2, 11);
        },
    
    }"
    x-init="
        layout = getDefaultLayout();
        saveHistory();
    "
    class="p-5 gap-5 grid">
        <template x-for="(row, rowIndex) in layout" :key="row.id">
            <div class="relative">
                <div class="grid grid-rows-2">
                    <div  class="h-24 hover:ring-2 hover:ring-blue-600 hover:ring-inset relative w-full grid grid-cols-12 border-x border-y border-blue-600 divide-y-4 divide-y-reverse divide-gray-200 bg-gray-200">
                     asdf
                    </div>
                    <div class="h-24 bg-pink-200"></div>
                </div>
                <button class="rounded-full w-6 h-6 flex items-center justify-center bg-blue-600 -translate-x-1/2 translate-y-1/2 text-white absolute bottom-0 left-0">+</button>
            </div>
        </template>
    </section>
</x-layouts.app>