@extends('layouts.app')

@section('title', 'Pré-Inscrição Enviada')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="school-card p-5 shadow-lg border-0">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Pré-Inscrição Enviada com Sucesso!</h2>
                    <p class="lead text-muted mb-4">
                        Obrigado por escolher a nossa escola. O pedido de pré-inscrição para
                        <strong>{{ $application->student_data['first_name'] }}
                            {{ $application->student_data['last_name'] }}</strong>
                        foi recebido e está sendo processado.
                    </p>

                    <div class="card bg-light border-0 mb-4 text-start">
                        <div class="card-body">
                            <h5 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Próximos Passos:</h5>
                            <ol class="mb-0">
                                <li>A secretaria irá validar os dados e documentos enviados.</li>
                                <li>Você receberá um contacto para agendar a entrega dos documentos físicos.</li>
                                <li>O pagamento da taxa de
                                    <strong>{{ number_format($application->total_amount, 2, ',', '.') }} MT</strong> deve
                                    ser feito via depósito bancário.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="alert alert-info text-start">
                        <h6 class="fw-bold mb-2">Dados para Depósito:</h6>
                        <p class="mb-1"><strong>Banco:</strong> Millennium BIM</p>
                        <p class="mb-1"><strong>Conta:</strong> 123456789</p>
                        <p class="mb-1"><strong>NIB:</strong> 0001 0000 1234 5678 9012 3</p>
                        <p class="mb-0"><strong>Referência:</strong> PRE-{{ $application->id }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('welcome') }}" class="btn btn-primary-school px-5">Voltar à Página Inicial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection