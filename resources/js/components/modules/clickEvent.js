export default () => ({
    manageClick({
        actionUrl = null,
        actionTarget = '_parent',
        eventName = null,
        eventParams = null,
        disabled = false,
    }) {
        if(disabled) {
            return;
        }

        if(actionUrl) {
            window.open(actionUrl, actionTarget);
        }else if (eventName) {
            Livewire.dispatch(eventName, [eventParams]);
        }
    },
})