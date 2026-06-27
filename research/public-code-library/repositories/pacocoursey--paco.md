# pacocoursey/paco

## Identity
- **Owner**: pacocoursey
- **Repository**: paco
- **URL**: https://github.com/pacocoursey/paco
- **Live URL**: https://paco.me (may have moved to different codebase)
- **Commit SHA**: e011620d0fca28fb25d9877c1d739feaddd06b53
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: archived (April 2022)
- **Transferred**: no

## Relationship classification
exact-live-site-source

Evidence: This was the source for paco.me. Now archived, meaning the live site likely runs a different unpublished codebase.

## Licensing
- **Code license**: none specified (no LICENSE file found)
- **Attribution required**: assumed yes (no explicit grant)
- **Asset restrictions**: unknown — blog content (posts/) is personal
- **Reuse verdict**: study only — no explicit license; code patterns can be referenced but not directly copied

## Technical profile
- **Languages**: JavaScript (Next.js pages), CSS
- **Frameworks**: Next.js (Pages Router era)
- **Key dependencies**: next, react, tailwindcss (postcss.config.json present), gray-matter, remark, prism.js, swr
- **Build system**: Next.js build
- **Package manager**: yarn
- **Tests**: no
- **CI**: no
- **Architecture**: Pages Router static site with MDX blog posts. Markdown rendered via remark. RSS generated at build time. View counts tracked via external data source (lib/db.js likely uses Vercel KV or Upstash).
- **State management**: SWR for remote data (view counts)
- **Rendering model**: SSG with ISR for post pages

## Useful content (exact files)

### Adaptable patterns
- `lib/render-markdown.js` — Remark pipeline for converting markdown to HTML with syntax highlighting
- `lib/rss.js` — RSS feed generation from MDX posts at build time
- `lib/db.js` — View count persistence pattern (SWR + edge data store)
- `lib/use-data.js` — SWR-based data fetching hook for page-level data
- `styles/global.css`, `styles/markdown.css`, `styles/syntax.css` — Minimal CSS layering for blog content

### Architecture reference
- Minimal blog architecture with no CMS: posts are plain markdown files in `posts/`, processed by remark at build time, no database required for content.
- View counting with SWR: pattern for incrementing and displaying read counts without a full backend.

### Reference-only
- `posts/` — Personal blog posts (content only, not code)
- `components/icon.js`, `components/head.js` — Minimal utility components
- `styles/inter.css`, `styles/nprogress.css` — Font loading and progress indicator CSS

## Evaluation
**Problem solved**: Personal blog/portfolio with markdown posts, syntax highlighting, view counts, and RSS.
**Original value**: Low unique value on its own — the patterns (markdown blog, SSG, SWR) are widespread. The value is in seeing how a skilled developer kept the whole thing extremely lean: no UI library, minimal dependencies, everything hand-rolled.
**Future project types**: Personal blog, documentation site, minimal content site.
**Do not copy**: Content (posts) is obviously personal. The lack of a license means code should be referenced, not copy-pasted wholesale.
**Risks**: Archived. No license file means copying code is legally ambiguous. Treat as study material only.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 4 |
| Originality | 2 |
| General usefulness | 3 |
| Architecture | 3 |
| Design and UX | 4 |
| Accessibility | 3 |
| Performance | 4 |
| Testing | 1 |
| Documentation | 2 |
| Maintenance health | 1 |
| Licensing clarity | 1 |
| Long-term lab value | 2 |

**Priority**: low
**Action**: reference-only
