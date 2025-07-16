<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-lg shadow font-semibold transition-all focus:ring-2 focus:ring-blue-500 bg-blue-600 text-white !text-white hover:bg-blue-700']) }}>
    {{ $slot }}
</button>
