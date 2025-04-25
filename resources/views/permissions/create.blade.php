<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($permission) ? __('Edit Permission') : __('Create Permission') }}
        </h2>
        @can ('view permissions')
        <a href="{{ route('permissions.index') }}" class="text-sm text-gray-700 underline">
            {{ __('Back to Permissions List') }}
        </a>
        @endcan
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" 
                          action="{{ isset($permission) ? route('permissions.update', $permission->id) : route('permissions.store') }}" 
                          class="space-y-6">
                        @csrf
                        @if(isset($permission))
                            @method('PUT')
                        @endif

                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Permission Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $permission->name ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ isset($permission) ? 'Update' : 'Create' }} Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>