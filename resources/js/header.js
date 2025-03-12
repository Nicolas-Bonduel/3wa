/**
 * Good old toggler for the mobile menu
 * did this before I implemented livewire, this is a relic of a forsaken era !
 */
document.addEventListener('livewire:navigated', () => {

    const $menu_mobile_toggle = document.querySelector('#mobile-menu .toggle');
    const $menu_mobile_wrapper = document.querySelector('#mobile-menu');
    const $menu_mobile_overlay = document.querySelector('#mobile-menu .overlay');
    const $menu_mobile_close = document.querySelector('#mobile-menu .menu .header .close-btn');

    const toggleMobileMenu = () => {
        if (! $menu_mobile_wrapper)
            return;

        $menu_mobile_wrapper.classList.toggle('visible');
    }

    // toggle buttons
    if ($menu_mobile_toggle)
        $menu_mobile_toggle.addEventListener('click', toggleMobileMenu);
    if ($menu_mobile_close)
        $menu_mobile_close.addEventListener('click', toggleMobileMenu);

    // toggle on overlay click
    if ($menu_mobile_overlay)
        $menu_mobile_overlay.addEventListener('click', toggleMobileMenu);

});
