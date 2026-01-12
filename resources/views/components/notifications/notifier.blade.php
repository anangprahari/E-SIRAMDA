<script>
    window.notify = {
        error(message) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { type: 'error', message }
            }));
        },

        success(message) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { type: 'success', message }
            }));
        },

        info(message) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { type: 'info', message }
            }));
        },

        confirm(options) {
            const modal = Alpine.$data(
                document.querySelector('[x-data*="confirmModal"]')
            );

            modal.show(options);
        }
    };
</script>
