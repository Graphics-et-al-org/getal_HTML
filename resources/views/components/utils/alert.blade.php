@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close')])
<div id="message-alert-{{ $type }}"
@switch($type)
@case('danger')
    {{ $attributes->merge(['class' => 'p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400']) }}
@break

@case('success')
{{ $attributes->merge(['class' => 'flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400']) }}
@break

@case('warning')
{{ $attributes->merge(['class' => '"p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300']) }}
@break

@case('info')
{{ $attributes->merge(['class' => 'p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400']) }}
@break

@case('status')
{{ $attributes->merge(['class' => 'p-4 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300']) }}
@break

@default
{{ $attributes->merge(['class' => 'p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400']) }}
@endswitch
    role="alert">
     {{ $slot }}
    @if ($dismissable)
        <button type="button"
            class="ms-auto -mx-1.5 -my-1.5  rounded-lg focus:ring-2  p-1.5 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800  dark:hover:bg-gray-700"
            data-dismiss-target="#message-alert-{{ $type }}" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    @endif


</div>
