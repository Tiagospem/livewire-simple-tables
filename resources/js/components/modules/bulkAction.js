export default () => ({
    allSelected: false,
    selectedItems: [],

    selectId(id) {
        if (this.selectedItems.includes(id)) {
            this.selectedItems = this.selectedItems.filter(item => item !== id);
        } else {
            this.selectedItems.push(id);
        }

        this.updateComponent();
    },

    selectAll() {
        const checkboxes = document.querySelectorAll('.bulk-checkbox');

        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

        const unselectedCheckboxes = Array.from(checkboxes).filter(cb => !cb.checked);

        if(!this.allSelected) {
            unselectedCheckboxes.forEach(cb => cb.checked = true);
        }else{
            selectedCheckboxes.forEach(cb => cb.checked = false);
        }

        this.selectedItems = this.allSelected ? [] : Array.from(checkboxes).map(cb => cb.value);

        this.updateComponent();

        this.allSelected = !this.allSelected;
    },

    updateComponent() {
        this.$wire.set('selectedIds', this.selectedItems);
    }
});
