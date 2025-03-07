<form id="add-user-form" {{ $visible ? 'class=visible' : '' }} wire:submit="addUser" wire:click.outside="hideForm">

    <!-- Email -->
    <div class="form-group">
        <x-input-label for="email" :value="__('Email')" />

        <x-text-input wire:model.blur="email" id="email"
                      class="{{ $email ? ($errors->get('email') ? 'invalid' : 'valid') : '' }}"
                      type="text"
                      name="email"
                      autofocus autocomplete="username" />

        <x-input-error :messages="$errors->get('email')" class="validation-errors" />
    </div>

    <!-- Password -->
    <div class="form-group relative">
        <x-input-label for="password" :value="__('Mot de passe')" />

        <x-text-input wire:model.blur="password" id="password"
                      class="{{ $password ? ($errors->get('password') ? 'invalid' : 'valid') : '' }}"
                      type="password"
                      name="password"
                      autocomplete="current-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />

        <x-text-input wire:model.blur="password_confirmation" id="password_confirmation"
                      class="{{ $password_confirmation ? ($errors->get('password_confirmation') ? 'invalid' : 'valid') : '' }}"
                      type="password"
                      name="password_confirmation"
                      autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password_confirmation')" class="validation-errors" />
    </div>

    <!-- Country -->
    <div class="form-group">
        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="role">
            Rôle
        </label>

        <select wire:model.blur="role"  id="role"
                class="{{ $role ? ($errors->get('role') ? 'invalid' : 'valid') : 'valid' }}"
                name="role" >
            <option value="user" selected>user</option>
            @if (auth('user')->user()->role === 'super admin')
                <option value="admin">admin</option>
            @endif
        </select>

        <x-input-error :messages="$errors->get('role')" class="validation-errors" />
    </div>


    <button type="submit" class="btn-primary mt-3">
        {{ __('Ajouter') }}
    </button>

</form>