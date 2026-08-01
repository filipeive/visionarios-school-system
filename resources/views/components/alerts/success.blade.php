@props(['message' => null])

<div class="alert alert-success d-flex align-items-center shadow-xs rounded-2xl p-4 border-0 bg-emerald-50 text-emerald-900 dark:bg-emerald-950/60 dark:text-emerald-200">
    <i class="fas fa-check-circle fs-4 me-3 text-emerald-600 dark:text-emerald-400"></i>
    <div class="flex-grow">
        <strong class="d-block font-semibold">Sucesso!</strong>
        <span class="text-sm">{{ $message ?? $slot }}</span>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fechar"></button>
</div>
