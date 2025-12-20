{{--
    Outer Row Add Buttons

    These buttons appear on the outer row container when a row has multiple columns.
    They allow adding:
    - New row above/below the entire row
    - New column at the far left/right of the row

    The parent needs "group/outer" class for hover effects to work.
--}}

{{-- Add Top (new row above entire row) --}}
<button
    @click="addRowAt(rowIndex, 'above')"
    class="absolute -top-3 left-1/2 -translate-x-1/2 z-30 w-6 h-6 bg-white border-2 border-indigo-500 text-indigo-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/outer:opacity-100 group-hover/outer:scale-100 transition-all duration-150 hover:bg-indigo-500 hover:text-white"
    title="Add row above"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Left (new column at far left of row) --}}
<button
    @click="addOuterSlot(rowIndex, 'left')"
    class="absolute top-1/2 -left-3 -translate-y-1/2 z-30 w-6 h-6 bg-white border-2 border-indigo-500 text-indigo-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/outer:opacity-100 group-hover/outer:scale-100 transition-all duration-150 hover:bg-indigo-500 hover:text-white"
    title="Add column at start"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Right (new column at far right of row) --}}
<button
    @click="addOuterSlot(rowIndex, 'right')"
    class="absolute top-1/2 -right-3 -translate-y-1/2 z-30 w-6 h-6 bg-white border-2 border-indigo-500 text-indigo-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/outer:opacity-100 group-hover/outer:scale-100 transition-all duration-150 hover:bg-indigo-500 hover:text-white"
    title="Add column at end"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Add Bottom (new row below entire row) --}}
<button
    @click="addRowAt(rowIndex, 'below')"
    class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-30 w-6 h-6 bg-white border-2 border-indigo-500 text-indigo-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer opacity-0 scale-75 group-hover/outer:opacity-100 group-hover/outer:scale-100 transition-all duration-150 hover:bg-indigo-500 hover:text-white"
    title="Add row below"
>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>
