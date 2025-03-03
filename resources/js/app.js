import './bootstrap';

document.addEventListener('livewire:navigated', () => {

    const onFakeLinkClick = (e) => {
        if (e.currentTarget.dataset.href) {
            Livewire.navigate(e.currentTarget.dataset.href);
            e.preventDefault();
            e.stopPropagation();
        }
    }

    const $fake_links = document.querySelectorAll('.fake-link');
    $fake_links.forEach(($el) => $el.addEventListener('click', onFakeLinkClick));

});
