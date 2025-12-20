/**
 * Layout Builder - Alpine.js Component
 *
 * This file contains all the logic for the layout builder.
 * It's registered as an Alpine.js component and used with x-data="layoutBuilder()"
 */

/**
 * Generate unique IDs for slots and rows
 */
function generateId() {
    return 'slot_' + Math.random().toString(36).substring(2, 11);
}

/**
 * Main Layout Builder Component
 */
export default function layoutBuilder() {
    return {
        // ==============================================
        // STATE
        // ==============================================

        layout: [],
        history: [],
        historyIndex: -1,

        /**
         * Toast notification state
         * Used to show feedback messages to the user
         */
        showToast: false,
        toastMessage: '',

        // ==============================================
        // INITIALIZATION
        // ==============================================

        /**
         * Called automatically when Alpine initializes the component
         */
        init() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();

            // Set up keyboard shortcuts
            // We use document.addEventListener because we want these
            // to work anywhere on the page, not just when focused on a specific element
            document.addEventListener('keydown', (e) => {
                // Check for Cmd (Mac) or Ctrl (Windows/Linux)
                if ((e.metaKey || e.ctrlKey) && e.key === 'z') {
                    e.preventDefault(); // Prevent browser's default undo

                    if (e.shiftKey) {
                        // Cmd+Shift+Z = Redo
                        this.redo();
                    } else {
                        // Cmd+Z = Undo
                        this.undo();
                    }
                }
            });
        },

        /**
         * Creates the default layout structure
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
        // HISTORY MANAGEMENT (Undo/Redo)
        // ==============================================

        /**
         * How Undo/Redo Works:
         *
         * We maintain a "history stack" - an array of layout snapshots.
         * historyIndex points to the current position in the stack.
         *
         * Example:
         *   history = [state0, state1, state2, state3]
         *   historyIndex = 3 (pointing to state3, the current state)
         *
         * When user does Undo:
         *   historyIndex = 2 (now pointing to state2)
         *   layout = state2
         *
         * When user does Redo:
         *   historyIndex = 3 (back to state3)
         *   layout = state3
         *
         * When user makes a new change while at historyIndex=2:
         *   history = [state0, state1, state2, newState]  (state3 is discarded)
         *   historyIndex = 3
         */

        /**
         * Save current state to history
         */
        saveHistory() {
            // If we're not at the end of history, remove "future" states
            // This happens when user undoes, then makes a new change
            this.history = this.history.slice(0, this.historyIndex + 1);

            // Deep clone the layout (JSON parse/stringify is a simple way to deep clone)
            this.history.push(JSON.parse(JSON.stringify(this.layout)));
            this.historyIndex++;

            // Limit history to 50 entries to prevent memory issues
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        /**
         * Undo the last action
         */
        undo() {
            if (this.canUndo()) {
                this.historyIndex--;
                // Deep clone from history to avoid reference issues
                this.layout = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
            }
        },

        /**
         * Redo the last undone action
         */
        redo() {
            if (this.canRedo()) {
                this.historyIndex++;
                this.layout = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
            }
        },

        /**
         * Check if undo is available
         * We can undo if we're not at the beginning of history
         */
        canUndo() {
            return this.historyIndex > 0;
        },

        /**
         * Check if redo is available
         * We can redo if we're not at the end of history
         */
        canRedo() {
            return this.historyIndex < this.history.length - 1;
        },

        // ==============================================
        // ACTIONS (Reset, Export)
        // ==============================================

        /**
         * Reset layout to default state
         */
        resetLayout() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();
            this.toast('Layout reset');
        },

        /**
         * Copy layout JSON to clipboard
         * Uses the modern Clipboard API
         */
        copyToClipboard() {
            // Format the JSON with 2-space indentation for readability
            const json = JSON.stringify(this.layout, null, 2);

            // navigator.clipboard.writeText() returns a Promise
            navigator.clipboard.writeText(json).then(() => {
                this.toast('JSON copied to clipboard!');
            }).catch((err) => {
                this.toast('Failed to copy');
                console.error('Clipboard error:', err);
            });
        },

        /**
         * Show a toast notification
         * @param {string} message - Message to display
         */
        toast(message) {
            this.toastMessage = message;
            this.showToast = true;

            // Auto-hide after 2 seconds
            setTimeout(() => {
                this.showToast = false;
            }, 2000);
        },

        // ==============================================
        // SLOT COUNTING & VALIDATION
        // ==============================================

        /**
         * Get total number of slots across all rows
         */
        getTotalSlots() {
            let count = 0;
            this.layout.forEach(row => {
                row.children.forEach(child => {
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
         */
        canDelete() {
            return this.getTotalSlots() > 1;
        },

        // ==============================================
        // ADD SLOT OPERATIONS
        // ==============================================

        /**
         * Add a new slot in the specified direction
         */
        addSlot(rowIndex, slotIndex, direction) {
            const newSlot = {
                id: generateId(),
                type: 'slot',
                name: 'New Slot',
                component: null,
                width: null,
                widthMode: 'fluid',
                fixedWidth: null
            };

            if (direction === 'left' || direction === 'right') {
                const row = this.layout[rowIndex];
                const insertIdx = direction === 'left' ? slotIndex : slotIndex + 1;

                const newSlotCount = row.children.length + 1;
                const baseWidth = Math.floor(12 / newSlotCount);
                const remainder = 12 - (baseWidth * newSlotCount);

                row.children.forEach((slot, idx) => {
                    slot.width = baseWidth + (idx < remainder ? 1 : 0);
                });

                newSlot.width = baseWidth;
                row.children.splice(insertIdx, 0, newSlot);
                this.normalizeRowWidths(rowIndex);

            } else {
                newSlot.width = 12;
                const newRow = {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [newSlot]
                };

                const insertIdx = direction === 'top' ? rowIndex : rowIndex + 1;
                this.layout.splice(insertIdx, 0, newRow);
            }

            this.saveHistory();
        },

        // ==============================================
        // DELETE SLOT OPERATIONS
        // ==============================================

        /**
         * Delete a slot from the layout
         */
        deleteSlot(rowIndex, slotIndex) {
            if (!this.canDelete()) return;

            const row = this.layout[rowIndex];
            const deletedWidth = row.children[slotIndex].width ||
                Math.floor(12 / row.children.length);

            row.children.splice(slotIndex, 1);

            if (row.children.length === 0) {
                this.layout.splice(rowIndex, 1);
            } else {
                const widthPerSlot = Math.floor(deletedWidth / row.children.length);
                const remainder = deletedWidth - (widthPerSlot * row.children.length);

                row.children.forEach((slot, idx) => {
                    const currentWidth = slot.width ||
                        Math.floor(12 / (row.children.length + 1));
                    slot.width = currentWidth + widthPerSlot + (idx < remainder ? 1 : 0);
                });

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
         */
        normalizeRowWidths(rowIndex) {
            const row = this.layout[rowIndex];
            const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');
            if (fluidSlots.length === 0) return;

            const totalFluidWidth = fluidSlots.reduce((sum, s) => sum + (s.width || 1), 0);

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
