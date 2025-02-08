export default () => ({
    manageClick({ actionUrl = null, actionTarget = '_parent', eventName = null, eventParams = null }) {
        if(actionUrl) {
            window.open(actionUrl, actionTarget);
        }else if (eventName) {
            Livewire.dispatch(eventName, [eventParams]);
        }
    },
})