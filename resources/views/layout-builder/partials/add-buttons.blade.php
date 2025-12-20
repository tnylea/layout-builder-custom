{{--
    Inner Add Slot Buttons

    These buttons appear on hover around each slot for inner operations.
    - When single column: Blue buttons for adding rows/columns
    - When multi-column: Gray buttons for splitting/stacking within the slot

    Key behaviors:
    - Top/Bottom: Split slot vertically (create nested column) OR add row if single column
    - Left/Right: Add adjacent slot in the same row

    The parent slot container needs "group/slot" class for these to work.
--}}

{{-- Add Top (split/stack above OR new row if single column) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'top')"
    :class="row.children.length > 1
        ? 'border-slate-400 text-slate-400 hover:bg-slate-400 hover:border-slate-400'
        : 'border-indigo-500 text-indigo-500 hover:bg-indigo-500 hover:border-indigo-500'"
    class="absolute -top-3 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:text-white"
    :title="row.children.length > 1 ? 'Stack slot above' : 'Add row above'"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (add slot to left in same row) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'left')"
    :class="row.children.length > 1
        ? 'border-slate-400 text-slate-400 hover:bg-slate-400 hover:border-slate-400'
        : 'border-indigo-500 text-indigo-500 hover:bg-indigo-500 hover:border-indigo-500'"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:text-white"
    title="Add column left"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (add slot to right in same row) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'right')"
    :class="row.children.length > 1
        ? 'border-slate-400 text-slate-400 hover:bg-slate-400 hover:border-slate-400'
        : 'border-indigo-500 text-indigo-500 hover:bg-indigo-500 hover:border-indigo-500'"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:text-white"
    title="Add column right"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (split/stack below OR new row if single column) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'bottom')"
    :class="row.children.length > 1
        ? 'border-slate-400 text-slate-400 hover:bg-slate-400 hover:border-slate-400'
        : 'border-indigo-500 text-indigo-500 hover:bg-indigo-500 hover:border-indigo-500'"
    class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:text-white"
    :title="row.children.length > 1 ? 'Stack slot below' : 'Add row below'"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
