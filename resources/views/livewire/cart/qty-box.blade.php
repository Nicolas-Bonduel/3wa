<div class="qty-box">
    @vite('resources/css/add-to-cart.scss')

    @if ($qty > 1)
        <svg wire:click="decrease" class="decrease" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="12" height="12">
            <path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/>
        </svg>
    @endif
    <input wire:model.blur="qty" class="qty {{ ($qty > $stock || $qty < 1) ? 'invalid' : '' }}"
           name="qty"
           type="number"
           title="quantity"
           value="{{ $qty }}"
           required
    />
    @if ($qty < $stock)
        <svg wire:click="increase" class="increase" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="12" height="12">
            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
        </svg>
    @endif
</div>
