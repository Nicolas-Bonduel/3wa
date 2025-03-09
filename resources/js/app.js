import './bootstrap';


let previous_url = null;
document.addEventListener('livewire:navigate', () => {
    previous_url = window.location.href;
});
document.addEventListener('livewire:initialized', () => {
    Livewire.on('get-previous-url', () => {
        Livewire.dispatch('set-previous-url', { previous_url: previous_url });
    });
});

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

