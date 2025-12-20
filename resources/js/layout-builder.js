/**
 * Layout Builder - Alpine.js Component
 */

function generateId() {
    return 'slot_' + Math.random().toString(36).substring(2, 11);
}

export default function layoutBuilder() {
    return {
        // ==============================================
        // STATE
        // ==============================================

        layout: [],
        history: [],
        historyIndex: -1,
        showToast: false,
        toastMessage: '',

        /**
         * Row Width Presets
         *
         * Maps preset keys to Tailwind max-width classes and pixel values.
         * These are the "boxed" width options users can choose from.
         *
         * Why use presets?
         * - Consistent widths across the app
         * - Maps directly to Tailwind classes (no custom CSS needed)
         * - Easy to display human-readable labels
         */
        rowWidthPresets: {
            'sm':  { label: 'Small (640px)',   class: 'max-w-screen-sm', value: 640 },
            'md':  { label: 'Medium (768px)',  class: 'max-w-screen-md', value: 768 },
            'lg':  { label: 'Large (1024px)',  class: 'max-w-screen-lg', value: 1024 },
            'xl':  { label: 'XL (1280px)',     class: 'max-w-screen-xl', value: 1280 },
            '2xl': { label: '2XL (1536px)',    class: 'max-w-screen-2xl', value: 1536 },
            '5xl': { label: '5XL (1024px)',    class: 'max-w-5xl', value: 1024 },
            '6xl': { label: '6XL (1152px)',    class: 'max-w-6xl', value: 1152 },
            '7xl': { label: '7XL (1280px)',    class: 'max-w-7xl', value: 1280 },
        },

        // ==============================================
        // INITIALIZATION
        // ==============================================

        init() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();

            document.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key === 'z') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        this.redo();
                    } else {
                        this.undo();
                    }
                }
            });
        },

        getDefaultLayout() {
            return [
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        { id: generateId(), type: 'slot', name: 'Header', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        { id: generateId(), type: 'slot', name: 'Content', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                },
                {
                    id: generateId(),
                    type: 'row',
                    rowWidthMode: 'boxed',
                    maxWidth: '7xl',
                    fixedWidth: null,
                    children: [
                        { id: generateId(), type: 'slot', name: 'Footer', component: null, width: 12, widthMode: 'fluid', fixedWidth: null }
                    ]
                }
            ];
        },

        // ==============================================
        // HISTORY MANAGEMENT
        // ==============================================

        saveHistory() {
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(JSON.parse(JSON.stringify(this.layout)));
            this.historyIndex++;

            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        undo() {
            if (this.canUndo()) {
                this.historyIndex--;
                this.layout = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
            }
        },

        redo() {
            if (this.canRedo()) {
                this.historyIndex++;
                this.layout = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
            }
        },

        canUndo() {
            return this.historyIndex > 0;
        },

        canRedo() {
            return this.historyIndex < this.history.length - 1;
        },

        // ==============================================
        // ROW WIDTH CONTROL
        // ==============================================

        /**
         * Get classes for the outer row wrapper (flex container)
         *
         * The wrapper is always full-width flex. Its job is to:
         * - Center the content (justify-center) for boxed/fixed modes
         * - Let content stretch full width for full mode
         *
         * @param {object} row - The row object
         * @returns {string} Space-separated CSS classes
         */
        getRowWrapperClass(row) {
            const classes = ['flex', 'w-full'];

            // Center content for boxed and fixed modes
            if (row.rowWidthMode === 'boxed' || row.rowWidthMode === 'fixed') {
                classes.push('justify-center');
            }

            return classes.join(' ');
        },

        /**
         * Get classes for the inner row content (grid container)
         *
         * This is where the 12-column grid lives.
         * Width is controlled by:
         * - 'full': No max-width constraint
         * - 'boxed': Tailwind max-width class from preset
         * - 'fixed': Inline style (see getRowContentStyle)
         *
         * @param {object} row - The row object
         * @returns {string} Space-separated CSS classes
         */
        getRowContentClass(row) {
            const classes = ['grid', 'grid-cols-12', 'gap-10', 'w-full'];

            // Add max-width class for boxed mode
            if (row.rowWidthMode === 'boxed' && row.maxWidth) {
                const preset = this.rowWidthPresets[row.maxWidth];
                if (preset) {
                    classes.push(preset.class);
                }
            }

            return classes.join(' ');
        },

        /**
         * Get inline styles for the row content
         *
         * Used for fixed pixel widths since we can't use
         * Tailwind classes for arbitrary values like "max-width: 847px"
         *
         * @param {object} row - The row object
         * @returns {string} Inline style string or empty
         */
        getRowContentStyle(row) {
            if (row.rowWidthMode === 'fixed' && row.fixedWidth) {
                return `max-width: ${row.fixedWidth}px;`;
            }
            return '';
        },

        /**
         * Set the row width mode
         *
         * @param {number} rowIndex - Index of the row to modify
         * @param {string} mode - 'full' | 'boxed' | 'fixed'
         * @param {string|number|null} value - Preset key for boxed, pixels for fixed
         */
        setRowWidthMode(rowIndex, mode, value = null) {
            const row = this.layout[rowIndex];

            row.rowWidthMode = mode;

            if (mode === 'full') {
                // Full width - no constraints
                row.maxWidth = null;
                row.fixedWidth = null;
            } else if (mode === 'boxed') {
                // Boxed - use preset key (e.g., '7xl')
                row.maxWidth = value || '7xl';
                row.fixedWidth = null;
            } else if (mode === 'fixed') {
                // Fixed - use pixel value
                row.maxWidth = null;
                row.fixedWidth = parseInt(value) || 960;
            }

            this.saveHistory();
        },

        /**
         * Get human-readable display text for row width
         *
         * @param {object} row - The row object
         * @returns {string} Display text like "Full Width" or "7xl"
         */
        getRowWidthDisplay(row) {
            if (row.rowWidthMode === 'full') {
                return 'Full Width';
            } else if (row.rowWidthMode === 'boxed' && row.maxWidth) {
                const preset = this.rowWidthPresets[row.maxWidth];
                return preset ? preset.label : row.maxWidth;
            } else if (row.rowWidthMode === 'fixed' && row.fixedWidth) {
                return `${row.fixedWidth}px`;
            }
            return 'Full Width';
        },

        // ==============================================
        // ACTIONS
        // ==============================================

        resetLayout() {
            this.layout = this.getDefaultLayout();
            this.saveHistory();
            this.toast('Layout reset');
        },

        copyToClipboard() {
            const json = JSON.stringify(this.layout, null, 2);
            navigator.clipboard.writeText(json).then(() => {
                this.toast('JSON copied to clipboard!');
            }).catch((err) => {
                this.toast('Failed to copy');
                console.error('Clipboard error:', err);
            });
        },

        toast(message) {
            this.toastMessage = message;
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 2000);
        },

        // ==============================================
        // SLOT COUNTING & VALIDATION
        // ==============================================

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

        canDelete() {
            return this.getTotalSlots() > 1;
        },

        // ==============================================
        // ADD SLOT OPERATIONS
        // ==============================================

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

        getSlotWidth(row, slotIndex) {
            const slot = row.children[slotIndex];
            if (slot.width) return slot.width;
            return Math.floor(12 / row.children.length);
        },

        getSlotColSpanClass(row, slotIndex) {
            const width = this.getSlotWidth(row, slotIndex);
            return `col-span-${width}`;
        }
    };
}
