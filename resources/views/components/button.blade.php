<button
    {{ $attributes->merge(['type' => 'submit','class' =>'bg-amber-500 text-white active:bg-amber-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150']) }}>
    {{ $slot }}
</button>
