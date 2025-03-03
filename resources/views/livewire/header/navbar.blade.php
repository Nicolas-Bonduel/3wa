<nav class="navbar">
    <ul>
        <li>
            <a href="{{ route('public.home') }}" wire:navigate>
                Accueil
            </a>
        </li>
        <li class="has-children">
            <a href="#">
                Marques
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="14" height="14">
                    <path fill="currentColor" d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/>
                </svg>
            </a>
            @if (isset($brands) && count($brands))
                <div class="hover-content left">
                    <ul class="brands-nav">
                        @php $n = 0; @endphp
                        @foreach($brands as $brand)
                            @php $n++; @endphp
                            <li @if ($n < 10) class="font-bold" @endif>
                                <a
                                    href="/products?filters={{ http_build_query(['brand' => [$brand->id => true]]) }}"
                                    wire:navigate
                                >
                                    {{ htmlspecialchars_decode($brand->label) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </li>
        <li>
            <a href="{{ route('public.home') }}" wire:navigate>
                Qui sommes-nous ?
            </a>
        </li>
        <li>
            <a href="{{ route('public.home') }}" wire:navigate>
                Blog
            </a>
        </li>
    </ul>
</nav>
