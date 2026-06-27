# kentcdodds/mdx-bundler

## Identity
- **Owner**: kentcdodds
- **Repository**: mdx-bundler
- **URL**: https://github.com/kentcdodds/mdx-bundler
- **Live URL**: N/A (npm package: `mdx-bundler`)
- **Commit SHA**: d3992c7
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (1,900+ stars)
- **Transferred**: no

## Relationship classification
`reusable-engine-or-library`

Evidence: A published npm library (`mdx-bundler`) used in production by kentcdodds.com and many MDX-heavy documentation sites. Not a starter — it is the actual bundling library.

## Licensing
- **Code license**: MIT (confirmed — Kent C. Dodds, 2020)
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: TypeScript
- **Frameworks**: none (Node.js library)
- **Key dependencies**: esbuild, @mdx-js/mdx, vfile, gray-matter
- **Build system**: tsup
- **Package manager**: npm
- **Tests**: Jest (comprehensive)
- **CI**: GitHub Actions
- **Architecture**: Uses esbuild's `transform` API to bundle MDX files with their imports at runtime. MDX files can import local components, remote modules, and arbitrary Node.js modules. The bundling step resolves all imports into a single self-contained JavaScript string that can be `eval()`'d or executed in any JS environment.
- **Rendering model**: Library — used server-side to bundle MDX, then result is passed to client for hydration

## Useful content (exact files)

### Directly reusable code

- `src/index.tsx` — The complete bundler implementation. The key function is `bundleMDX({ source, files, mdxOptions, esbuildOptions, globals, cwd })`. Shows how to use esbuild's `transform` API for custom bundling.
- `src/get-source-file.ts` — Handles both file path and source string inputs. Pattern for flexible API that accepts content either as a file path or inline string.

### Adaptable patterns

- **esbuild for runtime MDX bundling**: Using esbuild's `transform` API at runtime (in a Node.js server) to bundle arbitrary code is a pattern not widely documented elsewhere. This is the core technique enabling MDX files to import components.
- **Remote import support**: mdx-bundler supports loading imports from remote URLs (CDN-hosted components) — shows how to extend esbuild with a custom fetch-based resolver plugin.

### Architecture reference

The `bundleMDX` function pattern — taking MDX source, a map of virtual files, and returning a compiled `code` string + `frontmatter` object — is the right separation for MDX bundling in SSR contexts. The consumer pattern: `bundleMDX` server-side → `getMDXComponent(code)` client-side.

### Reference-only

- `tests/` — Comprehensive test suite covering all edge cases. Good reference for testing MDX processing pipelines.

## Evaluation

**Problem solved**: Running MDX with import support at runtime on a server, without a build step. Enables MDX files that import React components to be bundled and served dynamically (e.g., from a CMS or database).

**Original value**: The key innovation is using esbuild for runtime MDX bundling rather than requiring a build-time compilation step. This enables CMS-backed MDX content where posts can import custom components. Used by kentcdodds.com and MaximeHeckel's blog.

**Future project types**: Documentation sites with CMS-managed content, any MDX-powered blog or documentation where content is stored outside the build system, educational platforms with live code examples.

**Do not copy**: The library itself is the right thing to use via npm. Clone is for source inspection only.

**Risks**: Next.js App Router has moved toward `@next/mdx` and `next-mdx-remote` as the standard solutions. mdx-bundler is more flexible but also more complex. The esbuild dependency may conflict with a project's existing build tooling.

## Scores (1–5)

| Dimension | Score |
|-----------|-------|
| Technical quality | 5 |
| Originality | 5 |
| General usefulness | 4 |
| Architecture | 5 |
| Design and UX | N/A |
| Accessibility | N/A |
| Performance | 5 |
| Testing | 5 |
| Documentation | 4 |
| Maintenance health | 4 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: medium
**Action**: clone
