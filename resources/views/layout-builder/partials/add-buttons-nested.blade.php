{{--
    Add Buttons for Nested Slots (within columns)

    These buttons allow adding slots above/below within a column,
    or adding slots left/right at the row level.

    This partial expects access to:
    - rowIndex, slotIndex (for the column)
    - nestedIndex (for the nested slot within the column)
    - addNestedSlot() for vertical adds within column
    - addSlot() for horizontal adds at row level
--}}

{{-- Add Top (within column) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'top')"
    class="absolute -top-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add slot above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (at row level - adds new column) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add column left"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (at row level - adds new column) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add column right"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (within column) --}}
<button
    @click="addNestedSlot(rowIndex, slotIndex, nestedIndex, 'bottom')"
    class="absolute -bottom-2 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/nested:opacity-100 group-hover/nested:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add slot below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
