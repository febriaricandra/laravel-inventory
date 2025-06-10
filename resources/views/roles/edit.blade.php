<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $role->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label :value="__('Permissions')" />
                            
                            @php
                                $groupedPermissions = $permissions->groupBy(function($permission) {
                                    return explode('.', $permission->name)[0];
                                });
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                @foreach($groupedPermissions as $group => $permissions)
                                    <div class="p-4 border rounded-lg">
                                        <h3 class="font-semibold text-lg mb-3 capitalize">{{ $group }}</h3>
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" 
                                                    id="permission_{{ $permission->id }}"
                                                    name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-600">
                                                    {{ str_replace($group . '.', '', $permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Role') }}</x-primary-button>
                            <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>