# Guia de PWA & Acessibilidade — ZamEdu

Este documento especifica a conformidade PWA (Progressive Web App) e os critérios de acessibilidade WCAG 2.1 Nível AA implementados no **ZamEdu**.

---

## 📱 1. Suporte a Progressive Web App (PWA)

O ZamEdu pode ser instalado diretamente nos dispositivos móveis (Android / iOS) e computadores desktop como uma aplicação nativa standalone.

### Componentes PWA Incluídos:
- **`public/manifest.json`**: Define o nome, ícones, esquema de cores, orientação e modo de exibição `standalone`.
- **`public/sw.js`**: Service Worker com estratégia de cache para recarga rápida e funcionamento parcial offline.
- **Meta Tags de Integração Mobile**: Declaradas em [app.blade.php](file:///home/fdev-ms/Filipe/visionarios-school-system/resources/views/layouts/app.blade.php) para suporte a iOS Safari e Android Chrome.

---

## ♿ 2. Diretrizes de Acessibilidade (WCAG 2.1 AA Compliance)

### 2.1 Rácio de Contraste de Cores
- Todos os textos sobre fundo claro possuem rácio de contraste superior a **4.5:1** (cumprindo WCAG 2.1 AA).
- Todos os botões de ação utilizam cores sólidas ou gradientes com texto branco de alto contraste (`#FFFFFF`).

### 2.2 Navegação por Teclado
- Foco visível (`:focus-visible`) preservado em formulários, botões e hiperligações.
- Suporte a navegação por tecla `TAB` e atalhos de fecho em modais via `Esc`.

### 2.3 Semântica & Atributos ARIA
- Utilização de elementos HTML5 semânticos (`<header>`, `<nav>`, `<main>`, `<aside>`, `<footer>`, `<section>`).
- Atributos `aria-expanded` na sidebar retrátil.
- Atributos `title` e `aria-label` em todos os botões de ícone (Ex: `title="Ver Detalhes"`, `title="Editar"`, `title="Excluir"`).

---

## 🌗 3. Adaptação a Leitura & Dark Mode

- Suporte ao modo de alto contraste e modo escuro (`[data-bs-theme="dark"]`).
- Suporte a redimensionamento de fonte do navegador até 200% sem quebra de layout.
