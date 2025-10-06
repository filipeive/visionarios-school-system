{{-- resources/views/search/results.blade.php --}}
@extends('layouts.school')

@section('title', 'Resultados da Pesquisa')
@section('page-title', 'Resultados da Pesquisa')

@php
    $titleIcon = 'fas fa-search';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pesquisa</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Resultados para: <strong>"{{ $query }}"</strong>
        </div>
    </div>
</div>

@if($results['students']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-graduate"></i>
                Alunos Encontrados ({{ $results['students']->count() }})
            </div>
            <div class="school-card-body">
                <div class="list-group">
                    @foreach($results['students'] as $student)
                        <a href="{{ route('students.show', $student) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <img src="{{ $student->photo_url }}" class="rounded-circle me-3" 
                                     style="width: 40px; height: 40px;">
                                <div>
                                    <h6 class="mb-0">{{ $student->full_name }}</h6>
                                    <small class="text-muted">{{ $student->student_number }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($results['teachers']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-chalkboard-teacher"></i>
                Professores Encontrados ({{ $results['teachers']->count() }})
            </div>
            <div class="school-card-body">
                <div class="list-group">
                    @foreach($results['teachers'] as $teacher)
                        <a href="{{ route('teachers.show', $teacher) }}" 
                           class="list-group-item list-group-item-action">
                            <h6 class="mb-0">{{ $teacher->full_name }}</h6>
                            <small class="text-muted">{{ $teacher->email }}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($results['students']->count() == 0 && $results['teachers']->count() == 0 && $results['classes']->count() == 0)
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body text-center py-5">
                <i class="fas fa-search fs-1 text-muted mb-3"></i>
                <h4>Nenhum resultado encontrado</h4>
                <p class="text-muted">Tente pesquisar com outros termos</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection