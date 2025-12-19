import Alpine from 'alpinejs';

// Import the layout builder component
import layoutBuilder from './layout-builder';

// Register Alpine components BEFORE starting Alpine
// Alpine.data() creates a reusable component that can be used with x-data="layoutBuilder()"
Alpine.data('layoutBuilder', layoutBuilder);

// Make Alpine available globally (useful for debugging in browser console)
window.Alpine = Alpine;

// Start Alpine - this activates all x-data, x-for, etc. directives
Alpine.start();
