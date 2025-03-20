(function($) {
    'use strict';

    let hasShownModal = false;
    let modalTimeout = null;
    let lastScrollTop = 0;
    let scrollThreshold = 30;
    let inactivityTimeout = null;
    let inactivityDelay = 30000; // 30 seconds

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

    function handleScroll() {
        const st = window.pageYOffset || document.documentElement.scrollTop;
        if (st < lastScrollTop && Math.abs(st - lastScrollTop) > scrollThreshold) {
            clearTimeout(modalTimeout);
            modalTimeout = setTimeout(showModal, 100);
        }
        lastScrollTop = st;
    }

    function resetInactivityTimer() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(showModal, inactivityDelay);
    }

    function handleVisibilityChange() {
        if (document.hidden) {
            clearTimeout(modalTimeout);
            modalTimeout = setTimeout(showModal, 100);
        }
    }

    $(document).ready(function() {
        // Exit intent detection for desktop
        $(document).on('mouseleave', handleMouseLeave);

        // Mobile-specific exit intent detection
        $(window).on('scroll', handleScroll);
        $(document).on('touchstart touchmove', resetInactivityTimer);
        document.addEventListener('visibilitychange', handleVisibilityChange);

        // Initialize inactivity timer
        resetInactivityTimer();

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