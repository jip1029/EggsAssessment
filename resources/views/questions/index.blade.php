@php
    $questionArray = [
        __("What's on your mind?"),
        __("How are you doing?"),
        __("What's going on?"),
        __("What's up with you?"),
        __("Tell me something?"),
        __("How's your day?"),
        __("What's your name?"),
    ];
    $i = 0;

    $randomQuestion = $questionArray[$i];

    $i++;

    if($i >= count($questionArray)) {
        $i = 0;
    }
@endphp

<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="flex flex-col mb-4">
                <label for="question" class="text-lg font-medium mb-2">{{ __('Question') }}</label>
                <input id="question" type="text" name="question" value="{{ $randomQuestion }}" readonly
                    class="py-2 px-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
            </div>
            <div class="flex flex-col mb-4">
                <label for="answer" class="text-lg font-medium mb-2">{{ __('Answer') }}</label>
                <textarea id="answer" name="answer" placeholder="{{ __('Type your answer here') }}"
                    class="py-2 px-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('answer') }}</textarea>
                <x-input-error :messages="$errors->get('answer')" class="mt-2" />
            </div>
            <x-primary-button class="mt-4">{{ __('Submit') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($questions as $question)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $question->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">{{ $question->created_at->format('j M Y, g:i a') }}</small>
                                @unless ($question->created_at->eq($question->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }} </small>
                                @endunless
                            </div>
                            @if ($question->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('questions.edit', $question)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('questions.destroy', $question) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('questions.destroy', $question)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>

                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ $question->question }}</p>
                        <p class="mt-4 text-lg text-gray-900">{{ $question->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>