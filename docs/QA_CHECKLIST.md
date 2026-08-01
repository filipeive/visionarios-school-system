# Checklist de Garantia de Qualidade (QA) & Testes — ZamEdu

Este documento serve como guia oficial de testes, auditoria de UI e procedimentos de QA para o **ZamEdu**.

---

## 🧪 1. Suíte de Testes Automatizados (PHPUnit)

Para executar toda a suíte de testes de regressão:

```bash
./vendor/bin/phpunit
```

### Resultados Obtidos:
- **34 testes executados e 100% aprovados** (81 asserções) sem falhas.

---

## 🎨 2. Matriz de Auditoria de Componentes UI & Theme Engine

| Componente | Requisito de Qualidade | Estado |
| :--- | :--- | :--- |
| **Theme Engine** | Variáveis CSS dinâmicas via `ThemeHelper::getCssVariables()` com suporte a Color Mix. | ✅ Concluído |
| **Botões de Ação** | Botões Ver (`.btn-primary-school`), Editar (`.btn-secondary-school`) e Excluir (`.btn-danger`) com gradientes e alto contraste. | ✅ Concluído |
| **Componente `<x-cards.stat>`** | Cartões estatísticos unificados com suporte a temas e ícones semânticos. | ✅ Concluído |
| **Componentes de Formulário** | Inputs (`<x-forms.input>`), Selects (`<x-forms.select>`) e Textareas (`<x-forms.textarea>`) padronizados. | ✅ Concluído |
| **Alertas (`<x-alerts.*>`)** | Sucesso e Erro estilizados com ícones e fecho via atributo ARIA. | ✅ Concluído |
| **Tabelas (`<x-tables.simple>`)** | Suporte a dark mode, avatares e bordas suaves. | ✅ Concluído |
| **Personalização Institucional** | Color pickers de Cor Principal, Secundária, Acento e Radius em `/admin/settings`. | ✅ Concluído |
| **Modo Escuro (Dark Mode)** | Paridade de contraste WCAG AA entre `data-bs-theme="light"` e `data-bs-theme="dark"`. | ✅ Concluído |

---

## ⚡ 3. Performance & Otimização

- [x] Cache de configurações e rotas testados com `php artisan optimize:clear`.
- [x] Renderização de pautas e gráficos rápida via Chart.js UMD.
- [x] PWA ativado com `public/manifest.json` e `public/sw.js`.
