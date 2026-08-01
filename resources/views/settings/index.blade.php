@extends('layouts.app')

@section('title', 'Configurações do Sistema - ZamEdu')
@section('page-title', 'Configurações do Sistema')
@section('page-title-icon', 'fas fa-sliders-h')

@section('breadcrumbs')
    <li class="breadcrumb-item active text-slate-600 font-medium">
        <i class="fas fa-cog me-1"></i> Configurações
    </li>
@endsection

@php
    $activeTab = request('tab', 'escola');
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header Banner de Configurações SaaS -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-emerald-400 text-2xl border border-white/10 shadow-inner shrink-0">
                    <i class="fas fa-cogs"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-extrabold tracking-tight font-heading text-white">
                        Painel Geral de Configurações
                    </h2>
                    <p class="text-xs md:text-sm text-slate-300 max-w-2xl mt-0.5">
                        Gerencie a identidade da escola, ano letivo, estrutura académica, permissões, alertas de comunicação e preferências globais.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 text-xs font-medium">
                <i class="fas fa-shield-alt text-emerald-400"></i>
                <span>Multi-Escola / SaaS Ready</span>
            </div>
        </div>
    </div>


    <!-- Container Principal com Tabs e Form -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Navegação das 6 Categorias de Configurações -->
        <div class="border-b border-slate-100 bg-slate-50/50 p-2 md:p-3 overflow-x-auto">
            <ul class="nav nav-pills flex-nowrap gap-1 md:gap-2" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'escola' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="escola-tab" data-bs-toggle="pill" data-bs-target="#tab-escola" type="button" role="tab">
                        <i class="fas fa-school text-sm"></i>
                        <span>1. Escola & Marca</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'ano-letivo' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="ano-letivo-tab" data-bs-toggle="pill" data-bs-target="#tab-ano-letivo" type="button" role="tab">
                        <i class="fas fa-calendar-alt text-sm"></i>
                        <span>2. Ano Letivo</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'estrutura' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="estrutura-tab" data-bs-toggle="pill" data-bs-target="#tab-estrutura" type="button" role="tab">
                        <i class="fas fa-graduation-cap text-sm"></i>
                        <span>3. Estrutura Académica</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'utilizadores' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="utilizadores-tab" data-bs-toggle="pill" data-bs-target="#tab-utilizadores" type="button" role="tab">
                        <i class="fas fa-users-cog text-sm"></i>
                        <span>4. Utilizadores & Auditoria</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'comunicacao' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="comunicacao-tab" data-bs-toggle="pill" data-bs-target="#tab-comunicacao" type="button" role="tab">
                        <i class="fas fa-paper-plane text-sm"></i>
                        <span>5. Comunicação</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-xs md:text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2 {{ $activeTab === 'system' ? 'active bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}"
                        id="system-tab" data-bs-toggle="pill" data-bs-target="#tab-system" type="button" role="tab">
                        <i class="fas fa-server text-sm"></i>
                        <span>6. Sistema & Manutenção</span>
                    </button>
                </li>
            </ul>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="p-6 md:p-8 tab-content" id="settingsTabContent">

                <!-- TAB 1: ESCOLA -->
                <div class="tab-pane fade {{ $activeTab === 'escola' ? 'show active' : '' }}" id="tab-escola" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Dados da Instituição de Ensino</h3>
                            <p class="text-xs text-slate-500">Informações oficiais, logótipo, endereço e direção da escola.</p>
                        </div>
                        <span class="badge bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-semibold">Identidade Visual</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nome Completo da Escola</label>
                            <input type="text" name="settings[school_name]" class="form-control rounded-xl border-slate-200 p-3 text-sm focus:ring-2 focus:ring-emerald-500/20"
                                value="{{ setting('school_name', 'Escola Primária e Secundária dos Visionários') }}" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nome Curto / Sigla</label>
                            <input type="text" name="settings[school_short_name]" class="form-control rounded-xl border-slate-200 p-3 text-sm focus:ring-2 focus:ring-emerald-500/20"
                                value="{{ setting('school_short_name', 'ZamEdu') }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Logótipo da Escola</label>
                            <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 flex items-center justify-center p-2 shadow-sm shrink-0 overflow-hidden">
                                    @if(setting('school_logo'))
                                        <img src="{{ setting('school_logo') }}" alt="Logo" class="max-h-full max-w-full object-contain">
                                    @else
                                        <i class="fas fa-graduation-cap text-3xl text-emerald-600"></i>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="school_logo_file" class="form-control rounded-xl border-slate-200 text-xs" accept="image/*">
                                    <p class="text-[11px] text-slate-500 mt-1.5">Formatos suportados: PNG, JPG, SVG ou WebP (máx: 2MB). O logótipo será utilizado nos boletins e comprovantes.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Endereço Principal</label>
                            <input type="text" name="settings[school_address]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_address', 'Av. das Indústrias, Nº 145, Cidade de Maputo') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">E-mail Institucional</label>
                            <input type="email" name="settings[school_email]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_email', 'contacto@escola.co.mz') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Telefone Principal</label>
                            <input type="text" name="settings[school_phone]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_phone', '+258 84 123 4567') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Telefone Secundário / WhatsApp</label>
                            <input type="text" name="settings[school_phone_secondary]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_phone_secondary', '+258 82 987 6543') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Website Oficial</label>
                            <input type="url" name="settings[school_website]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_website', 'https://www.visionarios-school.co.mz') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Diretor Geral da Escola</label>
                            <input type="text" name="settings[school_director]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_director', 'Prof. Dr. António Silva') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Diretor Pedagógico</label>
                            <input type="text" name="settings[school_pedagogical_director]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_pedagogical_director', 'Dra. Maria Fernanda Santos') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIF / NUUIT (Número Fiscal)</label>
                            <input type="text" name="settings[school_nif]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_nif', '400123456') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Código MEC / Alvará Oficial</label>
                            <input type="text" name="settings[school_code]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('school_code', 'MEC-2026-MZ-889') }}">
                        </div>

                        <!-- SUBSEÇÃO: PERSONALIZAÇÃO VISUAL & THEME ENGINE (WHITE-LABEL) -->
                        <div class="md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-palette text-emerald-600"></i>
                                        <span>Personalização de Marca & Theme Engine</span>
                                    </h4>
                                    <p class="text-xs text-slate-500">Defina o esquema de cores e estilo visual da instituição aplicável globalmente.</p>
                                </div>
                                <span class="badge bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">White-Label Ready</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cor Principal (Primary)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="settings[primary_color]" class="form-control form-control-color w-10 h-10 rounded-lg cursor-pointer border-0"
                                            value="{{ setting('primary_color', '#4F46E5') }}" title="Escolher cor principal">
                                        <input type="text" class="form-control rounded-lg text-xs font-mono uppercase"
                                            value="{{ setting('primary_color', '#4F46E5') }}" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cor Secundária (Secondary)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="settings[secondary_color]" class="form-control form-control-color w-10 h-10 rounded-lg cursor-pointer border-0"
                                            value="{{ setting('secondary_color', '#06B6D4') }}" title="Escolher cor secundária">
                                        <input type="text" class="form-control rounded-lg text-xs font-mono uppercase"
                                            value="{{ setting('secondary_color', '#06B6D4') }}" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cor de Acento (Accent)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="settings[accent_color]" class="form-control form-control-color w-10 h-10 rounded-lg cursor-pointer border-0"
                                            value="{{ setting('accent_color', '#F59E0B') }}" title="Escolher cor de acento">
                                        <input type="text" class="form-control rounded-lg text-xs font-mono uppercase"
                                            value="{{ setting('accent_color', '#F59E0B') }}" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Arredondamento (Border Radius)</label>
                                    <select name="settings[border_radius]" class="form-select rounded-lg text-xs p-2.5">
                                        <option value="16px" {{ setting('border_radius', '16px') == '16px' ? 'selected' : '' }}>Padrão (16px)</option>
                                        <option value="12px" {{ setting('border_radius') == '12px' ? 'selected' : '' }}>Compacto (12px)</option>
                                        <option value="20px" {{ setting('border_radius') == '20px' ? 'selected' : '' }}>Arredondado (20px)</option>
                                        <option value="8px" {{ setting('border_radius') == '8px' ? 'selected' : '' }}>Retangular (8px)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: ANO LETIVO -->
                <div class="tab-pane fade {{ $activeTab === 'ano-letivo' ? 'show active' : '' }}" id="tab-ano-letivo" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Ano Letivo e Períodos Académicos</h3>
                            <p class="text-xs text-slate-500">Definição do ano de exercício, regime de trimestres/semestres e datas chave.</p>
                        </div>
                        <span class="badge bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-xs font-semibold">Calendário Escolar</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ano Letivo Ativo</label>
                            <input type="number" name="settings[current_academic_year]" class="form-control rounded-xl border-slate-200 p-3 text-sm font-bold text-emerald-700"
                                value="{{ setting('current_academic_year', date('Y')) }}" min="2020" max="2035">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Regime Académico</label>
                            <select name="settings[academic_regime]" class="form-select rounded-xl border-slate-200 p-3 text-sm">
                                <option value="trimestral" {{ setting('academic_regime', 'trimestral') == 'trimestral' ? 'selected' : '' }}>Trimestral (3 Trimestres)</option>
                                <option value="semestral" {{ setting('academic_regime') == 'semestral' ? 'selected' : '' }}>Semestral (2 Semestres)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Início do Ano Letivo</label>
                            <input type="date" name="settings[academic_year_start]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('academic_year_start', '2026-02-01') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Término do Ano Letivo</label>
                            <input type="date" name="settings[academic_year_end]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('academic_year_end', '2026-11-30') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Início das Matrículas</label>
                            <input type="date" name="settings[enrollment_start_date]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('enrollment_start_date', '2026-01-05') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Encerramento de Matrículas</label>
                            <input type="date" name="settings[enrollment_end_date]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('enrollment_end_date', '2026-02-28') }}">
                        </div>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 mb-6">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-emerald-600"></i> Cronograma dos Trimestres
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="block text-xs font-extrabold text-emerald-700 mb-2">1º Trimestre</span>
                                <div class="space-y-2">
                                    <input type="date" name="settings[term1_start]" class="form-control text-xs" value="{{ setting('term1_start', '2026-02-01') }}">
                                    <input type="date" name="settings[term1_end]" class="form-control text-xs" value="{{ setting('term1_end', '2026-05-15') }}">
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="block text-xs font-extrabold text-emerald-700 mb-2">2º Trimestre</span>
                                <div class="space-y-2">
                                    <input type="date" name="settings[term2_start]" class="form-control text-xs" value="{{ setting('term2_start', '2026-05-25') }}">
                                    <input type="date" name="settings[term2_end]" class="form-control text-xs" value="{{ setting('term2_end', '2026-08-28') }}">
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="block text-xs font-extrabold text-emerald-700 mb-2">3º Trimestre (Final)</span>
                                <div class="space-y-2">
                                    <input type="date" name="settings[term3_start]" class="form-control text-xs" value="{{ setting('term3_start', '2026-09-07') }}">
                                    <input type="date" name="settings[term3_end]" class="form-control text-xs" value="{{ setting('term3_end', '2026-11-20') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: ESTRUTURA ACADÉMICA -->
                <div class="tab-pane fade {{ $activeTab === 'estrutura' ? 'show active' : '' }}" id="tab-estrutura" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Estrutura Pedagógica e Avaliações</h3>
                            <p class="text-xs text-slate-500">Parâmetros de escala de notas, ponderações de exames e capacidade de turmas.</p>
                        </div>
                        <span class="badge bg-purple-50 text-purple-700 px-3 py-1.5 rounded-full text-xs font-semibold">Parâmetros Curriculares</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Escala de Avaliação</label>
                            <select name="settings[grading_scale]" class="form-select rounded-xl border-slate-200 p-3 text-sm">
                                <option value="0_20" {{ setting('grading_scale', '0_20') == '0_20' ? 'selected' : '' }}>0 a 20 Valores (Padrão Moçambique/Angola)</option>
                                <option value="0_10" {{ setting('grading_scale') == '0_10' ? 'selected' : '' }}>0 a 10 Valores</option>
                                <option value="percentage" {{ setting('grading_scale') == 'percentage' ? 'selected' : '' }}>Percentagem (0 - 100%)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nota Mínima para Aprovação</label>
                            <input type="number" name="settings[passing_grade]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('passing_grade', 10) }}" min="0" max="20" step="0.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Capacidade Máxima por Sala</label>
                            <input type="number" name="settings[default_room_capacity]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('default_room_capacity', 45) }}" min="5" max="100">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Peso da Avaliação Contínua (%)</label>
                            <input type="number" name="settings[acp_weight]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('acp_weight', 40) }}" min="0" max="100">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Peso da Prova Trimestral (%)</label>
                            <input type="number" name="settings[exam_weight]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('exam_weight', 60) }}" min="0" max="100">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Limite de Faltas Não Justificadas</label>
                            <input type="number" name="settings[max_unexcused_absences]" class="form-control rounded-xl border-slate-200 p-3 text-sm"
                                value="{{ setting('max_unexcused_absences', 15) }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- TAB 4: UTILIZADORES & AUDITORIA -->
                <div class="tab-pane fade {{ $activeTab === 'utilizadores' ? 'show active' : '' }}" id="tab-utilizadores" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Controlo de Perfis, Acessos e Auditoria</h3>
                            <p class="text-xs text-slate-500">Gestão de papéis de utilizador, regras de sessão e histórico de atividades.</p>
                        </div>
                        <span class="badge bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full text-xs font-semibold">Segurança & RBAC</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-bold uppercase text-slate-700 mb-3">Políticas de Segurança de Senha</h4>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="settings[force_password_change]" value="1" class="rounded text-emerald-600"
                                        {{ setting('force_password_change', 1) ? 'checked' : '' }}>
                                    <span class="text-xs text-slate-700 font-medium">Exigir alteração de senha no primeiro login do usuário</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="settings[enable_audit_log]" value="1" class="rounded text-emerald-600"
                                        {{ setting('enable_audit_log', 1) ? 'checked' : '' }}>
                                    <span class="text-xs text-slate-700 font-medium">Registar log de auditoria para todas as alterações de notas e pagamentos</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-bold uppercase text-slate-700 mb-3">Atalhos de Gestão de Contas</h4>
                            <p class="text-xs text-slate-500 mb-4">Aceda aos módulos dedicados para gestão granular de contas e logs.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm bg-slate-800 text-white rounded-xl text-xs font-bold px-4 py-2 hover:bg-slate-900">
                                    <i class="fas fa-users-cog me-1"></i> Gerir Utilizadores
                                </a>
                                <a href="{{ route('admin.audit.index') }}" class="btn btn-sm bg-emerald-700 text-white rounded-xl text-xs font-bold px-4 py-2 hover:bg-emerald-800">
                                    <i class="fas fa-shield-alt me-1"></i> Ver Logs de Auditoria
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 5: COMUNICAÇÃO -->
                <div class="tab-pane fade {{ $activeTab === 'comunicacao' ? 'show active' : '' }}" id="tab-comunicacao" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Canais de Comunicação & Notificações</h3>
                            <p class="text-xs text-slate-500">Configuração de gateway de e-mail SMTP, envio de SMS e automações.</p>
                        </div>
                        <span class="badge bg-teal-50 text-teal-700 px-3 py-1.5 rounded-full text-xs font-semibold">Integrador SMS/Email</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Servidor SMTP -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-bold uppercase text-slate-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-envelope text-emerald-600"></i> Servidor de E-mail (SMTP)
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Host SMTP</label>
                                    <input type="text" name="settings[smtp_host]" class="form-control text-xs rounded-xl"
                                        value="{{ setting('smtp_host', 'smtp.mailtrap.io') }}">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Porta</label>
                                        <input type="text" name="settings[smtp_port]" class="form-control text-xs rounded-xl"
                                            value="{{ setting('smtp_port', '587') }}">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Criptografia</label>
                                        <select name="settings[smtp_encryption]" class="form-select text-xs rounded-xl">
                                            <option value="tls" {{ setting('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ setting('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nome do Remetente</label>
                                    <input type="text" name="settings[mail_from_name]" class="form-control text-xs rounded-xl"
                                        value="{{ setting('mail_from_name', setting('school_name', 'ZamEdu')) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Gateway SMS -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-bold uppercase text-slate-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-sms text-emerald-600"></i> Provedor de SMS
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Provedor Ativo</label>
                                    <select name="settings[sms_provider]" class="form-select text-xs rounded-xl">
                                        <option value="twilio" {{ setting('sms_provider') == 'twilio' ? 'selected' : '' }}>Twilio SMS Gateway</option>
                                        <option value="smslocal" {{ setting('sms_provider', 'smslocal') == 'smslocal' ? 'selected' : '' }}>SMSlocal Moçambique / Angola</option>
                                        <option value="custom" {{ setting('sms_provider') == 'custom' ? 'selected' : '' }}>API HTTP Customizada</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Chave da API / Auth Token</label>
                                    <input type="password" name="settings[sms_api_key]" class="form-control text-xs rounded-xl"
                                        value="{{ setting('sms_api_key', '********************') }}">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Sender ID (Remetente)</label>
                                    <input type="text" name="settings[sms_sender_id]" class="form-control text-xs rounded-xl"
                                        value="{{ setting('sms_sender_id', 'ZamEdu') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 6: SISTEMA & MANUTENÇÃO -->
                <div class="tab-pane fade {{ $activeTab === 'system' ? 'show active' : '' }}" id="tab-system" role="tabpanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Preferências Globais e Backup</h3>
                            <p class="text-xs text-slate-500">Definições regionais, moeda oficial e rotina de cópias de segurança.</p>
                        </div>
                        <span class="badge bg-slate-100 text-slate-800 px-3 py-1.5 rounded-full text-xs font-semibold">Infraestrutura SaaS</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Moeda Oficial</label>
                            <select name="settings[currency_symbol]" class="form-select rounded-xl border-slate-200 p-3 text-sm font-bold">
                                <option value="MZN" {{ setting('currency_symbol', 'MZN') == 'MZN' ? 'selected' : '' }}>MZN - Metical (Moçambique)</option>
                                <option value="AOA" {{ setting('currency_symbol') == 'AOA' ? 'selected' : '' }}>AOA - Kwanza (Angola)</option>
                                <option value="EUR" {{ setting('currency_symbol') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                                <option value="USD" {{ setting('currency_symbol') == 'USD' ? 'selected' : '' }}>USD - Dólar ($)</option>
                                <option value="BRL" {{ setting('currency_symbol') == 'BRL' ? 'selected' : '' }}>BRL - Real (R$)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fuso Horário (Timezone)</label>
                            <select name="settings[system_timezone]" class="form-select rounded-xl border-slate-200 p-3 text-sm">
                                <option value="Africa/Maputo" {{ setting('system_timezone', 'Africa/Maputo') == 'Africa/Maputo' ? 'selected' : '' }}>Africa/Maputo (UTC+2)</option>
                                <option value="Africa/Luanda" {{ setting('system_timezone') == 'Africa/Luanda' ? 'selected' : '' }}>Africa/Luanda (UTC+1)</option>
                                <option value="Europe/Lisbon" {{ setting('system_timezone') == 'Europe/Lisbon' ? 'selected' : '' }}>Europe/Lisbon (UTC+0)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Idioma do Sistema</label>
                            <select name="settings[system_language]" class="form-select rounded-xl border-slate-200 p-3 text-sm">
                                <option value="pt" {{ setting('system_language', 'pt') == 'pt' ? 'selected' : '' }}>Português (PT)</option>
                                <option value="en" {{ setting('system_language') == 'en' ? 'selected' : '' }}>English (EN)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Seção de Backups & Log Viewer -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-bold uppercase text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-database text-emerald-600"></i> Backups do Sistema
                                </h4>
                                <a href="{{ route('admin.backup.create') }}" method="POST" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold px-3 py-1.5 inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-download"></i> Backup Agora
                                </a>
                            </div>

                            @if(!empty($backups))
                                <div class="divide-y divide-slate-200 max-h-48 overflow-y-auto">
                                    @foreach($backups as $b)
                                        <div class="py-2 flex items-center justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-slate-800 block">{{ $b['filename'] }}</span>
                                                <span class="text-[10px] text-slate-500">{{ $b['date'] }} • {{ $b['size'] }}</span>
                                            </div>
                                            <span class="badge bg-emerald-100 text-emerald-800">Concluído</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-500 text-center py-4">Nenhum backup manual registado recentemente.</p>
                            @endif
                        </div>

                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-bold uppercase text-slate-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-terminal text-slate-700"></i> Visualizador de Logs Recentes
                            </h4>
                            <div class="bg-slate-900 text-emerald-400 font-mono text-[11px] p-3 rounded-xl max-h-48 overflow-y-auto shadow-inner leading-relaxed">
                                @forelse($logs as $logLine)
                                    <div class="truncate">{{ $logLine }}</div>
                                @empty
                                    <div class="text-slate-500">Sem registos de erro no log.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botão Fixo de Gravação -->
            <div class="border-t border-slate-100 bg-slate-50/80 p-4 md:p-6 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">As alterações serão aplicadas imediatamente a todas as instâncias ativas.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5">
                    <i class="fas fa-save"></i>
                    <span>Guardar Configurações</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection