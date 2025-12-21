<x-layouts.cdn>
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
                    rows: [{ id: this.generateId() }, { id: this.generateId() }]
                }
            ];
        },
        addRow(layoutIndex) {
            this.layout[layoutIndex].rows.push({ id: this.generateId() });
            this.saveHistory();
        },
        deleteRow(layoutIndex) {
            this.layout[layoutIndex].rows.pop();
            this.saveHistory();
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
            <div class="relative border max-w-7xl mx-auto w-full border-stone-200 p-5 rounded-xl hover:ring-2 hover:ring-blue-600">
                <div class="grid border-t border-stone-300 " :style="'grid-template-rows: repeat(' + row.rows.length + ', minmax(0, 1fr))'">
                    <template x-for="(innerRow, innerRowIndex) in row.rows" :key="innerRow.id">
                        <div class="h-auto relative w-full grid grid-cols-12 border border-t-0 border-stone-300 bg-gray-100">
                            <div class="aspect-square w-full col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 border-r border-stone-300 h-full"></div>
                            <div class="aspect-square col-span-1 h-full"></div>
                        </div>
                    </template>
                </div>
                <button @click="deleteRow(rowIndex)" x-show="row.rows.length > 1" class="rounded-full w-6 h-6 flex items-center justify-center bg-red-600 -translate-x-1/2 -translate-y-1/2 text-white absolute top-0 left-0">-</button>
                <button @click="addRow(rowIndex)" class="rounded-full w-6 h-6 flex items-center justify-center bg-blue-600 -translate-x-1/2 translate-y-1/2 text-white absolute bottom-0 left-0">+</button>
            </div>
        </template>
    </section>
</x-layouts.cdn>