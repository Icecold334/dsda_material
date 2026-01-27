<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-4 py-2 bg-primary-800 border
    border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700
    :bg-white focus:bg-primary-700 :bg-white active:bg-primary-900 :bg-primary-300 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 :ring-offset-primary-800 transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>