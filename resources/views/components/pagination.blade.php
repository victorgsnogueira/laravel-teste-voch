@props(['paginator'])

<nav aria-label="pagination">
    <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
        <li>
            <button wire:click="setPage({{ $paginator->currentPage() - 1 }})"
                {{ $paginator->onFirstPage() ? 'disabled' : '' }}
                class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-50"
                aria-label="previous page">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                    class="size-6">
                    <path fill-rule="evenodd"
                        d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                        clip-rule="evenodd" />
                </svg>
                Anterior
            </button>
        </li>

        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li>
                <button wire:click="setPage({{ $page }})"
                    class="flex size-6 items-center justify-center rounded-radius p-1 {{ $page === $paginator->currentPage() ? 'bg-primary font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' : 'text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark' }}"
                    {{ $page === $paginator->currentPage() ? 'aria-current="page"' : '' }}
                    aria-label="page {{ $page }}">
                    {{ $page }}
                </button>
            </li>
        @endforeach

        <li>
            <button wire:click="setPage({{ $paginator->currentPage() + 1 }})"
                {{ $paginator->hasMorePages() ? '' : 'disabled' }}
                class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-50"
                aria-label="next page">
                Próximo
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                    class="size-6">
                    <path fill-rule="evenodd"
                        d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </li>
    </ul>
</nav>
