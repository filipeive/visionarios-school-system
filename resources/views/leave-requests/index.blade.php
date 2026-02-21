@extends('layouts.app')

@section('title', 'Gestão de Licenças')
@section('page-title', 'Gestão de Licenças')
@section('title-icon', 'fas fa-calendar-times')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Licenças</li>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-slate-500 text-xs uppercase tracking-wide">Total</div>
            <div class="mt-2 flex items-end justify-between">
                <div class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</div>
                <i class="fas fa-list text-slate-400"></i>
            </div>
        </div>
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
            <div class="text-amber-700 text-xs uppercase tracking-wide">Pendentes</div>
            <div class="mt-2 flex items-end justify-between">
                <div class="text-3xl font-bold text-amber-900">{{ $stats['pending'] }}</div>
                <i class="fas fa-clock text-amber-500"></i>
            </div>
        </div>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div class="text-emerald-700 text-xs uppercase tracking-wide">Aprovadas</div>
            <div class="mt-2 flex items-end justify-between">
                <div class="text-3xl font-bold text-emerald-900">{{ $stats['approved'] }}</div>
                <i class="fas fa-check-circle text-emerald-500"></i>
            </div>
        </div>
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
            <div class="text-rose-700 text-xs uppercase tracking-wide">Rejeitadas</div>
            <div class="mt-2 flex items-end justify-between">
                <div class="text-3xl font-bold text-rose-900">{{ $stats['rejected'] }}</div>
                <i class="fas fa-times-circle text-rose-500"></i>
            </div>
        </div>
    </div>

    <div x-data="{ openFilters: true }" class="rounded-xl border border-slate-200 bg-white shadow-sm mb-6">
        <div class="border-b border-slate-200 p-4 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <button type="button" @click="openFilters = !openFilters"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fas fa-filter mr-2"></i> Filtros
                </button>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('staff-leave-requests.export.csv', request()->query()) }}"
                    class="inline-flex items-center rounded-md border border-emerald-300 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50">
                    <i class="fas fa-file-csv mr-2"></i>CSV
                </a>
                <a href="{{ route('staff-leave-requests.export.pdf', request()->query()) }}"
                    class="inline-flex items-center rounded-md border border-rose-300 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </a>
            </div>
        </div>
        <div x-show="openFilters" x-transition class="p-4">
            <form method="GET" action="{{ route('staff-leave-requests.index') }}"
                class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-3">
                <div>
                    <input type="text" name="teacher"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Professor"
                        value="{{ request('teacher') }}">
                </div>
                <div>
                    <select name="status"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                        <option value="approved" @selected(request('status') === 'approved')>Aprovada</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejeitada</option>
                    </select>
                </div>
                <div>
                    <select name="leave_type"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os tipos</option>
                        <option value="sick" @selected(request('leave_type') === 'sick')>Licença Médica</option>
                        <option value="vacation" @selected(request('leave_type') === 'vacation')>Férias</option>
                        <option value="personal" @selected(request('leave_type') === 'personal')>Assunto Pessoal</option>
                        <option value="maternity" @selected(request('leave_type') === 'maternity')>Maternidade</option>
                        <option value="other" @selected(request('leave_type') === 'other')>Outro</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="date_from"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ request('date_from') }}" title="Data inicial">
                </div>
                <div>
                    <input type="date" name="date_to"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ request('date_to') }}" title="Data final">
                </div>
                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex w-full justify-center items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                    <a href="{{ route('staff-leave-requests.index') }}"
                        class="inline-flex items-center rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3">
            <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-clipboard-list mr-2"></i>Pedidos de Licença</h3>
        </div>
        <div class="p-4">
            @if ($leaveRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Professor</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Tipo</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Período</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Dias</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Status</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Solicitado em</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($leaveRequests as $leaveRequest)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-3 py-3">
                                        <strong>
                                            {{ $leaveRequest->staff?->first_name }} {{ $leaveRequest->staff?->last_name }}
                                        </strong>
                                        <small class="block text-slate-500">{{ $leaveRequest->staff?->email }}</small>
                                    </td>
                                    <td class="px-3 py-3 text-slate-700">{{ $leaveRequest->leave_type_name }}</td>
                                    <td class="px-3 py-3 text-slate-700">
                                        {{ $leaveRequest->start_date?->format('d/m/Y') }}
                                        <i class="fas fa-arrow-right mx-1 text-slate-400 text-xs"></i>
                                        {{ $leaveRequest->end_date?->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-3 text-slate-700">{{ $leaveRequest->days_requested }}</td>
                                    <td class="px-3 py-3">
                                        @if ($leaveRequest->status === 'approved')
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aprovada</span>
                                        @elseif($leaveRequest->status === 'rejected')
                                            <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Rejeitada</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-slate-700">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('staff-leave-requests.show', $leaveRequest) }}"
                                                class="inline-flex items-center justify-center rounded-md border border-blue-300 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50">
                                                <i class="fas fa-eye mr-1"></i>Detalhes
                                            </a>
                                            @if ($leaveRequest->reason)
                                                <small class="text-slate-500" title="{{ $leaveRequest->reason }}">
                                                    {{ \Illuminate\Support\Str::limit($leaveRequest->reason, 45) }}
                                                </small>
                                            @endif
                                            @if ($leaveRequest->status === 'pending')
                                                @can('approve_leave_requests')
                                                    <form method="POST"
                                                        action="{{ route('staff-leave-requests.approve', $leaveRequest) }}">
                                                        @csrf
                                                        <button
                                                            class="inline-flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                            <i class="fas fa-check mr-1"></i>Aprovar
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('staff-leave-requests.reject', $leaveRequest) }}">
                                                        @csrf
                                                        <input type="text" name="rejection_reason"
                                                            class="mb-1 w-full rounded-md border-slate-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            placeholder="Motivo da rejeição" required>
                                                        <button
                                                            class="inline-flex w-full justify-center rounded-md bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                                            <i class="fas fa-times mr-1"></i>Rejeitar
                                                        </button>
                                                    </form>
                                                @endcan
                                            @elseif($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
                                                <small class="text-rose-600">{{ $leaveRequest->rejection_reason }}</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $leaveRequests->links() }}</div>
            @else
                <div class="text-center py-10 text-slate-500">
                    <i class="fas fa-calendar-times text-3xl mb-3"></i>
                    <p class="mb-0">Nenhum pedido de licença encontrado com os filtros atuais.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
