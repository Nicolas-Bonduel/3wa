<div class="highlight-nav">

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
        <path fill="currentColor" d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/>
    </svg>
    <span>
        Nos Catégories
    </span>

    @if (isset($categories) && count($categories))
        <div class="hover-content left no-arrow">
            <ul>
                @foreach($categories as $cat)
                    @php
                        // constructs the link to the products page with corresponding filters
                        $subcategories_ids = $cat->subcategories()->getResults()
                            ->pluck('id')
                            ->toArray();
                        $subcategories_filter = [];
                        array_walk($subcategories_ids, function($id) use(&$subcategories_filter) {
                            $subcategories_filter[$id] = "1";
                        });

                        $subcategories_filters = http_build_query(['subcategory' => $subcategories_filter]);
                        $subcategories_filters = str_replace('&', '%26', $subcategories_filters);
                    @endphp
                    <li>
                        <a
                            href="/products?filters={{ $subcategories_filters }}"
                            wire:navigate
                        >
                            <span>{{ htmlspecialchars_decode($cat->label) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="18" height="18">
                                <path fill="currentColor" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/>
                            </svg>
                        </a>
                        <div class="sub-menu">
                            @foreach ($cat->subcategories()->getResults()->sortBy(fn($s) => $s->label) as $subcat)
                                <a
                                    href="/products?filters={{ http_build_query(['subcategory' => [$subcat->id => true]]) }}"
                                    wire:navigate
                                >
                                    {{ htmlspecialchars_decode($subcat->label) }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
