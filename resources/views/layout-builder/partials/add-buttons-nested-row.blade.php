{{--
    Add Buttons for Slots within Nested-Rows

    These buttons allow adding slots within a nested-row (horizontal group inside a column):
    - Left/Right: Add slot horizontally within the nested-row
    - Top/Bottom: Add slot/item to the parent column (above/below this nested-row)

    Uses gray styling to indicate inner operations.

    This partial expects access to:
    - rowIndex, slotIndex (for the column)
    - nestedIndex (for the nested-row within the column)
    - nrSlotIndex (for the slot within the nested-row)
    - addNestedRowSlot() for all add operations
--}}

{{-- Add Top (add to column above this nested-row) --}}
<button
    @click="addNestedRowSlot(rowIndex, slotIndex, nestedIndex, nrSlotIndex, 'top')"
    class="absolute -top-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrslot:opacity-100 group-hover/nrslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (within nested-row) --}}
<button
    @click="addNestedRowSlot(rowIndex, slotIndex, nestedIndex, nrSlotIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrslot:opacity-100 group-hover/nrslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot left"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (within nested-row) --}}
<button
    @click="addNestedRowSlot(rowIndex, slotIndex, nestedIndex, nrSlotIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrslot:opacity-100 group-hover/nrslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot right"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (add to column below this nested-row) --}}
<button
    @click="addNestedRowSlot(rowIndex, slotIndex, nestedIndex, nrSlotIndex, 'bottom')"
    class="absolute -bottom-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nrslot:opacity-100 group-hover/nrslot:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
