<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Edit Setting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <x-input-label for="configKey" :value="__('Key')" />
                            <x-text-input id="configKey" name="configKey" type="text" class="mt-1 block w-full" 
                                :value="old('configKey', $settings->configKey)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('configKey')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="configValue" :value="__('Value')" />
                            <x-text-input id="configValue" name="configValue" type="text" class="mt-1 block w-full" 
                                :value="old('configValue', $settings->configValue)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('configValue')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                :value="old('name', $settings->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="path" :value="__('Path')" />
                            <x-text-input id="path" name="path" type="text" class="mt-1 block w-full" 
                                :value="old('path', $settings->path)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('path')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Setting') }}</x-primary-button>
                            <a href="{{ route('settings.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>