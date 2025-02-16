import { createPopper } from '@popperjs/core';

export default () => ({
    dropdownOpen: false,
    popperInstance: null,

    handleClick(options) {
        const {
            event = null,
            hasDropdown = false,
            disabled = false,
        } = options;

        if (disabled) return;

        if (hasDropdown) {
            this.toggleDropdown();
            return;
        }

        this.executeAction(event);
    },

    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;

        if (this.dropdownOpen) {
            this.$nextTick(() => {
                if (this.popperInstance) {
                    this.popperInstance.forceUpdate();
                }
            });
        }
    },

    executeAction(event) {
        if (event?.name) {
            this.dispatchEvent(event.name, event.params);
        }
    },

    dispatchEvent(eventName, eventParams) {
        Livewire.dispatch(eventName, [eventParams]);
    },

    init() {
        this.$nextTick(() => {
            const dropdownButton = this.$refs.dropdownButton;
            const dropdownPanel = this.$refs.dropdownPanel;

            this.popperInstance = createPopper(dropdownButton, dropdownPanel, {
                placement: 'bottom-end',
                strategy: 'fixed',
                modifiers: [
                    {
                        name: 'preventOverflow',
                        options: {
                            boundary: 'viewport',
                            padding: 10,
                            altBoundary: true,
                        },
                    },
                    {
                        name: 'computeStyles',
                        options: {
                            gpuAcceleration: false,
                        },
                    },
                    {
                        name: 'flip',
                        options: {
                            fallbackPlacements: ['top-end', 'bottom-start'],
                        },
                    },
                    {
                        name: 'offset',
                        options: {
                            offset: [0, 8],
                        },
                    },
                    {
                        name: 'eventListeners',
                        options: {
                            scroll: true,
                            resize: true,
                        },
                    },
                ],
            });
        });
    },
});
