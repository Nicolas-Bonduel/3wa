import { debounce } from '../util.js';

document.addEventListener('livewire:navigated', () => {
    const $quick_search_input = document.querySelector('.quick-search .input input[type="text"]');
    const $quick_search_result = document.querySelector('.quick-search .result');

    // placeholder text adapts to screen size
    const refreshQuicksearchPlaceholder = () => {
        if (! $quick_search_input)
            return;

        $quick_search_input.placeholder = window.innerWidth >= 768 ?
            "Rechercher par référence, par article, par marque..."
            :
            "..."
        ;
    }
    refreshQuicksearchPlaceholder();
    window.addEventListener('resize', debounce(refreshQuicksearchPlaceholder));

    // close quick search results on click  elsewhere
    if ($quick_search_input && $quick_search_result) {
        $quick_search_input.addEventListener('click', (e) => {
            $quick_search_result.classList.add('visible');
            e.stopPropagation();
        });
        $quick_search_result.addEventListener('click', (e) => e.stopPropagation());
        window.addEventListener('click', () => $quick_search_result.classList.remove('visible'));
    }

})
