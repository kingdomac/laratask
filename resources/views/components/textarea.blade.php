@props(['rows' => 3])
<textarea
    {{ $attributes->merge([
        'class' => 'orm-control
                                    block
                                    w-full
                                    py-1.5
                                    text-xs
                                    font-normal
                                    text-gray-700
                                    bg-white bg-clip-padding
                                    border border-solid border-gray-300
                                    rounded
                                    transition
                                    ease-in-out
                                    m-0
                                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
    ]) }}
    rows={{ $rows }}>{{ $slot }}</textarea>
