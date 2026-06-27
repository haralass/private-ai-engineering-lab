# Kent C. Dodds

**GitHub**: kentcdodds
**Website**: https://kentcdodds.com
**Analysis date**: 2026-06-27

## Profile overview
Kent C. Dodds is a prominent React educator, author of Testing Library, and creator of EpicWeb.dev — a professional training platform for full-stack web development. His most impactful original code contributions are testing utilities (match-sorter, use-deep-compare-effect, stop-runaway-react-effects, babel-plugin-macros), the Epic Stack full-stack Remix starter, and mdx-bundler. He is deeply embedded in the React, Remix, and testing-library ecosystems, and is a member of the `epicweb-dev`, `testing-library`, `remix-run`, and `downshift-js` organizations.

## Repository inventory (selected)
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| match-sorter | Original | 4099 | Active | Clone |
| mdx-bundler | Original | 1900 | Active | Clone |
| kentcdodds.com | Original | 2486 | Active | Reference |
| epic-stack (epicweb-dev) | Original | 5536 | Active | Clone |
| kody | Original | 104 | Active | Reference |
| cross-env | Original | 6527 | Archived | Reference |
| babel-plugin-macros | Original | 2635 | Active | Reference |
| babel-plugin-preval | Original | 1364 | Active | Reference |
| babel-plugin-codegen | Original | 347 | Active | Reference |
| kcd-scripts | Original | 888 | Active | Reference |
| netlify-shortener | Original | 807 | Active | Reference |
| stop-runaway-react-effects | Original | 794 | Active | Reference |
| use-deep-compare-effect | Original | 1920 | Active | Reference |
| bookshelf | Original | 2682 | Active | Reference (course repo) |
| testing-react-apps | Original | 1100 | Active | Reference (course repo) |
| advanced-react-patterns | Original | 371 | Active | Reference (course repo) |
| mdx-bundler | Original | 1900 | Active | Clone |

## Workshop/course repos (bulk)
The following are all workshop/course repos with NOASSERTION license — they are educational exercises, not reusable architecture. All rejected for cloning:
- react-hooks, react-performance, react-suspense, react-fundamentals, advanced-react-hooks
- js-testing-fundamentals, js-mocking-fundamentals, testing-node-apps, testing-workshop (archived)
- bookshelf, advanced-react-patterns, react-testing-library-course, beginners-guide-to-react
- advanced-remix, remix-workshop, remix-todomvc, fakebooks-remix
- es6-workshop, asts-workshop

## Original vs forks vs transferred
- **Original**: ~60+ original repos
- **Forks**: ~10 forks (react, reactjs.org, create-react-app, etc.) — all rejected
- **Transferred**: `epic-stack` lives under `epicweb-dev` org (Kent's org), not his personal account
- **Archived**: cross-env, ng-stats, react-toggled, testing-workshop, super-simple-rsc, es6-todomvc, advanced-react-patterns-v2

## Strongest repositories

1. **match-sorter** — A deterministic, predictable best-match sorting algorithm for arrays. Uniquely solves the problem of sorting autocomplete results by how well items match a user's query, with configurable ranking thresholds. TypeScript, MIT, 4099 stars, minimal deps (only remove-accents). Decision: **clone**

2. **mdx-bundler** — Bundles MDX files with imports at runtime using esbuild. Supports remote imports, frontmatter, and arbitrary remark/rehype plugins. Used by many documentation sites. MIT licensed, 1900 stars. Decision: **clone**

3. **epic-stack** (epicweb-dev/epic-stack) — A production-ready full-stack React Router v7 starter with Prisma, authentication (bcrypt + TOTP + OAuth), Radix UI, Conform for form validation, LiteFS, Playwright E2E tests, and a comprehensive testing architecture. One of the most complete and well-thought-out open-source full-stack starters available. MIT licensed. Decision: **clone**

4. **kentcdodds.com** — The source of his personal website and blog. Built with Remix, MDX, Redis caching, and sophisticated content tooling. NOASSERTION on the course content portions but the infrastructure code is useful as a reference for MDX-based Remix sites. Decision: **reference**

5. **kody** — Experimental personal assistant platform on Cloudflare Workers with MCP protocol. Active in 2026. Decision: **reference**

## Key findings
Kent's uniquely valuable public contributions are: (1) the match-sorter algorithm, which solves a real problem no other library handles well; (2) mdx-bundler, which enables runtime MDX compilation — a capability not available in other tools at the same simplicity level; (3) the Epic Stack, which represents his distillation of full-stack Remix best practices. His testing library repos are widely cited but are now primarily the `testing-library` org (not his personal repo). His babel plugin trilogy (macros, preval, codegen) represents innovative compile-time JavaScript patterns.

## Rejected repositories
- **All workshop repos** (react-hooks, react-fundamentals, etc.) — NOASSERTION license, course exercise content
- **cross-env** — Archived, still useful but unmaintained
- **All forks** (react, create-react-app, reactjs.org) — Not original
- **es6-workshop, asts-workshop** — Educational workshops with no reuse value
- **netlify-shortener** — Simple Netlify redirect utility
- **starwars-names** — Tutorial demo
- **jest-glamor-react, rtl-css-js** — Old and unmaintained
- **generator-kcd-oss, kcd-common-tools** — Old build tooling
- **ng-stats** — Archived Angular 1 utility
- **genie** — Old keyboard shortcut library (2013-era)
