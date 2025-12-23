import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ============================================================================
// BUILDER STATE MANAGEMENT
// ============================================================================
// This Alpine data store manages the selected element state from the parent
// window. The iframe communicates selection events back to this store.

Alpine.store('builder', {
    selectedElement: null,

    setSelectedElement(element) {
        this.selectedElement = element;
    },

    clearSelection() {
        this.selectedElement = null;
    }
});

Alpine.start();


// ============================================================================
// ╔═══════════════════════════════════════════════════════════════════════════╗
// ║                                                                           ║
// ║                        IFRAME SELECTOR FUNCTIONALITY                      ║
// ║                                                                           ║
// ║  This section contains all the selector logic that runs inside the        ║
// ║  iframe. It handles hover effects, element selection, and communicates    ║
// ║  selection events back to the parent window's Alpine store.               ║
// ║                                                                           ║
// ╚═══════════════════════════════════════════════════════════════════════════╝
// ============================================================================

const iframeSelector = {

    // The iframe document reference
    iframeDoc: null,

    // Currently selected element reference
    selectedElement: null,

    // CSS class names for styling
    classes: {
        hover: 'builder-hover',
        selected: 'builder-selected'
    },

    // -------------------------------------------------------------------------
    // INITIALIZATION
    // -------------------------------------------------------------------------

    /**
     * Initialize the selector on the iframe document
     * @param {Document} iframeDocument - The iframe's document object
     */
    init(iframeDocument) {
        this.iframeDoc = iframeDocument;
        this.injectStyles();
        this.bindEvents();
    },

    /**
     * Inject the required CSS styles into the iframe
     */
    injectStyles() {
        const style = this.iframeDoc.createElement('style');
        style.id = 'builder-selector-styles';
        style.textContent = `
            /* Ignored elements - clicks pass through to parent */
            [data-ignore] {
                pointer-events: none !important;
            }

            /* Hover state - dashed blue border */
            .${this.classes.hover} {
                outline: 2px dashed #3b82f6 !important;
                outline-offset: -2px;
                cursor: pointer;
            }

            /* Selected state - solid blue border */
            .${this.classes.selected} {
                outline: 2px solid #3b82f6 !important;
                outline-offset: -2px;
            }

            /* Prevent hover style when element is selected */
            .${this.classes.selected}.${this.classes.hover} {
                outline: 2px solid #3b82f6 !important;
            }
        `;
        this.iframeDoc.head.appendChild(style);
    },

    /**
     * Bind mouse and click events to the iframe document
     */
    bindEvents() {
        this.iframeDoc.addEventListener('mouseover', this.handleMouseOver.bind(this), { capture: true });
        this.iframeDoc.addEventListener('mouseout', this.handleMouseOut.bind(this), { capture: true });
        this.iframeDoc.addEventListener('click', this.handleClick.bind(this), { capture: true });
    },

    // -------------------------------------------------------------------------
    // EVENT HANDLERS
    // -------------------------------------------------------------------------

    /**
     * Handle mouseover event - add hover effect
     * @param {MouseEvent} event
     */
    handleMouseOver(event) {
        const element = event.target;

        if (this.shouldIgnoreElement(element)) return;

        element.classList.add(this.classes.hover);
    },

    /**
     * Handle mouseout event - remove hover effect
     * @param {MouseEvent} event
     */
    handleMouseOut(event) {
        const element = event.target;

        if (this.shouldIgnoreElement(element)) return;

        element.classList.remove(this.classes.hover);
    },

    /**
     * Handle click event - select element
     * @param {MouseEvent} event
     */
    handleClick(event) {
        const element = event.target;

        if (this.shouldIgnoreElement(element)) return;

        event.preventDefault();
        event.stopPropagation();

        // Don't reselect the same element
        if (element === this.selectedElement) return;

        this.selectElement(element);
    },

    // -------------------------------------------------------------------------
    // SELECTION METHODS
    // -------------------------------------------------------------------------

    /**
     * Select an element and update the parent window's state
     * @param {HTMLElement} element - The element to select
     */
    selectElement(element) {
        // Remove selection from previously selected element
        this.clearSelection();

        // Remove hover class if present
        element.classList.remove(this.classes.hover);

        // Add selected class
        element.classList.add(this.classes.selected);

        // Store reference
        this.selectedElement = element;

        // Notify parent window's Alpine store
        this.notifyParent(element);
    },

    /**
     * Clear the current selection
     */
    clearSelection() {
        if (this.selectedElement) {
            this.selectedElement.classList.remove(this.classes.selected);
            this.selectedElement = null;
        }

        // Also clear any other selected elements (safety check)
        const selected = this.iframeDoc.querySelectorAll(`.${this.classes.selected}`);
        selected.forEach(el => el.classList.remove(this.classes.selected));
    },

    /**
     * Programmatically unselect the current element
     */
    unselectElement() {
        this.clearSelection();
        this.notifyParent(null);
    },

    // -------------------------------------------------------------------------
    // HELPER METHODS
    // -------------------------------------------------------------------------

    /**
     * Check if an element should be ignored from selection
     * @param {HTMLElement} element
     * @returns {boolean}
     */
    shouldIgnoreElement(element) {
        // Ignore elements with data-ignore attribute
        if (element.hasAttribute('data-ignore')) return true;

        // Ignore if any parent has data-ignore
        if (element.closest('[data-ignore]')) return true;

        // Ignore the document body and html
        if (element === this.iframeDoc.body || element === this.iframeDoc.documentElement) return true;

        return false;
    },

    /**
     * Notify the parent window about the selected element
     * @param {HTMLElement|null} element
     */
    notifyParent(element) {
        // Update the Alpine store in the parent window
        if (window.parent && window.parent.Alpine) {
            window.parent.Alpine.store('builder').setSelectedElement(element);
        }

        // Also dispatch a custom event for additional flexibility
        window.parent.dispatchEvent(new CustomEvent('builder:element-selected', {
            detail: { element }
        }));
    },

    // -------------------------------------------------------------------------
    // CLEANUP
    // -------------------------------------------------------------------------

    /**
     * Disable the selector and remove all event listeners
     */
    disable() {
        if (!this.iframeDoc) return;

        this.iframeDoc.removeEventListener('mouseover', this.handleMouseOver.bind(this), { capture: true });
        this.iframeDoc.removeEventListener('mouseout', this.handleMouseOut.bind(this), { capture: true });
        this.iframeDoc.removeEventListener('click', this.handleClick.bind(this), { capture: true });

        // Remove injected styles
        const style = this.iframeDoc.getElementById('builder-selector-styles');
        if (style) style.remove();

        // Clear selection
        this.clearSelection();
    }
};

// Make the selector available globally for the parent window to access
window.iframeSelector = iframeSelector;


// ============================================================================
// IFRAME INITIALIZATION
// ============================================================================

const iframe = document.querySelector('iframe');

iframe.onload = function() {
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

    // Inject the layout HTML into the iframe
    iframeDoc.body.innerHTML = `<div class="min-h-screen grid grid-rows-[auto_1fr_auto]">
    <!-- Header -->
    <header class="border-b">
        <!-- Header slot -->
        <div data-ignore class="w-full h-[100px]"></div>
    </header>

    <!-- Main layout -->
    <div class="grid grid-cols-1 md:grid-cols-[16rem_1fr]">
        <!-- Sidebar -->
        <aside class="border-r">
            <!-- Sidebar slot -->
        </aside>

        <!-- Content -->
        <main class="p-6">
            <!-- Content slot -->
        </main>
    </div>

    <!-- Footer -->
    <footer class="border-t">
        <!-- Footer slot -->
        <div data-ignore class="w-full h-[100px]"></div>
    </footer>
</div>`;

    // Initialize the selector on the iframe
    iframeSelector.init(iframeDoc);
};
