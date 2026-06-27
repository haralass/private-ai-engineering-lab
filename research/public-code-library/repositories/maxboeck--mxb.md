# maxboeck/mxb

## Identity and Provenance

| Field | Value |
|-------|-------|
| Owner | maxboeck |
| Repository | mxb |
| URL | https://github.com/maxboeck/mxb |
| Live URL | https://mxb.dev |
| Commit SHA | 5a922eaea07d64fc3f45ee96d5f3d9a04b38ae25 |
| Date Analyzed | 2026-06-27 |
| Original / Fork | original |
| Upstream | none |
| Status | active (last meaningful push 2025-01-26) |
| Last Meaningful Push | 2025-01-26 |

## Legal Status

| Field | Value |
|-------|-------|
| License | NONE (no license file in root) |
| Attribution Required | unknown — no license |
| Code Reuse | reference-only until license clarified |
| Reference-Only | yes — no explicit license |

No license file found. Treat as reference-only. Code patterns can be studied but not directly copied without permission.

## Technical Profile

| Field | Value |
|-------|-------|
| Primary Language | SCSS + JavaScript + Nunjucks |
| Framework | Eleventy (11ty) |
| Major Dependencies | Eleventy, WebMentions plugin, custom webmention-cache plugin, Netlify functions |
| Build System | Eleventy + Netlify |
| Test System | none |
| Repository Structure | `src/` (content, templates, assets), `plugins/webmention-cache/` (custom Eleventy plugin), `functions/` (Netlify function for share) |
| Architecture | Static site generated with 11ty + Nunjucks templates. Custom Eleventy plugin caches and fetches WebMentions. Netlify function for share API. |

## Actual Valuable Content

### Directly Reusable: WebMention Cache Plugin

**`plugins/webmention-cache/index.js`**
- Custom Eleventy plugin that fetches WebMentions from `webmention.io`, caches them locally in a manifest file, and injects them as Eleventy global data.
- Handles incremental updates: only fetches WebMentions newer than the last cached entry.
- **Why valuable**: WebMention support for static sites is poorly documented. This is a clean, production-tested implementation for any 11ty site wanting social/reply integration.

### Architecture Reference: Eleventy Personal Site

**`eleventy.config.js`**
- Shows how to configure Eleventy with: passthrough copies, custom collections, Nunjucks as template engine, structured data files as global data.
- Clean separation: `src/assets/` → passthrough, `src/blog/` → collection, `src/projects/` → collection.

**`src/` Nunjucks templates**
- Base layout → section layouts → page/post templates hierarchy is a textbook 11ty structure.

### Content Architecture

**`functions/share.ts`** — Netlify function implementing a Web Share Target endpoint. Allows mobile share-to-blog workflows. Reference for share target API integration with static sites.

## Value Classification

| Item | Classification |
|------|---------------|
| WebMention cache plugin | adaptable implementation pattern |
| Eleventy config structure | architecture reference |
| Share target Netlify function | adaptable implementation pattern |
| Blog content | reject |
| Personal design/branding | reject |

## General Usefulness

**Problem it solves**: Production personal blog with 11ty, WebMentions, and social sharing — demonstrating IndieWeb patterns.

**Why the implementation is notable**: The WebMention cache plugin is the most interesting piece — it solves the problem of integrating social web interactions (likes, replies, reposts from Mastodon/Twitter bridged via Bridgy) into a static site build without hitting the WebMention API on every build.

**Future project uses**:
- WebMention integration for any static site
- Eleventy site structure reference
- Share target API pattern

**Should not retain**: Blog content, personal photos, design decisions are personal.

## Quality Assessment

| Dimension | Rating | Notes |
|-----------|--------|-------|
| Code Quality | 3/5 | Clean but not exceptional |
| Architecture | 3/5 | Good 11ty patterns |
| Dependency Risk | 2/5 | 11ty ecosystem; stable but niche |
| Documentation | 2/5 | Minimal |
| Licensing Clarity | 1/5 | No license file — reference only |
| Long-term Usefulness | 3/5 | WebMention plugin is niche but useful |

## Scoring

| Dimension | Score (1–5) |
|-----------|-------------|
| Technical Quality | 3 |
| Originality | 3 |
| General Reusability | 2 |
| Educational Value | 3 |
| Design / UX Quality | 3 |
| Architecture Quality | 3 |
| Documentation Quality | 2 |
| Maintenance Health | 3 |
| Licensing Clarity | 1 |
| Long-term Lab Value | 2 |

**Final Priority**: low  
**Recommended Action**: reference-only (no license — do not copy code)
