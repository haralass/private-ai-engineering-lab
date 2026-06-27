# Useful Assets Catalogue

**Public Code Research Library**
**A deduplicated catalogue of the strongest individual reusable components, algorithms, datasets, configurations, architectures, and interaction systems across all 68 repositories.**
**Date**: 2026-06-27

---

## Architecture Systems

### Three.js Application Architecture (Application/Resources pattern)
**Source**: brunosimon/folio-2019 — `src/javascript/Application.js`, `src/javascript/Resources.js`
**Type**: Architecture pattern
**What it is**: Singleton Application class managing a Three.js scene lifecycle. Resources class with event-driven asset loading that emits `ready` when all textures/models/fonts are loaded. Enables clean separation of scene setup from asset dependencies.
**Why valuable**: The most well-structured public vanilla Three.js codebase. All subsequent Three.js tutorials reference this pattern.
**Complexity**: Medium. Drop-in for any vanilla Three.js project.
**License**: MIT

### GlobalCanvas + ScrollScene (DOM/WebGL scroll sync)
**Source**: 14islands/r3f-scroll-rig — `src/scrollrig/components/GlobalCanvas.tsx`, `src/scrollrig/hooks/useTracker.ts`
**Type**: Architecture pattern + library
**What it is**: Single shared R3F canvas positioned fixed in background. `useTracker` measures a DOM element's DOMRect and returns equivalent world-space coordinates. `ScrollScene` renders a 3D object that follows a DOM element's scroll position.
**Why valuable**: Solves the hardest problem in hybrid DOM+WebGL layouts. Canonical reference for scroll-driven 3D scenes.
**Complexity**: High. Requires deep React Three Fiber understanding.
**License**: MIT

### Hydrogen Cart Pattern (Form-Action Cart)
**Source**: Shopify/hydrogen — `templates/skeleton/app/routes/cart.tsx`, `packages/hydrogen/src/cart/`
**Type**: Architecture pattern
**What it is**: Shopping cart managed entirely through HTML form actions and React Router v7 route actions. No client-side cart state management. Cart mutations POST to server and respond with redirect.
**Why valuable**: Progressive enhancement by default. Works without JavaScript. No hydration needed for cart operations.
**Complexity**: Medium. Requires React Router v7 understanding.
**License**: MIT

### RSC + Server Actions Commerce (Next.js App Router)
**Source**: vercel/commerce — `components/cart/actions.ts`, `components/cart/add-to-cart.tsx`
**Type**: Architecture pattern
**What it is**: Server Actions for all cart mutations (`addItem`, `updateItem`, `removeItem`). No client API calls. RSC product pages with streaming recommendations. Provider-agnostic product interface.
**Why valuable**: Canonical RSC commerce pattern. Shows how to implement cart without client state management.
**Complexity**: Medium. Requires Next.js App Router + RSC knowledge.
**License**: MIT

### TOTP 2FA + RBAC (Epic Stack)
**Source**: epicweb-dev/epic-stack — `app/utils/auth.server.ts`, `app/routes/settings+/profile.two-factor.tsx`, `app/utils/permissions.ts`
**Type**: Architecture pattern
**What it is**: Time-based One-Time Password authentication with QR code setup flow. Role-based access control with `requireUserWithPermission()` and `requireUserWithRole()` server utilities. All auth logic server-side with no client token exposure.
**Why valuable**: The most complete public 2FA + RBAC reference in the React ecosystem. Kent Dodds' production-tested approach.
**Complexity**: High. Requires understanding Prisma, TOTP spec, and cookie-based session management.
**License**: MIT

### FHIR R4 Patient Portal (foomedical)
**Source**: medplum/foomedical — `src/pages/health-record/`, `src/pages/messages/`
**Type**: Architecture pattern
**What it is**: SMART on FHIR OAuth2 authentication. FHIR R4 resource fetching for Patient, Observation, DiagnosticReport, and Appointment resources. Clinical data display patterns using Mantine UI components.
**Why valuable**: Only public FHIR patient portal. The reference implementation for healthcare frontend architecture.
**Complexity**: High. Requires FHIR R4 standard knowledge.
**License**: Apache-2.0

---

## Algorithms

### MATCHES Ranking Algorithm (match-sorter)
**Source**: kentcdodds/match-sorter — `src/index.ts`
**Type**: Algorithm
**What it is**: Deterministic client-side search ranking. Ranks results by: exact-case-match → case-insensitive-match → starts-with → word-starts-with → contains → acronym → fuzzy-match → no-match. Returns original objects sorted by match quality.
**Why valuable**: Zero dependencies. Predictable behavior unlike fuzzy search algorithms. Widely used (TanStack Table, cmdk, Radix Select).
**Complexity**: Low. Drop-in.
**License**: MIT

### Command Score (fuzzy command palette ranking)
**Source**: dip/cmdk — `cmdk/src/command-score.ts`
**Type**: Algorithm
**What it is**: Score function that rates how well an input string matches a candidate. Weights word-boundary matches higher than substring matches. Returns float [0,1]. Used by cmdk for command palette result ordering.
**Why valuable**: Specifically tuned for command menu UX (type-ahead pattern). Outperforms generic fuzzy matching for short strings.
**Complexity**: Low. Single-file import.
**License**: MIT

### GPU Tier Detection
**Source**: MaximeHeckel/blog.maximeheckel.com — `core/hooks/useGPUTier.ts`
**Type**: Algorithm
**What it is**: Reads WebGL renderer string and GPU vendor to classify GPU into: low (degraded), medium (standard), high (full effects). Enables progressive enhancement of GPU-intensive effects.
**Why valuable**: Essential for Three.js/R3F applications that need to gracefully degrade on low-end hardware.
**Complexity**: Low. React hook, single file.
**License**: MIT

### CSS Selector Tokenizer + Specificity Calculator (parsel)
**Source**: LeaVerou/parsel — `src/parsel.js`
**Type**: Algorithm + library
**What it is**: Full CSS Selectors Level 4 parser. Tokenizes `.foo > .bar + [attr]:is(.x, .y)` into AST. Calculates specificity. Traverses and transforms selector trees.
**Why valuable**: Only serious TypeScript CSS selector analysis library. Used in DevTools, CSS linters, and style analyzers.
**Complexity**: Low. Single file, zero deps.
**License**: MIT

### CSS Property Change Detection (StyleObserver)
**Source**: LeaVerou/style-observer — `src/StyleObserver.js`
**Type**: Algorithm
**What it is**: Observes changes to any CSS property (including custom properties) on an element. Uses CSS transitions on a zero-duration animation to detect computed style changes without polling.
**Why valuable**: Fills a browser primitive gap. No API exists for this natively. Zero dependencies.
**Complexity**: Low. Single-file import.
**License**: MIT

### SVG Path Rescaling for Motion (meanderer)
**Source**: jh3y/meanderer — `src/meanderer.js`
**Type**: Algorithm
**What it is**: Parses SVG path data and rescales coordinate values to match a new viewport size. Enables responsive CSS motion paths that adapt when the container resizes.
**Why valuable**: The only public library solving the responsive SVG path rescaling problem for CSS motion-path animations.
**Complexity**: Low.
**License**: MIT

---

## Reusable Components and Libraries

### Toast Notification System (sonner)
**Source**: emilkowalski/sonner — `src/index.tsx`, `src/styles.css`
**Type**: React component
**What it is**: Stacked toast notifications with physics-based animation. Swipe-to-dismiss with velocity detection. Elapsed-timer pause on hover. CSS custom property layout system. Single file (~600 lines).
**Why valuable**: Best toast library architecture in the React ecosystem. 12k+ stars. Used by shadcn/ui.
**Complexity**: Low. Drop-in `<Toaster/>`.
**License**: MIT

### Command Palette (cmdk)
**Source**: dip/cmdk — `cmdk/src/index.tsx`
**Type**: React component
**What it is**: Compound-component command menu. `<Command>`, `<Command.Input>`, `<Command.List>`, `<Command.Group>`, `<Command.Item>`. Keyboard navigation, fuzzy search, async loading support. Built on Radix Dialog.
**Why valuable**: The canonical command palette for React. Used by Vercel, Linear, Raycast Web.
**Complexity**: Low. Drop-in with Radix Dialog dependency.
**License**: MIT

### Flash-Free Dark Mode (next-themes)
**Source**: pacocoursey/next-themes — `src/index.tsx`, `src/script.ts`
**Type**: React context + script injection
**What it is**: Theme provider that injects an inline script before first paint to set the correct theme class. Prevents flash-of-incorrect-theme. System preference detection with manual override persistence.
**Why valuable**: Solves a notoriously difficult problem. The canonical solution for Next.js dark mode.
**Complexity**: Low. Wrap app with `<ThemeProvider>`.
**License**: MIT

### macOS Settings Window (sindresorhus/Settings)
**Source**: sindresorhus/Settings — `Sources/Settings/Settings.swift`
**Type**: Swift framework
**What it is**: Tab-based settings window for macOS. Toolbar and segmented control styles. Native appearance following macOS HIG. SwiftUI and AppKit compatible.
**Complexity**: Low. 3 files. SPM package.
**License**: MIT

### macOS Keyboard Shortcuts (sindresorhus/KeyboardShortcuts)
**Source**: sindresorhus/KeyboardShortcuts — `Sources/KeyboardShortcuts/`
**Type**: Swift framework
**What it is**: User-configurable global keyboard shortcut manager. SwiftUI-native recorder view. Conflict detection. Persistent storage via UserDefaults.
**Complexity**: Low. SPM package.
**License**: MIT

### Type-Safe UserDefaults (sindresorhus/Defaults)
**Source**: sindresorhus/Defaults — `Sources/Defaults/Defaults.swift`
**Type**: Swift framework
**What it is**: `@Default(.someKey)` property wrapper for type-safe UserDefaults access. SwiftUI bindings. iCloud NSUbiquitousKeyValueStore sync. Swift Macros `@ObservableDefault` integration.
**Complexity**: Low. SPM package.
**License**: MIT

### Dev-Mode Layout Inspector (raunofreiberg/inspx)
**Source**: raunofreiberg/inspx — `src/components/Inspect.tsx`
**Type**: React component (dev-only)
**What it is**: Hover to see pixel distances between elements and dimensions, like browser inspector but visible in-app during development.
**Complexity**: Low. Wrap subtree with `<Inspect>`.
**License**: MIT

### In-App Autocomplete (LeaVerou/awesomplete)
**Source**: LeaVerou/awesomplete — `awesomplete.js`
**Type**: Vanilla JS widget
**What it is**: Zero-dependency accessible autocomplete for `<input>` elements. Simple array or custom data function. Keyboard navigation, ARIA support. ~2KB minified.
**Complexity**: Low. Single-file include.
**License**: MIT

### CSS Loading Animations (jh3y/whirl)
**Source**: jh3y/whirl — `src/whirls/`
**Type**: CSS/SCSS library
**What it is**: 100+ isolated CSS loading animations. Each in its own SCSS file. Pure CSS, zero JavaScript.
**Complexity**: Low. Copy individual SCSS files.
**License**: MIT

### Pure-CSS Spinners (tobiasahlin/SpinKit)
**Source**: tobiasahlin/SpinKit — `css/spinkit.css`
**Type**: CSS library
**What it is**: 10 transform-only loading spinner animations. Zero JavaScript. Minimal CSS. 19k stars. Production-proven across thousands of projects.
**Complexity**: Low. Copy one CSS class.
**License**: MIT

### Form Element Auto-Size (LeaVerou/stretchy)
**Source**: LeaVerou/stretchy — `src/stretchy.js`
**Type**: Vanilla JS utility
**What it is**: Auto-resizes textarea, input, and select elements to fit their content.
**Complexity**: Low. Single file.
**License**: NOASSERTION — verify before production use.

---

## Datasets and Configurations

### VS Code File Nesting Config
**Source**: antfu/vscode-file-nesting-config — `package.json`
**Type**: Configuration dataset
**What it is**: Community-maintained rules for VS Code file nesting. Nests generated files under their source (e.g., `tsconfig.json` → `tsconfig.*.json`). 3.6k stars. Updated continuously.
**Why valuable**: The standard nesting configuration. Import directly into `.vscode/settings.json`.
**License**: MIT

### ESLint Flat Config Factory (antfu/eslint-config)
**Source**: antfu/eslint-config — `src/factory.ts`, `src/configs/`
**Type**: Tooling configuration
**What it is**: Composable ESLint Flat Config (ESLint 9+) factory. Returns array of config objects from pluggable rule sets for TypeScript, Vue, React, UnoCSS, Perfectionist, etc.
**Why valuable**: The definitive approach to multi-framework ESLint config. 16k+ stars.
**License**: MIT

### NHS WCAG 2.2 Component Documentation Format
**Source**: nhsuk/nhsuk-service-manual — `app/views/design-system/components/`
**Type**: Documentation format / dataset
**What it is**: Evidence-based component specification format covering: research rationale, do/don't examples, accessibility specifications by user type, coded examples with HTML.
**Why valuable**: The most rigorous publicly available format for accessible component documentation. Template for any design system documentation effort.
**License**: MIT (code); Crown Copyright (content)

---

## Interaction Systems

### Velocity-Based Swipe Dismiss (sonner / vaul)
**Source**: emilkowalski/sonner — `src/index.tsx`; emilkowalski/vaul — `src/index.tsx`
**Type**: Interaction algorithm
**What it is**: Pointer event tracking that calculates swipe velocity. Dismiss if velocity exceeds threshold OR if displacement exceeds 50% of component height. Spring animation on snap-back.
**Why valuable**: The correct way to implement mobile-style swipe-to-dismiss. Velocity-based threshold feels more natural than distance-only.
**License**: MIT

### Inline Script Dark Mode Prevention
**Source**: pacocoursey/next-themes — `src/script.ts`
**Type**: Browser technique
**What it is**: Synchronous inline script in `<head>` that reads localStorage theme preference and applies the correct CSS class before any rendering occurs. Prevents flash-of-incorrect-theme that occurs with useEffect-based approaches.
**Why valuable**: The only correct solution to FOCT in SSR/SSG frameworks. Copy the script, not the full library, if only this is needed.
**License**: MIT

### Shared R3F Canvas Pattern
**Source**: 14islands/r3f-scroll-rig — `src/scrollrig/components/GlobalCanvas.tsx`
**Type**: React/R3F architecture
**What it is**: Single `<Canvas>` component rendered once and positioned fixed. All scroll-driven scenes portal into this single canvas rather than creating multiple WebGL contexts.
**Why valuable**: Multiple WebGL contexts kill performance. This pattern is essential for any multi-section scroll page with 3D effects.
**License**: MIT

### Text Animation Recipes (moving-letters)
**Source**: tobiasahlin/moving-letters — all 16 HTML demos
**Type**: Animation patterns dataset
**What it is**: 16 standalone HTML demos of text animation using anime.js. Each shows a complete letter-by-letter animation recipe.
**Why valuable**: Reference implementations for common text entrance animations. MIT licensed.
**License**: MIT

---

## Starter Templates and Boilerplates

| Name | Source | Stack | Best For |
|------|--------|-------|---------|
| Epic Stack | epicweb-dev/epic-stack | React Router v7 + SQLite + 2FA | Full-stack React apps |
| Hydrogen Skeleton | Shopify/hydrogen | React Router v7 + Shopify | Shopify headless storefronts |
| Vercel Commerce | vercel/commerce | Next.js App Router + Shopify | Commerce sites |
| Vitesse | antfu-collective/vitesse | Vue 3 + Vite + UnoCSS | Vue apps |
| Foomedical | medplum/foomedical | React + FHIR R4 | Patient portals |
| Medusa Next.js Starter | medusajs/nextjs-starter-medusa | Next.js + Medusa v2 | Self-hosted commerce |
| Emergency Site | maxboeck/emergency-site | Eleventy (zero-JS) | Resilient emergency pages |
| Eleventastic | maxboeck/eleventastic | Eleventy + critical CSS | Static content sites |
