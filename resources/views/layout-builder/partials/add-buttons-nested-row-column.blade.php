{{--
    Add Buttons for Slots within Columns that are within Nested-Rows

    These buttons allow:
    - Top/Bottom: Add slots vertically within the column
    - Left/Right: Add slot horizontally (creates sibling in parent nested-row)

    Uses gray styling to indicate inner operations.

    This partial expects access to:
    - rowIndex, slotIndex (for the parent column)
    - nestedIndex (for the nested-row)
    - nrItemIndex (for the column within the nested-row)
    - nrColSlotIndex (for the slot within that column)
--}}

{{-- Add Top (within this column) --}}
<button
    @click="addNestedRowColumnSlot(rowIndex, slotIndex, nestedIndex, nrItemIndex, nrColSlotIndex, 'top')"
    class="absolute -top-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrcolslot:opacity-100 group-hover/nrcolslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (adds to parent nested-row) --}}
<button
    @click="addNestedRowColumnSlot(rowIndex, slotIndex, nestedIndex, nrItemIndex, nrColSlotIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrcolslot:opacity-100 group-hover/nrcolslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot left"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (adds to parent nested-row) --}}
<button
    @click="addNestedRowColumnSlot(rowIndex, slotIndex, nestedIndex, nrItemIndex, nrColSlotIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrcolslot:opacity-100 group-hover/nrcolslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot right"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (within this column) --}}
<button
    @click="addNestedRowColumnSlot(rowIndex, slotIndex, nestedIndex, nrItemIndex, nrColSlotIndex, 'bottom')"
    class="absolute -bottom-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrcolslot:opacity-100 group-hover/nrcolslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
