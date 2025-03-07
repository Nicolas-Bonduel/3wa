<div class="admin-users self-center">

    <table>
        @if (auth('user')->user()->role != 'user')
            <caption>
                <div class="wrapper">
                    <button class="add-user" wire:click="addUser">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="20" height="20">
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
                        </svg>
                        <span>
                            Ajouter
                        </span>
                    </button>
                </div>
            </caption>
        @endif
        <thead>
            <tr>
                <th>
                    <input type="checkbox" />
                </th>
                <th>
                    email
                </th>
                <th>
                    rôle
                </th>
                <th>
                    créé le
                </th>
                <th>
                    m.a.j. le
                </th>
                <th>
                    actions
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr wire:key="{{ $user->id }}">
                <td>
                    <input type="checkbox" />
                </td>
                <td>
                    {{ $user->email }}
                </td>
                <td>
                    @if ($user->role === 'super admin')
                        <span class="text-red">
                            {{ $user->role }}
                        </span>
                    @elseif (auth('user')->user()->role == 'user')
                        <span>
                            {{ $user->role }}
                        </span>
                    @elseif (! in_array($user->id, $edit_role_list))
                        <a href="#" aria-label="edit role" wire:click="toggleEditRole({{ $user->id }})">
                            {{ $user->role }}
                        </a>
                    @else
                        <span class="edit-role">
                            <select wire:change="onRoleChange({{ $user->id }}, $event.target.value)">
                                <option value="{{ $user->role }}">{{ $user->role }}</option>
                                @foreach (['admin', 'user'] as $role)
                                    @if ($role !== $user->role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="check" wire:click="editRole({{ $user->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="16" height="16">
                                    <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                </svg>
                            </span>
                            <span class="cancel" wire:click="toggleEditRole({{ $user->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor" width="16" height="16">
                                    <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                                </svg>
                            </span>
                        </span>
                    @endif
                </td>
                <td>
                    <small>{{ $user->created_at->format('d/m/Y') }}</small>
                </td>
                <td>
                    <small>{{ $user->updated_at->format('d/m/Y') }}</small>
                </td>
                <td class="actions">
                    @if (auth('user')->user()->id !== $user->id && auth('user')->user()->role != 'user' && $user->role !== 'super admin')
                        <span class="login relative" wire:click="logInto({{ $user->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor" width="16" height="16">
                                <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c1.8 0 3.5-.2 5.3-.5c-76.3-55.1-99.8-141-103.1-200.2c-16.1-4.8-33.1-7.3-50.7-7.3l-91.4 0zm308.8-78.3l-120 48C358 277.4 352 286.2 352 296c0 63.3 25.9 168.8 134.8 214.2c5.9 2.5 12.6 2.5 18.5 0C614.1 464.8 640 359.3 640 296c0-9.8-6-18.6-15.1-22.3l-120-48c-5.7-2.3-12.1-2.3-17.8 0zM591.4 312c-3.9 50.7-27.2 116.7-95.4 149.7l0-187.8L591.4 312z"/>
                            </svg>
                            <span class="hover-content small rounded black top center auto">
                                <small>Se connecter à la place de l'utilisateur</small>
                            </span>
                        </span>
                    @endif
                    @if (auth('user')->user()->id !== $user->id && auth('user')->user()->role != 'user' && $user->role !== 'super admin')
                        <span class="delete relative" wire:click="deleteUser({{ $user->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor" width="16" height="16">
                                <path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L353.3 251.6C407.9 237 448 187.2 448 128C448 57.3 390.7 0 320 0C250.2 0 193.5 55.8 192 125.2L38.8 5.1zM264.3 304.3C170.5 309.4 96 387.2 96 482.3c0 16.4 13.3 29.7 29.7 29.7l388.6 0c3.9 0 7.6-.7 11-2.1l-261-205.6z"/>
                            </svg>
                            <span class="hover-content small rounded black top center auto">
                                <small>Supprimer l'utilisateur</small>
                            </span>
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if (auth('user')->user()->role != 'user')
        <livewire:admin.add-user-form />
    @endif

</div>