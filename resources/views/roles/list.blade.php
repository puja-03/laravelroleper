<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles Management') }}
            </h2>
            @can('create roles')
            <a href="{{ route('roles.create') }}" 
               class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                {{ __('Create New Role') }}
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Permissions
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $role->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @forelse($role->permissions as $permission)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-2">
                                                    {{ $permission->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 text-xs">No permissions assigned</span>
                                            @endforelse
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                               @can(abilities: 'edit roles')

                                            <a href="{{ route('roles.edit', $role->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </a>
                                            @endcan
                                            @can(abilities: 'delete roles')
                                            @if($role->name !== 'admin')
                                                <form action="{{ route('roles.destroy', $role->id) }}" 
                                                      method="POST" 
                                                      class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to delete this role?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                            @endcan
                                        </td>
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-sm">

                                            <a href="{{ route('roles.edit', $role->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </a>
                                            
                                            @if($role->name !== 'admin')
                                                <form action="{{ route('roles.destroy', $role->id) }}" 
                                                      method="POST" 
                                                      class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to delete this role?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                            
                                        </td> -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No roles found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($roles->hasPages())
                        <div class="mt-4">
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>