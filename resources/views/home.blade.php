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
                    rowWidthMode: 'boxed',      // 'full' | 'boxed' | 'fixed'
                    maxWidth: '7xl',            // Preset key (e.g., '7xl') or null
                    fixedWidth: null,          // Pixel value for fixed mode
                    children: [
                        { id: this.generateId(), type: 'slot', name: 'Header', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: this.generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        { id: this.generateId(), type: 'slot', name: 'Content', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: this.generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
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
    class="p-5">
        <template x-for="(row, rowIndex) in layout" :key="row.id">
            <div x-text="row.id" class="h-24 w-full bg-gray-200"></div>
        </template>
    </section>
</x-layouts.app>