{{--
    Add Slot Buttons

    These buttons appear on hover around each slot.
    - Top/Bottom: Add a new row above/below
    - Left/Right: Add a new slot in the same row

    Key Tailwind classes:
    - opacity-0 + group-hover/slot:opacity-100: Hidden until slot is hovered
    - scale-75 + group-hover/slot:scale-100: Grows slightly on hover
    - absolute + positioning: Places buttons on edges of slot

    The parent slot container needs "group/slot" class for these to work.
--}}

{{-- Add Top (new row above) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'top')"
    class="absolute -top-3 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add row above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (new slot in same row) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add column left"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (new slot in same row) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add column right"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (new row below) --}}
<button
    @click="addSlot(rowIndex, slotIndex, 'bottom')"
    class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-20 w-6 h-6 bg-white border-2 border-blue-500 text-blue-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/slot:opacity-100 group-hover/slot:scale-100 transition-all duration-150 hover:bg-blue-500 hover:text-white"
    title="Add row below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
