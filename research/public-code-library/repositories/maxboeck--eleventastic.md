# maxboeck/eleventastic

## Identity
- **Owner**: maxboeck
- **Repository**: eleventastic
- **URL**: https://github.com/maxboeck/eleventastic
- **Live URL**: https://eleventastic.netlify.app
- **Commit SHA**: 37e469d
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (492 stars)
- **Transferred**: no

## Relationship classification
`production-starter`

Evidence: An opinionated Eleventy starter template explicitly designed to be forked. The README positions it as "an opinionated Eleventy starter" with a list of included features.

## Licensing
- **Code license**: MIT (LICENSE confirmed — Max Böck, 2020)
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Nunjucks, JavaScript, SCSS
- **Frameworks**: Eleventy (11ty)
- **Key dependencies**: @11ty/eleventy, critical (CSS extraction), eleventy-plugin-lazyimages, @11ty/eleventy-plugin-rss, @11ty/eleventy-navigation, @11ty/eleventy-img
- **Build system**: Eleventy + npm scripts for SCSS and JS
- **Package manager**: npm
- **Tests**: none
- **CI**: Netlify (netlify.toml)
- **Architecture**: Eleventy static site with Nunjucks templating. SCSS compiled to CSS. JavaScript minified with a simple esbuild-like pipeline. Critical CSS extraction via `critical` package. Images processed via Eleventy Image for responsive srcsets. PWA-ready with service worker.
- **Rendering model**: Static HTML

## Useful content (exact files)

### Directly reusable code

- `utils/` — Eleventy utility scripts: `filters.js` (date formatting, limit, slugify), `shortcodes.js` (image shortcode, icon shortcode), `transforms.js` (critical CSS inline transform, HTML minification).
- `src/_data/` — Site metadata pattern: `site.json` holds global site configuration (name, URL, description, author, social links). Standard Eleventy data cascade usage.
- `src/_includes/layouts/` — Base layout pattern: `base.njk` with `head.njk` and `foot.njk` partials. Shows the minimal Eleventy layout structure.

### Adaptable patterns

- **Critical CSS pattern** (`utils/transforms.js`): Uses the `critical` npm package to extract and inline above-the-fold CSS into `<style>` tags during the build. This is a significant performance optimization rarely done correctly in starter templates.
- **Eleventy Image with srcset** (`utils/shortcodes.js`): The image shortcode generates multiple sizes of each image with correct `srcset` and `sizes` attributes. Shows the correct Eleventy Image pattern for responsive images.
- **Service Worker for offline** (`src/js/sw.js`): Basic service worker with caching strategy. Shows how to add offline support to an Eleventy site.

### Architecture reference

The `utils/transforms.js` pattern — applying HTML transformations (critical CSS, minification) as Eleventy transforms — is the clean way to post-process Eleventy output. More maintainable than build scripts.

### Reference-only

- `src/` — The template content (posts, pages). Replace entirely when forking.

## Evaluation

**Problem solved**: Setting up a performant Eleventy static site with critical CSS, responsive images, SCSS, PWA, RSS, and accessible navigation — all configured correctly from the start.

**Original value**: The critical CSS extraction in a static site generator context is non-trivial to configure correctly and rarely included in starters. The Eleventy Image srcset generation is also well implemented. These two features together make this one of the more performance-conscious Eleventy starters.

**Future project types**: Static marketing sites, personal blogs, content-heavy sites, any project needing a fast static site with minimal JavaScript and strong performance defaults.

**Do not copy**: The template's visual design and content. The site structure is minimal and intentionally generic.

**Risks**: Eleventy has moved to v3.x with ESM-first configuration. This starter uses the older CommonJS configuration pattern and may need updating for Eleventy 3.x. The `critical` package can be slow for large sites.

## Scores (1–5)

| Dimension | Score |
|-----------|-------|
| Technical quality | 4 |
| Originality | 4 |
| General usefulness | 4 |
| Architecture | 4 |
| Design and UX | 3 |
| Accessibility | 4 |
| Performance | 5 |
| Testing | 1 |
| Documentation | 4 |
| Maintenance health | 3 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: medium
**Action**: clone
