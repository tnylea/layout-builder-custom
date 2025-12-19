/**
 * Layout Builder - Alpine.js Component
 *
 * This file contains all the logic for the layout builder.
 * It's registered as an Alpine.js component and used with x-data="layoutBuilder()"
 */

/**
 * Generate unique IDs for slots and rows
 * Uses a random string to ensure each element has a unique identifier
 */
function generateId() {
    return 'slot_' + Math.random().toString(36).substring(2, 11);
}

/**
 * Main Layout Builder Component
 * Returns an object with all the reactive data and methods
 */
export default function layoutBuilder() {
    return {
        // ==============================================
        // STATE (Reactive Data)
        // ==============================================

        /**
         * The main layout array - this is the core data structure
         * Structure: Array of rows, each row has children (slots)
         *
         * Row structure:
         * {
         *   id: string,           // Unique identifier
         *   type: 'row',          // Always 'row' for rows
         *   rowWidthMode: string, // 'full' | 'boxed' | 'fixed'
         *   maxWidth: string,     // Preset key like '7xl' (for boxed mode)
         *   fixedWidth: number,   // Pixel value (for fixed mode)
         *   children: []          // Array of slots
         * }
         *
         * Slot structure:
         * {
         *   id: string,           // Unique identifier
         *   type: 'slot',         // 'slot' or 'column' (for nested)
         *   name: string,         // Display name (e.g., 'Header')
         *   component: null,      // Future: component reference
         *   width: number,        // Grid columns (1-12)
         *   widthMode: string,    // 'fluid' | 'fixed'
         *   fixedWidth: number    // Pixel value (for fixed mode)
         * }
         */
        layout: [],

        /**
         * History stack for undo/redo functionality
         * Each entry is a deep copy of the layout at that point
         */
        history: [],
        historyIndex: -1,

        // ==============================================
        // INITIALIZATION
        // ==============================================

        /**
         * Called automatically when Alpine initializes the component
         * Sets up the default layout and saves initial history
         */
        init() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();
        },

        /**
         * Creates the default layout structure
         * Three rows: Header, Content, Footer
         * Each with a single full-width (12 columns) slot
         */
        getDefaultLayout() {
            return [
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',      // Content is constrained
                    maxWidth: '7xl',            // Max width preset
                    fixedWidth: null,
                    children: [
                        {
                            id: generateId(),
                            type: 'slot',
                            name: 'Header',
                            component: null,
                            width: 12,              // Full width (12/12 columns)
                            widthMode: 'fluid',
                            fixedWidth: null
                        }
                    ]
                },
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        {
                            id: generateId(),
                            type: 'slot',
                            name: 'Content',
                            component: null,
                            width: 12,
                            widthMode: 'fluid',
                            fixedWidth: null
                        }
                    ]
                },
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        {
                            id: generateId(),
                            type: 'slot',
                            name: 'Footer',
                            component: null,
                            width: 12,
                            widthMode: 'fluid',
                            fixedWidth: null
                        }
                    ]
                }
            ];
        },

        // ==============================================
        // HISTORY MANAGEMENT (for undo/redo - we'll use this later)
        // ==============================================

        /**
         * Saves the current layout state to history
         * Called after any modification to enable undo
         */
        saveHistory() {
            // Remove any "future" history if we're not at the end
            this.history = this.history.slice(0, this.historyIndex + 1);

            // Deep clone the layout and add to history
            this.history.push(JSON.parse(JSON.stringify(this.layout)));
            this.historyIndex++;

            // Limit history to 50 entries to prevent memory issues
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        // ==============================================
        // SLOT OPERATIONS
        // ==============================================

        /**
         * Updates a slot's name when the user edits it
         * @param {number} rowIndex - Index of the row containing the slot
         * @param {number} slotIndex - Index of the slot within the row
         * @param {string} newName - The new name entered by the user
         */
        updateSlotName(rowIndex, slotIndex, newName) {
            const trimmed = newName.trim();
            if (trimmed) {
                this.layout[rowIndex].children[slotIndex].name = trimmed;
                this.saveHistory();
            }
        },

        // ==============================================
        // HELPER METHODS
        // ==============================================

        /**
         * Gets the width of a slot (defaults to even distribution)
         * @param {object} row - The row object
         * @param {number} slotIndex - Index of the slot
         * @returns {number} Width in grid columns (1-12)
         */
        getSlotWidth(row, slotIndex) {
            const slot = row.children[slotIndex];
            if (slot.width) return slot.width;

            // Default: distribute evenly among all slots in the row
            return Math.floor(12 / row.children.length);
        },

        /**
         * Gets the CSS class for a slot's column span
         * This maps to Tailwind's col-span-X classes
         * @param {object} row - The row object
         * @param {number} slotIndex - Index of the slot
         * @returns {string} Tailwind class like 'col-span-12'
         */
        getSlotColSpanClass(row, slotIndex) {
            const width = this.getSlotWidth(row, slotIndex);
            return `col-span-${width}`;
        }
    };
}
