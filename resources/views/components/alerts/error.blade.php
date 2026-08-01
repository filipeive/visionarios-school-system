@props(['message' => null])

<div class="alert alert-danger d-flex align-items-center shadow-xs rounded-2xl p-4 border-0 bg-rose-50 text-rose-900 dark:bg-rose-950/60 dark:text-rose-200">
    <i class="fas fa-exclamation-triangle fs-4 me-3 text-rose-600 dark:text-rose-400"></i>
    <div class="flex-grow">
        <strong class="d-block font-semibold">Atenção!</strong>
        <span class="text-sm">{{ $message ?? $slot }}</span>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fechar"></button>
</div>
