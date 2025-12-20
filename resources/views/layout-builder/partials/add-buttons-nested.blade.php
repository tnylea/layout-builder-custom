{{--
    Add Buttons for Nested Slots (within columns)

    These buttons allow:
    - Top/Bottom: Add slots vertically within the column
    - Left/Right: Create a nested-row (horizontal split within the column)

    Uses gray styling to indicate inner operations.

    This partial expects access to:
    - rowIndex, slotIndex (for the column)
    - nestedIndex (for the nested slot within the column)
    - addNestedSlot() for all directions (handles nested-row creation)
--}}

{{-- Add Top (within column) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'top')"
    class="absolute -top-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (creates nested-row or adds to existing one) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot left (split horizontally)"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (creates nested-row or adds to existing one) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot right (split horizontally)"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (within column) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'bottom')"
    class="absolute -bottom-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-slate-400 text-slate-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-slate-400 hover:text-white"
    title="Add slot below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
