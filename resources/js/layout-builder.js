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
         */
        layout: [],

        /**
         * History for undo/redo (we'll wire this up in Iteration 3)
         */
        history: [],
        historyIndex: -1,

        // ==============================================
        // INITIALIZATION
        // ==============================================

        /**
         * Called automatically when Alpine initializes the component
         */
        init() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();
        },

        /**
         * Creates the default layout structure
         * Three rows: Header, Content, Footer
         */
        getDefaultLayout() {
            return [
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
                            name: 'Header',
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
        // HISTORY MANAGEMENT
        // ==============================================

        /**
         * Saves the current layout state to history
         */
        saveHistory() {
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(JSON.parse(JSON.stringify(this.layout)));
            this.historyIndex++;

            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        // ==============================================
        // SLOT COUNTING & VALIDATION
        // ==============================================

        /**
         * Get total number of slots across all rows
         * Used for display in toolbar and for delete validation
         */
        getTotalSlots() {
            let count = 0;
            this.layout.forEach(row => {
                row.children.forEach(child => {
                    // In the future, columns can contain nested slots
                    if (child.type === 'column') {
                        count += child.children.length;
                    } else {
                        count += 1;
                    }
                });
            });
            return count;
        },

        /**
         * Check if deletion is allowed
         * We must always have at least one slot in the layout
         */
        canDelete() {
            return this.getTotalSlots() > 1;
        },

        // ==============================================
        // ADD SLOT OPERATIONS
        // ==============================================

        /**
         * Add a new slot in the specified direction
         *
         * This is one of the most important methods in the layout builder.
         * It handles 4 different directions and adjusts widths accordingly.
         *
         * @param {number} rowIndex - Which row the source slot is in
         * @param {number} slotIndex - Which slot we're adding relative to
         * @param {string} direction - 'top' | 'bottom' | 'left' | 'right'
         *
         * How it works:
         * - LEFT/RIGHT: Adds a new slot in the same row, redistributes column widths
         * - TOP/BOTTOM: Adds a new row above/below the current row
         */
        addSlot(rowIndex, slotIndex, direction) {
            // Create a new slot with default values
            const newSlot = {
                id: generateId(),
                type: 'slot',
                name: 'New Slot',
                component: null,
                width: null,        // Will be calculated
                widthMode: 'fluid',
                fixedWidth: null
            };

            if (direction === 'left' || direction === 'right') {
                // ==========================================
                // HORIZONTAL: Add slot to the same row
                // ==========================================
                const row = this.layout[rowIndex];

                // Calculate insert position
                // 'left' = insert at current index (pushes current slot right)
                // 'right' = insert after current index
                const insertIdx = direction === 'left' ? slotIndex : slotIndex + 1;

                // IMPORTANT: Redistribute widths among all slots
                // If we have 1 slot (12 cols) and add another, each gets 6 cols
                // If we have 2 slots (6 each) and add another, each gets 4 cols
                const newSlotCount = row.children.length + 1;
                const baseWidth = Math.floor(12 / newSlotCount);
                const remainder = 12 - (baseWidth * newSlotCount);

                // Update existing slot widths
                row.children.forEach((slot, idx) => {
                    // Give extra column to first slots if there's a remainder
                    // e.g., 3 slots: 12/3 = 4, but we might need 4+4+4 = 12 ✓
                    // e.g., 5 slots: 12/5 = 2.4 → 2, remainder = 2, so first 2 get 3
                    slot.width = baseWidth + (idx < remainder ? 1 : 0);
                });

                // New slot gets base width
                newSlot.width = baseWidth;

                // Insert the new slot at the calculated position
                // splice(index, deleteCount, ...items) - inserts items at index
                row.children.splice(insertIdx, 0, newSlot);

                // Ensure widths sum to exactly 12
                this.normalizeRowWidths(rowIndex);

            } else {
                // ==========================================
                // VERTICAL: Add a new row above or below
                // ==========================================

                // New slot in new row gets full width
                newSlot.width = 12;

                // Create a new row with the slot
                const newRow = {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [newSlot]
                };

                // Calculate insert position
                // 'top' = insert at current row index (pushes current row down)
                // 'bottom' = insert after current row
                const insertIdx = direction === 'top' ? rowIndex : rowIndex + 1;

                // Insert the new row
                this.layout.splice(insertIdx, 0, newRow);
            }

            // Save to history for undo support
            this.saveHistory();
        },

        // ==============================================
        // DELETE SLOT OPERATIONS
        // ==============================================

        /**
         * Delete a slot from the layout
         *
         * @param {number} rowIndex - Which row the slot is in
         * @param {number} slotIndex - Which slot to delete
         *
         * How it works:
         * 1. Check if we can delete (must have > 1 slot)
         * 2. Remember the deleted slot's width
         * 3. Remove the slot from the row
         * 4. If row is empty, remove the entire row
         * 5. Otherwise, redistribute the deleted width to remaining slots
         */
        deleteSlot(rowIndex, slotIndex) {
            // Safety check - never delete the last slot
            if (!this.canDelete()) return;

            const row = this.layout[rowIndex];

            // Get the width of the slot being deleted
            // Default to even distribution if width isn't set
            const deletedWidth = row.children[slotIndex].width ||
                Math.floor(12 / row.children.length);

            // Remove the slot
            row.children.splice(slotIndex, 1);

            // Check if row is now empty
            if (row.children.length === 0) {
                // Remove the entire row
                this.layout.splice(rowIndex, 1);
            } else {
                // Redistribute the deleted slot's width to remaining slots
                // This ensures the row still uses all 12 columns
                const widthPerSlot = Math.floor(deletedWidth / row.children.length);
                const remainder = deletedWidth - (widthPerSlot * row.children.length);

                row.children.forEach((slot, idx) => {
                    const currentWidth = slot.width ||
                        Math.floor(12 / (row.children.length + 1));
                    // Add the redistributed width, plus 1 for first 'remainder' slots
                    slot.width = currentWidth + widthPerSlot + (idx < remainder ? 1 : 0);
                });

                // Ensure total is exactly 12
                const total = row.children.reduce((sum, s) => sum + s.width, 0);
                if (total !== 12) {
                    row.children[0].width += (12 - total);
                }
            }

            this.saveHistory();
        },

        // ==============================================
        // WIDTH NORMALIZATION
        // ==============================================

        /**
         * Ensure all fluid slot widths in a row sum to exactly 12
         *
         * The 12-column grid requires widths to sum to 12.
         * After adding/removing slots, rounding errors can cause
         * the total to be 11 or 13. This fixes that.
         */
        normalizeRowWidths(rowIndex) {
            const row = this.layout[rowIndex];

            // Only normalize fluid (non-fixed) slots
            const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');
            if (fluidSlots.length === 0) return;

            // Calculate current total
            const totalFluidWidth = fluidSlots.reduce((sum, s) => sum + (s.width || 1), 0);

            // If not 12, adjust the last fluid slot
            if (totalFluidWidth !== 12) {
                const diff = 12 - totalFluidWidth;
                const lastFluidSlot = fluidSlots[fluidSlots.length - 1];
                lastFluidSlot.width += diff;
            }
        },

        // ==============================================
        // SLOT NAME EDITING
        // ==============================================

        /**
         * Updates a slot's name when the user edits it
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
         */
        getSlotWidth(row, slotIndex) {
            const slot = row.children[slotIndex];
            if (slot.width) return slot.width;
            return Math.floor(12 / row.children.length);
        },

        /**
         * Gets the CSS class for a slot's column span
         */
        getSlotColSpanClass(row, slotIndex) {
            const width = this.getSlotWidth(row, slotIndex);
            return `col-span-${width}`;
        }
    };
}
