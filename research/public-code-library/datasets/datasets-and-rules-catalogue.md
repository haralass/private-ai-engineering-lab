# Datasets and Rules Catalogue
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Structured data, curated lists, rule sets, and configuration datasets that have standalone value independent of the applications they were built for.

---

## Configuration Datasets

### VS Code File Nesting Patterns
**Source**: antfu/vscode-file-nesting-config — `extension/src/config.ts`  
**License**: MIT  
**Format**: VS Code `explorer.fileNesting.patterns` JSON structure  
**Contents**: Comprehensive mapping of "parent file" → "related files to nest under it" for:
- `package.json` → lock files, `.npmrc`, `.nvmrc`, etc.
- `tsconfig.json` → all tsconfig variants
- `next.config.*` → Next.js related files
- `vite.config.*` → Vite related files
- `svelte.config.*` → Svelte related files
- `astro.config.*` → Astro related files
- `pyproject.toml` → Python project files
- `Dockerfile` → Docker-related files
- `README.md` → CONTRIBUTING.md, CHANGELOG.md, etc.
- And 50+ more
**Update frequency**: Multiple times per month as new tooling emerges  
**Usage**: Copy the patterns into any project's `.vscode/settings.json`

---

## Curated Link Collections

### Whimsical Websites Collection
**Source**: maxboeck/whimsical — site data  
**License**: MIT (code), content free (community-submitted)  
**Format**: Website entries with URL, category, screenshot  
**What it is**: A community-curated list of websites with playful, creative, or unusual design and interaction  
**Live resource**: https://whimsical.club  
**Value**: Discovery resource for creative web design inspiration. Best accessed via the live site.  
**Note**: Individual website URLs may become stale. The live site is more current than the repo data.

---

## Interface Rules / Behavioral Specifications

### Rauno Freiberg's Interface Principles
**Source**: raunofreiberg/interfaces  
**License**: NONE (reference only)  
**Format**: Prose rules with interactive demonstrations  
**What it is**: A documented set of specific interface behaviors that well-built UIs should implement  
**Categories covered**:
- Button behavior (trigger on mouse-up, not down)
- Form validation timing
- Navigation state indicators
- Destructive action confirmations
- Number alignment in tables
- List sorting thresholds
- Loading state communication
- Error message quality
- Copy confirmation feedback
**Value**: Acts as a behavioral specification document that can be adapted as a product's own interface quality checklist  
**Live resource**: https://rauno.me/interfaces (check for current URL)

---

## Text and Language Datasets (in Repos)

### Alice in Wonderland Performance Test Files
**Source**: pacocoursey/writer — `samples/`  
**License**: NONE (for the writer repo; Alice in Wonderland text is public domain)  
**Format**: Plain text in various sizes (short, 2x, 4x)  
**What it is**: A set of text files at different sizes for testing text editor performance — used to benchmark canvas rendering  
**Value**: Ready-to-use text performance test dataset. Public domain text in calibrated sizes.

### Shakespeare Performance Test File
**Source**: pacocoursey/writer — `samples/shakespeare.txt`  
**License**: Public domain  
**What it is**: Shakespeare play text for larger-scale text editor performance testing

---

## Algorithm / Ranking Ruleset

### Match-Sorter Ranking Rules
**Source**: kentcdodds/match-sorter — `src/index.ts`  
**License**: MIT  
**Format**: TypeScript enum + algorithm  
**Rules** (ordered from highest to lowest priority):
1. `CASE_SENSITIVE_EQUAL` — exact case match
2. `EQUAL` — case-insensitive exact match
3. `STARTS_WITH` — value starts with the search term
4. `WORD_STARTS_WITH` — any word in the value starts with the search term
5. `CONTAINS` — value contains the search term anywhere
6. `ACRONYM` — the acronym of the value's words matches the search term
7. `MATCHES` — the fuzzy matching fallback (characters appear in order)
**Value**: The 7-tier ranking hierarchy is a validated, tested definition of "good search results" for array filtering. Adaptable to any language or search context.

---

## Style / Easing Datasets

### Bendable Easing Definitions
**Source**: tobiasahlin/bendable — `bendable.css`  
**License**: MIT  
**Format**: CSS custom properties  
**Contents**:
- Sine easings (in, out, in-out): `--ease-sine-1` through `--ease-sine-3`
- Quad easings: `--ease-quad-*`
- Cubic easings: `--ease-cubic-*`
- Quart easings: `--ease-quart-*`
- Quint easings: `--ease-quint-*`
- Expo easings: `--ease-expo-*`
- Circ easings: `--ease-circ-*`
- Back easings (overshoot): `--ease-back-*`
- Elastic easings (spring bounce): `--ease-elastic-*`
- Bounce easings: `--ease-bounce-*`
**Value**: Complete, validated easing function library usable in any CSS project.

---

## Design Token Datasets

### Shadow Scale (CSS Custom Properties)
**Source**: MaximeHeckel/design-system — `src/lib/tokens/shadows.ts`  
**License**: NONE (reference; re-implement)  
**Format**: TypeScript constants → CSS custom properties  
**Contents**: 4-step shadow scale (0 = none, 1 = subtle, 2 = medium, 3 = elevated) using stacked box shadows with HSL opacity  
**Value**: A validated perceptual shadow scale. The CSS custom property approach makes dark mode shadow color switching trivial.

### Typography Token Scale
**Source**: MaximeHeckel/design-system — `src/lib/tokens/typography.ts`  
**License**: NONE (reference; re-implement)  
**Format**: TypeScript constants → CSS custom properties  
**Contents**: Font stack definitions, font size scale (14px → 44px), font weight scale  
**Value**: Baseline typography token set. CSS custom property approach means the tokens work in any CSS-in-JS or plain CSS context.

### SpinKit Loading Animation Variants
**Source**: TobiasAhlin/SpinKit — individual SCSS files  
**License**: MIT  
**Format**: SCSS → CSS  
**Contents**: 10 named spinner animations with configurable color and size  
**Value**: A reference set of CSS loading animations. Pick the right aesthetic for the context.
