<div id="products-page" class="pt-4">

    @vite(['resources/css/products.scss'])


{{--    {{ $products->total() }}--}}

    <!-- Loader -->
    <div wire:loading>
        <div class="livewire-loader-wrapper fixed">
            <div class="loader"></div>
        </div>
    </div>

    <div class="mobile-filters">

        <button class="toggle" wire:click="toggleMobileFilters">
            Filtres
        </button>

        <div class="menu {{ $mobile_menu_visible ? 'visible' : '' }}">
            <div class="header">
                <svg wire:click="toggleMobileFilters" class="close-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
                    <path fill="currentColor" d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"></path>
                </svg>
                <span>
                    Filtres
                </span>
            </div>
            <div class="content">

                <a class="reset" href="{{ route('public.products') }}" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="18" height="18">
                        <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                    </svg>
                    <span>
                        Réinitialiser
                    </span>
                </a>

                <div class="section">
                    <span class="header">
                        Catégories
                    </span>
                    @foreach ($available_subcategories as $subcat)
                        @if (isset($filters_data['subcategory'][$subcat->id]))
                            <div class="option" wire:key="subcategory-{{ $subcat->id }}">
                                <input wire:click="toggleFilterData('subcategory', {{ $subcat->id }})" id="subcategory-{{ $subcat->id }}" type="checkbox"
                                    checked
                                />
                                <label for="subcategory-{{ $subcat->id }}">
                                    {{ htmlspecialchars_decode($subcat->label) }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                    @foreach ($available_subcategories as $subcat)
                        @if (! isset($filters_data['subcategory'][$subcat->id]))
                            <div class="option" wire:key="subcategory-{{ $subcat->id }}">
                                <input wire:click="toggleFilterData('subcategory', {{ $subcat->id }})" id="subcategory-{{ $subcat->id }}" type="checkbox"
                                />
                                <label for="subcategory-{{ $subcat->id }}">
                                    {{ htmlspecialchars_decode($subcat->label) }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="section">
                    <span class="header">
                        Marques
                    </span>
                    @foreach ($available_brands as $brand)
                        @if (isset($filters_data['brand'][$brand->id]))
                            <div class="option" wire:key="brand-{{ $brand->id }}">
                                <input wire:click="toggleFilterData('brand', {{ $brand->id }})" id="brand-{{ $brand->id }}" type="checkbox"
                                    checked
                                />
                                <label for="brand-{{ $brand->id }}">
                                    {{ htmlspecialchars_decode($brand->label) }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                    @foreach ($available_brands as $brand)
                        @if (! isset($filters_data['brand'][$brand->id]))
                            <div class="option" wire:key="brand-{{ $brand->id }}">
                                <input wire:click="toggleFilterData('brand', {{ $brand->id }})" id="brand-{{ $brand->id }}" type="checkbox"
                                />
                                <label for="brand-{{ $brand->id }}">
                                    {{ htmlspecialchars_decode($brand->label) }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="section">
                    <span class="header">
                        Gammes
                    </span>
                    @foreach ($available_ranges as $range)
                        @if (isset($filters_data['range'][$range->id]))
                        <div class="option" wire:key="range-{{ $range->id }}">
                            <input wire:click="toggleFilterData('range', {{ $range->id }})" id="range-{{ $range->id }}" type="checkbox"
                                checked
                            />
                            <label for="range-{{ $range->id }}">
                                {{ htmlspecialchars_decode($range->label) }}
                            </label>
                        </div>
                        @endif
                    @endforeach
                    @foreach ($available_ranges as $range)
                        @if (! isset($filters_data['range'][$range->id]))
                        <div class="option" wire:key="range-{{ $range->id }}">
                            <input wire:click="toggleFilterData('range', {{ $range->id }})" id="range-{{ $range->id }}" type="checkbox"
                            />
                            <label for="range-{{ $range->id }}">
                                {{ htmlspecialchars_decode($range->label) }}
                            </label>
                        </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
        <div class="overlay" wire:click="toggleMobileFilters"> </div>

    </div>

    <div class="products-listing mt-4">
        @foreach ($products as $product)
            <div wire:key="slot-{{ $product->id }}" class="slot">
                <livewire:product.card wire:key="{{ $product->id }}" :product="$product" />
            </div>
        @endforeach
    </div>

    {{ $products->links() }}

</div>
