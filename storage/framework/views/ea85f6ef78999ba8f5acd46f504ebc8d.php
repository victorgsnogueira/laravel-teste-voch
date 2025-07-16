<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['paginator']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<nav aria-label="pagination">
    <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
        <li>
            <button wire:click="setPage(<?php echo e($paginator->currentPage() - 1); ?>)"
                <?php echo e($paginator->onFirstPage() ? 'disabled' : ''); ?>

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

        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paginator->getUrlRange(1, $paginator->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <button wire:click="setPage(<?php echo e($page); ?>)"
                    class="flex size-6 items-center justify-center rounded-radius p-1 <?php echo e($page === $paginator->currentPage() ? 'bg-primary font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' : 'text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark'); ?>"
                    <?php echo e($page === $paginator->currentPage() ? 'aria-current="page"' : ''); ?>

                    aria-label="page <?php echo e($page); ?>">
                    <?php echo e($page); ?>

                </button>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

        <li>
            <button wire:click="setPage(<?php echo e($paginator->currentPage() + 1); ?>)"
                <?php echo e($paginator->hasMorePages() ? '' : 'disabled'); ?>

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
<?php /**PATH /var/www/html/resources/views/components/pagination.blade.php ENDPATH**/ ?>