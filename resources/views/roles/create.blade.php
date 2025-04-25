<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($role) ? __('Edit Role') : __('Create Role') }}
        </h2>
        @can('view roles')
        <a href="{{ route('roles.index') }}" class="text-sm text-gray-700 underline">
            {{ __('Back to Roles List') }}
        </a>
        @endcan
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST"
                        action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}"
                        class="space-y-6">
                        @csrf
                        @if(isset($role))
                        @method('PUT')
                        @endif

                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Role Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Permissions</label>

                            @if ($permissions->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}"
                                        value="{{ $permission->id }}"
                                        {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}
                                        {{ isset($role) && $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-gray-500">No permissions available.</p>
                            @endif

                            @error('permissions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ isset($role) ? 'Update' : 'Create' }} Role
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>