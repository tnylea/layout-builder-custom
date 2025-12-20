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
         * Gap/Spacing Presets
         * Maps semantic names to Tailwind gap classes and pixel values
         */
        gapPresets: {
            'none': { class: 'gap-0', value: 0, label: 'None' },
            'xs':   { class: 'gap-1', value: 4, label: '4px' },
            'sm':   { class: 'gap-2', value: 8, label: '8px' },
            'md':   { class: 'gap-4', value: 16, label: '16px' },
            'lg':   { class: 'gap-6', value: 24, label: '24px' },
            'xl':   { class: 'gap-8', value: 32, label: '32px' },
            '2xl':  { class: 'gap-10', value: 40, label: '40px' },
            '3xl':  { class: 'gap-12', value: 48, label: '48px' },
            '4xl':  { class: 'gap-16', value: 64, label: '64px' },
        },

        /**
         * Spacing Configuration
         * Controls gaps between rows, columns, and nested slots
         */
        spacing: {
            rows: '2xl',      // Gap between rows (vertical)
            columns: '2xl',   // Gap between columns in a row (horizontal)
            nested: 'sm',     // Gap between nested slots in a column (vertical)
        },

        /**
         * Row Width Presets
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
        // SPACING/GAP CONTROL
        // ==============================================

        /**
         * Get the gap class for rows container
         */
        getRowsGapClass() {
            const preset = this.gapPresets[this.spacing.rows];
            return preset ? preset.class : 'gap-10';
        },

        /**
         * Get the gap class for columns within a row
         */
        getColumnsGapClass() {
            const preset = this.gapPresets[this.spacing.columns];
            return preset ? preset.class : 'gap-10';
        },

        /**
         * Get the gap class for nested slots within a column
         */
        getNestedGapClass() {
            const preset = this.gapPresets[this.spacing.nested];
            return preset ? preset.class : 'gap-2';
        },

        /**
         * Set spacing for a specific type
         */
        setSpacing(type, value) {
            if (this.spacing.hasOwnProperty(type) && this.gapPresets.hasOwnProperty(value)) {
                this.spacing[type] = value;
                this.saveHistory();
            }
        },

        /**
         * Apply a spacing preset to all gaps
         */
        applySpacingPreset(preset) {
            const presets = {
                'compact': { rows: 'md', columns: 'md', nested: 'xs' },
                'comfortable': { rows: '2xl', columns: '2xl', nested: 'sm' },
                'spacious': { rows: '3xl', columns: '3xl', nested: 'md' },
            };
            if (presets[preset]) {
                this.spacing = { ...presets[preset] };
                this.saveHistory();
            }
        },

        // ==============================================
        // ROW WIDTH CONTROL
        // ==============================================

        getRowWrapperClass(row) {
            const classes = ['flex', 'w-full'];
            if (row.rowWidthMode === 'boxed' || row.rowWidthMode === 'fixed') {
                classes.push('justify-center');
            }
            return classes.join(' ');
        },

        getRowContentClass(row) {
            const hasFixedSlots = this.rowHasFixedSlots(row);
            const gapClass = this.getColumnsGapClass();

            let classes;
            if (hasFixedSlots) {
                classes = ['flex', gapClass, 'w-full'];
            } else {
                classes = ['grid', 'grid-cols-12', gapClass, 'w-full'];
            }

            if (row.rowWidthMode === 'boxed' && row.maxWidth) {
                const preset = this.rowWidthPresets[row.maxWidth];
                if (preset) {
                    classes.push(preset.class);
                }
            }

            return classes.join(' ');
        },

        getRowContentStyle(row) {
            if (row.rowWidthMode === 'fixed' && row.fixedWidth) {
                return `max-width: ${row.fixedWidth}px;`;
            }
            return '';
        },

        rowHasFixedSlots(row) {
            return row.children.some(item => {
                if (item.type === 'column') {
                    return item.widthMode === 'fixed' && item.fixedWidth;
                }
                return item.widthMode === 'fixed' && item.fixedWidth;
            });
        },

        setRowWidthMode(rowIndex, mode, value = null) {
            const row = this.layout[rowIndex];
            row.rowWidthMode = mode;

            if (mode === 'full') {
                row.maxWidth = null;
                row.fixedWidth = null;
            } else if (mode === 'boxed') {
                row.maxWidth = value || '7xl';
                row.fixedWidth = null;
            } else if (mode === 'fixed') {
                row.maxWidth = null;
                row.fixedWidth = parseInt(value) || 960;
            }

            this.saveHistory();
        },

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
        // SLOT WIDTH CONTROL
        // ==============================================

        getSlotWidth(row, slotIndex) {
            const item = row.children[slotIndex];
            if (item.width) return item.width;
            return Math.floor(12 / row.children.length);
        },

        getSlotStyle(row, slotIndex) {
            const item = row.children[slotIndex];

            if (item.widthMode === 'fixed' && item.fixedWidth) {
                return `width: ${item.fixedWidth}px;`;
            }

            return '';
        },

        getSlotColSpanClass(row, slotIndex) {
            const item = row.children[slotIndex];
            const hasFixedSlots = this.rowHasFixedSlots(row);

            if (hasFixedSlots) {
                if (item.widthMode === 'fixed' && item.fixedWidth) {
                    return 'flex-shrink-0';
                } else {
                    return 'flex-1';
                }
            }

            const width = this.getSlotWidth(row, slotIndex);
            return `col-span-${width}`;
        },

        setFluidWidth(rowIndex, slotIndex, newWidth) {
            const row = this.layout[rowIndex];
            const slot = row.children[slotIndex];
            const currentWidth = slot.width || Math.floor(12 / row.children.length);
            const delta = newWidth - currentWidth;

            if (delta === 0) return;

            row.children.forEach((s, idx) => {
                if (!s.width) {
                    s.width = Math.floor(12 / row.children.length);
                }
                if (idx === slotIndex) {
                    s.widthMode = 'fluid';
                    s.fixedWidth = null;
                }
            });

            const siblings = row.children.filter((_, idx) =>
                idx !== slotIndex && row.children[idx].widthMode !== 'fixed'
            );

            if (siblings.length === 0) {
                slot.width = newWidth;
            } else {
                const availableWidth = siblings.reduce((sum, s) => sum + s.width, 0);
                const neededFromSiblings = delta;

                if (neededFromSiblings > 0 && neededFromSiblings <= availableWidth - siblings.length) {
                    slot.width = newWidth;
                    let remaining = neededFromSiblings;
                    siblings.forEach((sibling, idx) => {
                        const siblingIdx = row.children.indexOf(sibling);
                        const share = idx === siblings.length - 1
                            ? remaining
                            : Math.max(1, Math.round((sibling.width / availableWidth) * neededFromSiblings));
                        row.children[siblingIdx].width = Math.max(1, sibling.width - share);
                        remaining -= share;
                    });
                } else if (neededFromSiblings < 0) {
                    slot.width = newWidth;
                    const extraWidth = Math.abs(neededFromSiblings);
                    const siblingIdx = row.children.indexOf(siblings[0]);
                    row.children[siblingIdx].width += extraWidth;
                }
            }

            this.normalizeRowWidths(rowIndex);
            this.saveHistory();
        },

        setFixedWidth(rowIndex, slotIndex, pxValue) {
            const row = this.layout[rowIndex];
            const slot = row.children[slotIndex];

            slot.widthMode = 'fixed';
            slot.fixedWidth = parseInt(pxValue) || 300;

            this.redistributeWidths(rowIndex);
            this.saveHistory();
        },

        redistributeWidths(rowIndex) {
            const row = this.layout[rowIndex];
            const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');

            if (fluidSlots.length === 0) return;

            const widthPerSlot = Math.floor(12 / fluidSlots.length);
            const remainder = 12 - (widthPerSlot * fluidSlots.length);

            fluidSlots.forEach((slot, idx) => {
                slot.width = widthPerSlot + (idx < remainder ? 1 : 0);
            });
        },

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

        /**
         * Get total number of slots across all rows
         * Counts nested slots inside columns
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

        canDelete() {
            return this.getTotalSlots() > 1;
        },

        // ==============================================
        // ADD SLOT OPERATIONS
        // ==============================================

        /**
         * Add a new slot in the specified direction
         *
         * Behavior:
         * - LEFT/RIGHT: Add slot to the same row, redistribute widths
         * - TOP/BOTTOM:
         *   - If row has only 1 slot: Add as new row
         *   - If row has multiple slots (split row): Convert to column
         *     and stack slots vertically
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
                // Horizontal: add to same row
                const row = this.layout[rowIndex];
                const insertIdx = direction === 'left' ? slotIndex : slotIndex + 1;

                const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');
                const newFluidCount = fluidSlots.length + 1;
                const baseWidth = Math.floor(12 / newFluidCount);
                const remainder = 12 - (baseWidth * newFluidCount);

                let fluidIdx = 0;
                row.children.forEach((slot) => {
                    if (slot.widthMode !== 'fixed') {
                        slot.width = baseWidth + (fluidIdx < remainder ? 1 : 0);
                        fluidIdx++;
                    }
                });

                newSlot.width = baseWidth;
                row.children.splice(insertIdx, 0, newSlot);
                this.normalizeRowWidths(rowIndex);

            } else {
                // Vertical: top or bottom
                const row = this.layout[rowIndex];

                // Check if this is a split row (more than one child)
                if (row.children.length > 1) {
                    /**
                     * NESTED COLUMNS LOGIC
                     *
                     * When a row has multiple slots and user adds top/bottom,
                     * we convert the slot into a "column" that can contain
                     * multiple stacked slots.
                     *
                     * Before: Row → [Slot A, Slot B]
                     * After:  Row → [Column(Slot A, New Slot), Slot B]
                     */
                    const currentItem = row.children[slotIndex];

                    if (currentItem.type === 'column') {
                        // Already a column, add to its children
                        const insertIdx = direction === 'top' ? 0 : currentItem.children.length;
                        currentItem.children.splice(insertIdx, 0, newSlot);
                    } else {
                        // Convert slot to column
                        const column = {
                            id: generateId(),
                            type: 'column',
                            width: currentItem.width,
                            widthMode: currentItem.widthMode,
                            fixedWidth: currentItem.fixedWidth,
                            children: direction === 'top'
                                ? [newSlot, currentItem]
                                : [currentItem, newSlot]
                        };

                        // Clear width from original slot (column handles it now)
                        currentItem.width = null;
                        currentItem.widthMode = 'fluid';
                        currentItem.fixedWidth = null;

                        row.children[slotIndex] = column;
                    }
                } else {
                    // Single slot row: add as new row
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
            }

            this.saveHistory();
        },

        // ==============================================
        // NESTED SLOT OPERATIONS
        // ==============================================

        /**
         * Add a slot within a column (nested)
         *
         * @param {number} rowIndex - Row containing the column
         * @param {number} columnIndex - Index of the column in the row
         * @param {number} nestedIndex - Index of the slot we're adding relative to
         * @param {string} direction - 'top' or 'bottom'
         */
        addNestedSlot(rowIndex, columnIndex, nestedIndex, direction) {
            const column = this.layout[rowIndex].children[columnIndex];
            if (column.type !== 'column') return;

            const newSlot = {
                id: generateId(),
                type: 'slot',
                name: 'New Slot',
                component: null,
                width: null,
                widthMode: 'fluid',
                fixedWidth: null
            };

            const insertIdx = direction === 'top' ? nestedIndex : nestedIndex + 1;
            column.children.splice(insertIdx, 0, newSlot);

            this.saveHistory();
        },

        /**
         * Delete a nested slot from within a column
         */
        deleteNestedSlot(rowIndex, columnIndex, nestedIndex) {
            if (!this.canDelete()) return;

            const row = this.layout[rowIndex];
            const column = row.children[columnIndex];

            if (column.type !== 'column') return;

            column.children.splice(nestedIndex, 1);

            // If only one slot remains in column, convert back to regular slot
            if (column.children.length === 1) {
                const remainingSlot = column.children[0];
                remainingSlot.width = column.width;
                remainingSlot.widthMode = column.widthMode;
                remainingSlot.fixedWidth = column.fixedWidth;
                row.children[columnIndex] = remainingSlot;
            }

            // If column is empty, remove it
            if (column.children.length === 0) {
                const deletedWidth = column.width || Math.floor(12 / row.children.length);
                row.children.splice(columnIndex, 1);

                if (row.children.length === 0) {
                    this.layout.splice(rowIndex, 1);
                } else {
                    // Redistribute widths
                    const widthPerSlot = Math.floor(deletedWidth / row.children.length);
                    const remainder = deletedWidth - (widthPerSlot * row.children.length);

                    row.children.forEach((slot, idx) => {
                        slot.width = (slot.width || Math.floor(12 / (row.children.length + 1))) + widthPerSlot + (idx < remainder ? 1 : 0);
                    });

                    const total = row.children.reduce((sum, s) => sum + (s.width || 0), 0);
                    if (total !== 12 && row.children.length > 0) {
                        row.children[0].width = (row.children[0].width || 0) + (12 - total);
                    }
                }
            }

            this.saveHistory();
        },

        /**
         * Update a nested slot's name
         */
        updateNestedSlotName(rowIndex, columnIndex, nestedIndex, newName) {
            const trimmed = newName.trim();
            if (trimmed) {
                const column = this.layout[rowIndex].children[columnIndex];
                if (column.type === 'column' && column.children[nestedIndex]) {
                    column.children[nestedIndex].name = trimmed;
                    this.saveHistory();
                }
            }
        },

        // ==============================================
        // DELETE SLOT OPERATIONS
        // ==============================================

        deleteSlot(rowIndex, slotIndex) {
            if (!this.canDelete()) return;

            const row = this.layout[rowIndex];
            const deletedItem = row.children[slotIndex];
            const wasFluid = deletedItem.widthMode !== 'fixed';
            const deletedWidth = deletedItem.width || Math.floor(12 / row.children.length);

            row.children.splice(slotIndex, 1);

            if (row.children.length === 0) {
                this.layout.splice(rowIndex, 1);
            } else if (wasFluid) {
                const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');

                if (fluidSlots.length > 0) {
                    const widthPerSlot = Math.floor(deletedWidth / fluidSlots.length);
                    const remainder = deletedWidth - (widthPerSlot * fluidSlots.length);

                    fluidSlots.forEach((slot, idx) => {
                        slot.width = (slot.width || Math.floor(12 / fluidSlots.length)) + widthPerSlot + (idx < remainder ? 1 : 0);
                    });

                    this.normalizeRowWidths(rowIndex);
                }
            }

            this.saveHistory();
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
        }
    };
}
