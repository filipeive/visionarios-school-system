# ZamEdu Design System & Theme Engine Architecture

Este documento especifica o **Design System consolidado** e a arquitetura do **Theme Engine (White-Label)** do **ZamEdu — Sistema de Gestão Escolar (SIGE)**.

---

## 🎨 1. Theme Engine Architecture (`ThemeHelper.php`)

O sistema conta com um **Theme Engine dinâmico e desacoplado**, gerenciado pela classe `App\Helpers\ThemeHelper`. As variáveis CSS são geradas em tempo de execução e injetadas no `<style>` global de [layouts/app.blade.php](file:///home/fdev-ms/Filipe/visionarios-school-system/resources/views/layouts/app.blade.php).

### Injeção Dinâmica no Layout:
```html
<style>
    {!! \App\Helpers\ThemeHelper::getCssVariables() !!}
</style>
```

### Tokens CSS Universais (Light & Dark Mode):

```css
:root {
    /* Branding Tokens Institucionais */
    --primary: #4F46E5;
    --primary-dark: color-mix(in srgb, var(--primary) 82%, #000);
    --primary-light: color-mix(in srgb, var(--primary) 18%, #fff);
    --primary-soft: color-mix(in srgb, var(--primary) 10%, #fff);

    --secondary: #06B6D4;
    --secondary-dark: color-mix(in srgb, var(--secondary) 82%, #000);
    --secondary-light: color-mix(in srgb, var(--secondary) 18%, #fff);

    --accent: #F59E0B;
    --accent-light: color-mix(in srgb, var(--accent) 20%, #fff);

    /* Cores Semânticas de Estado (Harmonizadas) */
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --info: #06B6D4;

    /* Superfícies & Layout (Modo Claro) */
    --content-bg: #F4F6FA;
    --card-bg: #FFFFFF;
    --surface-bg: #F8FAFC;
    --sidebar-bg: #FFFFFF;
    --border-color: #E2E8F0;
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #94A3B8;
}

[data-bs-theme="dark"] {
    /* Superfícies & Layout (Modo Escuro) */
    --content-bg: #0B132B;
    --card-bg: #1C2541;
    --surface-bg: #151E3D;
    --sidebar-bg: #1C2541;
    --border-color: #2D3748;
    --text-primary: #F8FAFC;
    --text-secondary: #CBD5E1;
    --text-muted: #64748B;
}
```

---

## 🧩 2. Catálogo de Componentes Reutilizáveis

A plataforma padronizou seus componentes principais na pasta `resources/views/components/`:

### 2.1 Cartões Estatísticos (`<x-cards.stat>`)
```html
<x-cards.stat 
    title="Total de Alunos" 
    value="189" 
    icon="fas fa-user-graduate" 
    type="primary" 
    subtitle="Ano Lectivo 2026" />
```

### 2.2 Alertas & Feeds (`<x-alerts.success>` / `<x-alerts.error>`)
```html
<x-alerts.success message="Configurações atualizadas com sucesso!" />
<x-alerts.error message="Erro ao processar o pagamento." />
```

### 2.3 Tabelas Semânticas (`<x-tables.simple>`)
```html
<x-tables.simple :headers="['ALUNO', 'NÚMERO', 'TURMA', 'STATUS', 'AÇÕES']">
    <tr>
        <td>...</td>
    </tr>
</x-tables.simple>
```

### 2.4 Componentes de Formulários (`<x-forms.input>`, `<x-forms.select>`, `<x-forms.textarea>`)
```html
<x-forms.input name="settings[school_name]" label="Nome da Escola" :value="$schoolName" required />
<x-forms.select name="settings[border_radius]" label="Arredondamento">
    <option value="16px">Padrão (16px)</option>
</x-forms.select>
```

### 2.5 Grelha de Filtros Harmonizada (`.row.g-3.align-items-end`)

Todas as barras de filtragem dos módulos (Alunos, Professores, Pagamentos, Turmas) utilizam a estrutura unificada com alinhamento vertical pela linha de base dos botões:

```html
<form action="..." method="GET" class="row g-3 align-items-end">
    <div class="col-md-5">
        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar</label>
        <input type="text" name="search" class="form-control rounded-xl border-slate-200" placeholder="...">
    </div>
    <div class="col-md-3">
        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
        <select name="status" class="form-select rounded-xl border-slate-200">...</select>
    </div>
    <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary-school flex-grow rounded-xl">
            <i class="fas fa-filter me-1"></i> Filtrar
        </button>
    </div>
</form>
```

---

## 🎨 3. Módulo de Personalização Institucional (Painel Admin)

Aceda a `/admin/settings` (Tab 1: Escola & Marca) para definir visualmente:
- **Cor Principal (Primary)**: Escolha via Color Picker.
- **Cor Secundária (Secondary)**: Escolha via Color Picker.
- **Cor de Acento (Accent)**: Escolha via Color Picker.
- **Arredondamento (Border Radius)**: Seleção entre 8px, 12px, 16px e 20px.
