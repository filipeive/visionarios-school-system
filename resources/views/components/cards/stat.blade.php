@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fas fa-chart-line',
    'type' => 'primary',
    'subtitle' => null,
])

@php
    $typeClasses = [
        'primary' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 border-indigo-100',
        'secondary' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-950/40 dark:text-cyan-400 border-cyan-100',
        'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-100',
        'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border-amber-100',
        'danger' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border-rose-100',
        'info' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400 border-sky-100',
    ];
    $iconBg = $typeClasses[$type] ?? $typeClasses['primary'];
@endphp

<div class="stat-card p-5 rounded-2xl bg-white dark:bg-[#1C2541] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between transition hover:-translate-y-0.5">
    <div class="space-y-1">
        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">{{ $title }}</span>
        <div class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $value }}</div>
        @if($subtitle)
            <div class="text-xs text-slate-400 font-medium">{{ $subtitle }}</div>
        @endif
    </div>
    <div class="w-12 h-12 rounded-2xl {{ $iconBg }} border flex items-center justify-center text-xl shrink-0 shadow-xs">
        <i class="{{ $icon }}"></i>
    </div>
</div>
