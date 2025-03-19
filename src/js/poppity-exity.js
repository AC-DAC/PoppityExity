(function($) {
    'use strict';

    let hasShownModal = false;
    let modalTimeout = null;

    function showModal() {
        if (!hasShownModal) {
            const modal = $('#poppity-exity-modal');
            modal.addClass('show');
            hasShownModal = true;
        }
    }

    function hideModal() {
        const modal = $('#poppity-exity-modal');
        modal.removeClass('show');
    }

    function handleMouseLeave(e) {
        if (e.clientY <= 0) {
            clearTimeout(modalTimeout);
            modalTimeout = setTimeout(showModal, 100);
        }
    }

    $(document).ready(function() {
        // Exit intent detection
        $(document).on('mouseleave', handleMouseLeave);

        // Close button and overlay click handlers
        $('.poppity-exity-close').on('click', hideModal);
        $('#poppity-exity-modal').on('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });

        // Escape key handler
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                hideModal();
            }
        });
    });
})(jQuery);