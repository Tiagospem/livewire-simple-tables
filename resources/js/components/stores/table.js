document.addEventListener('alpine:init', () => {
    window.Alpine.store('table', {
        var: 'foo',
    });

    window.editOnClickValidation = window.Alpine.store('table')
})