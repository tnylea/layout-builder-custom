{{--
    Toast Notification Component

    A simple notification that slides up from the bottom-right corner.
    Controlled by showToast and toastMessage in the layoutBuilder component.

    x-show: Controls visibility based on showToast boolean
    x-transition: Adds enter/leave animations
    x-text: Displays the dynamic message
--}}

<div
    x-show="showToast"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 z-50"
>
    {{-- Success checkmark icon --}}
    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    {{-- Message text --}}
    <span x-text="toastMessage" class="text-sm font-medium"></span>
</div>
