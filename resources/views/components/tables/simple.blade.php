@props([
    'headers' => [],
])

<div class="table-responsive rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-xs">
    <table class="table table-school align-middle mb-0">
        @if(!empty($headers))
        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wider font-bold border-b border-slate-100 dark:border-slate-800">
            <tr>
                @foreach($headers as $header)
                    <th class="py-3.5 px-4">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm text-slate-700 dark:text-slate-300">
            {{ $slot }}
        </tbody>
    </table>
</div>
