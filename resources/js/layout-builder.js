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

        getRowWrapperClass(row) {
            const classes = ['flex', 'w-full'];
            if (row.rowWidthMode === 'boxed' || row.rowWidthMode === 'fixed') {
                classes.push('justify-center');
            }
            return classes.join(' ');
        },

        /**
         * Get classes for the inner row content
         *
         * IMPORTANT: This method now handles the Grid vs Flex decision.
         *
         * When a row has ANY fixed-width slots:
         * - We use Flexbox instead of Grid
         * - Fixed slots get their exact pixel width
         * - Fluid slots use flex-1 to fill remaining space
         *
         * When all slots are fluid:
         * - We use CSS Grid with 12 columns
         * - Each slot uses col-span-X
         */
        getRowContentClass(row) {
            const hasFixedSlots = this.rowHasFixedSlots(row);

            let classes;
            if (hasFixedSlots) {
                // Flexbox for rows with fixed-width slots
                classes = ['flex', 'gap-10', 'w-full'];
            } else {
                // Grid for rows with all fluid slots
                classes = ['grid', 'grid-cols-12', 'gap-10', 'w-full'];
            }

            // Add max-width class for boxed mode
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

        /**
         * Check if a row has any fixed-width slots
         * Used to determine if we should use Flexbox or Grid
         */
        rowHasFixedSlots(row) {
            return row.children.some(slot => slot.widthMode === 'fixed' && slot.fixedWidth);
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

        /**
         * Get the width of a slot (defaults to even distribution)
         */
        getSlotWidth(row, slotIndex) {
            const slot = row.children[slotIndex];
            if (slot.width) return slot.width;
            return Math.floor(12 / row.children.length);
        },

        /**
         * Get inline style for a slot
         * Used for fixed pixel width slots
         */
        getSlotStyle(row, slotIndex) {
            const slot = row.children[slotIndex];

            if (slot.widthMode === 'fixed' && slot.fixedWidth) {
                return `width: ${slot.fixedWidth}px;`;
            }

            return '';
        },

        /**
         * Get the class for a slot's column span
         *
         * Behavior depends on whether the row has fixed-width slots:
         *
         * Row with fixed slots (Flexbox mode):
         * - Fixed slots: flex-shrink-0 (don't grow or shrink)
         * - Fluid slots: flex-1 (grow to fill remaining space)
         *
         * Row with all fluid slots (Grid mode):
         * - All slots: col-span-X (X columns out of 12)
         */
        getSlotColSpanClass(row, slotIndex) {
            const slot = row.children[slotIndex];
            const hasFixedSlots = this.rowHasFixedSlots(row);

            // Flexbox mode (row has fixed-width slots)
            if (hasFixedSlots) {
                if (slot.widthMode === 'fixed' && slot.fixedWidth) {
                    // Fixed slot: don't grow or shrink
                    return 'flex-shrink-0';
                } else {
                    // Fluid slot: grow to fill remaining space
                    return 'flex-1';
                }
            }

            // Grid mode (all fluid slots)
            const width = this.getSlotWidth(row, slotIndex);
            return `col-span-${width}`;
        },

        /**
         * Set fluid width for a slot (1-12 columns)
         *
         * When changing a slot's width, we need to adjust sibling widths
         * to ensure the total still equals 12.
         */
        setFluidWidth(rowIndex, slotIndex, newWidth) {
            const row = this.layout[rowIndex];
            const slot = row.children[slotIndex];
            const currentWidth = slot.width || Math.floor(12 / row.children.length);
            const delta = newWidth - currentWidth;

            if (delta === 0) return;

            // Initialize all widths if needed
            row.children.forEach((s, idx) => {
                if (!s.width) {
                    s.width = Math.floor(12 / row.children.length);
                }
                // Set this slot to fluid mode
                if (idx === slotIndex) {
                    s.widthMode = 'fluid';
                    s.fixedWidth = null;
                }
            });

            // Find fluid siblings to redistribute width
            const siblings = row.children.filter((_, idx) =>
                idx !== slotIndex && row.children[idx].widthMode !== 'fixed'
            );

            if (siblings.length === 0) {
                // No fluid siblings, just set the width
                slot.width = newWidth;
            } else {
                // Calculate available width from siblings
                const availableWidth = siblings.reduce((sum, s) => sum + s.width, 0);
                const neededFromSiblings = delta;

                if (neededFromSiblings > 0 && neededFromSiblings <= availableWidth - siblings.length) {
                    // Taking width from siblings
                    slot.width = newWidth;

                    // Distribute reduction proportionally
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
                    // Giving width to siblings
                    slot.width = newWidth;
                    const extraWidth = Math.abs(neededFromSiblings);
                    const siblingIdx = row.children.indexOf(siblings[0]);
                    row.children[siblingIdx].width += extraWidth;
                }
            }

            // Ensure total equals 12
            this.normalizeRowWidths(rowIndex);
            this.saveHistory();
        },

        /**
         * Set fixed pixel width for a slot
         *
         * When a slot becomes fixed-width, the row switches to Flexbox mode.
         * Remaining fluid slots will use flex-1 to share the remaining space.
         */
        setFixedWidth(rowIndex, slotIndex, pxValue) {
            const row = this.layout[rowIndex];
            const slot = row.children[slotIndex];

            slot.widthMode = 'fixed';
            slot.fixedWidth = parseInt(pxValue) || 300;

            // Redistribute widths among remaining fluid slots
            this.redistributeWidths(rowIndex);
            this.saveHistory();
        },

        /**
         * Redistribute widths evenly among fluid slots
         * Called after a slot becomes fixed-width
         */
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

        /**
         * Ensure all fluid slot widths sum to exactly 12
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

                // Only redistribute fluid slots
                const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');
                const newFluidCount = fluidSlots.length + 1;
                const baseWidth = Math.floor(12 / newFluidCount);
                const remainder = 12 - (baseWidth * newFluidCount);

                // Update existing fluid slot widths
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
            const deletedSlot = row.children[slotIndex];
            const wasFluid = deletedSlot.widthMode !== 'fixed';
            const deletedWidth = deletedSlot.width || Math.floor(12 / row.children.length);

            row.children.splice(slotIndex, 1);

            if (row.children.length === 0) {
                this.layout.splice(rowIndex, 1);
            } else if (wasFluid) {
                // Redistribute deleted fluid width to remaining fluid slots
                const fluidSlots = row.children.filter(s => s.widthMode !== 'fixed');

                if (fluidSlots.length > 0) {
                    const widthPerSlot = Math.floor(deletedWidth / fluidSlots.length);
                    const remainder = deletedWidth - (widthPerSlot * fluidSlots.length);

                    fluidSlots.forEach((slot, idx) => {
                        slot.width = (slot.width || Math.floor(12 / fluidSlots.length)) + widthPerSlot + (idx < remainder ? 1 : 0);
                    });

                    // Normalize to ensure total is 12
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
