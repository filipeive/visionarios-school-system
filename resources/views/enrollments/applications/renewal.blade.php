@extends('layouts.app')

@section('title', 'Renovação de Matrícula')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="school-card shadow-lg border-0">
                    <div class="school-card-header bg-primary-school text-white p-4">
                        <h2 class="mb-0"><i class="fas fa-sync me-2"></i> Renovação de Matrícula - {{ $academicYear }}</h2>
                        <p class="mb-0 mt-2 opacity-75">Confirme os dados para a renovação da matrícula de
                            {{ $student->full_name }}.</p>
                    </div>
                    <div class="school-card-body p-5">
                        <form action="{{ route('parent.student-renewal.store', $student->id) }}" method="POST">
                            @csrf

                            <h4 class="border-bottom pb-2 mb-4 text-primary-school">Dados do Aluno</h4>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Nome Completo</label>
                                    <p class="fw-bold">{{ $student->full_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Número de Matrícula</label>
                                    <p class="fw-bold">{{ $student->student_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Classe Atual</label>
                                    <p class="fw-bold">{{ $student->current_class->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <h4 class="border-bottom pb-2 mb-4 text-primary-school">Atualização de Contactos</h4>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Telefone / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="parent[phone]" class="form-control"
                                        value="{{ $student->parent->phone ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" name="parent[email]" class="form-control"
                                        value="{{ $student->parent->user->email ?? '' }}">
                                </div>
                            </div>

                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Taxas de Renovação</h5>
                                    <ul class="list-group list-group-flush">
                                        @php $total = 0; @endphp
                                        @foreach($fees as $fee)
                                            @if(!$fee->grade_level || $fee->grade_level === $student->grade_level)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                                    {{ $fee->name }}
                                                    <span>{{ number_format($fee->amount, 2, ',', '.') }} MT</span>
                                                </li>
                                                @php $total += $fee->amount; @endphp
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div class="mt-3 text-end">
                                        <h4 class="fw-bold">Total a Pagar: {{ number_format($total, 2, ',', '.') }} MT</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="terms_accepted" required
                                        id="terms">
                                    <label class="form-check-label" for="terms">
                                        Confirmo que os dados estão corretos e aceito os termos do novo ano letivo.
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                A renovação só será efetivada após a confirmação do pagamento na secretaria.
                            </div>

                            <div class="mt-5 d-flex justify-content-between">
                                <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary-school px-5">Submeter Renovação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection