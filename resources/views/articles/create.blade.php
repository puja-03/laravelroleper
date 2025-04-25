<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($article) ? __('Edit Article') : __('Create Article') }}
            </h2>
            @can ('view articles')
                <a href="{{ route('articles.index') }}" 
                   class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                    {{ __('View Articles') }}
                </a>
            @endcan
            
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" 
                          action="{{ isset($article) ? route('articles.update', $article->id) : route('articles.store') }}" 
                          class="space-y-6">
                        @csrf
                        @if(isset($article))
                            @method('PUT')
                        @endif

                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Title
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $article->title ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="text" class="block text-sm font-medium text-gray-700">
                                Content
                            </label>
                            <textarea name="text" 
                                      id="text" 
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >{{ old('text', $article->text ?? '') }}</textarea>
                            @error('text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="author" class="block text-sm font-medium text-gray-700">
                                Author
                            </label>
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   value="{{ old('author', $article->author ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('author')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ isset($article) ? 'Update' : 'Create' }} Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>