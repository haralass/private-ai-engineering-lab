# maxboeck/emergency-site

## Identity
- **Owner**: maxboeck
- **Repository**: emergency-site
- **URL**: https://github.com/maxboeck/emergency-site
- **Live URL**: N/A (template — deploys vary per user)
- **Commit SHA**: 005b1d1
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (803 stars)
- **Transferred**: no

## Relationship classification
`production-starter`

Evidence: A template repository explicitly designed to be forked and deployed for real emergency communications situations. The README explains: "use this when your main website is down due to a disaster or emergency."

## Licensing
- **Code license**: MIT (LICENSE confirmed — Max Böck, 2020)
- **Attribution required**: yes
- **Asset restrictions**: none
- **Reuse verdict**: directly reusable

## Technical profile
- **Languages**: Nunjucks, JavaScript, SCSS
- **Frameworks**: Eleventy (11ty)
- **Key dependencies**: @11ty/eleventy, netlify-cms
- **Build system**: Eleventy
- **Package manager**: npm
- **Tests**: none
- **CI**: Netlify continuous deployment (netlify.toml)
- **Architecture**: Eleventy static site with a flat data model. Content is stored in JSON data files (`src/_data/`). Netlify CMS provides a no-login content editing UI for non-technical operators. PWA manifest allows installation. RSS feed for subscribers.
- **Rendering model**: Static HTML generation (zero JavaScript on client for content)

## Useful content (exact files)

### Directly reusable code

- `netlify.toml` — One-click Netlify deploy configuration. Shows how to configure Eleventy builds on Netlify with publish directory, build command, and environment variables.
- `src/admin/` — Netlify CMS configuration (`config.yml`). Shows how to add a no-login CMS to a static site for non-technical content editors.
- `eleventy/filters.js` — Eleventy filter functions: `dateFilter`, `w3DateFilter`, `limit`. Clean examples of Eleventy custom filter patterns.

### Adaptable patterns

- **Resilience-first architecture**: The site is designed to work when CDNs fail, JavaScript is blocked, and bandwidth is extremely limited. Design choices: no external CDN dependencies, inline critical CSS, preloaded fonts locally, service worker for offline. This pattern is directly applicable to any site that must function in degraded network conditions.
- **CMS + Static Site pattern**: Netlify CMS + Eleventy allows non-technical users to update a static site through a UI without Git knowledge. This pattern is underused and highly practical for clients who need content control.

### Architecture reference

The "emergency site" architecture constrains a site to maximum resilience: no JavaScript required for content, all assets self-hosted, offline-capable. This is a useful reference when designing sites that must work under adverse conditions (government sites, health advisories, crisis communications).

### Reference-only

- `src/` — Content templates. Copy the architecture, not the content.

## Evaluation

**Problem solved**: Rapid deployment of an emergency information website that continues functioning even when a CDN fails, JavaScript is unavailable, or bandwidth is severely constrained.

**Original value**: The only public emergency site template with explicit offline-first, no-CDN, no-JS-required design decisions. The resilience engineering behind the site (service worker, local fonts, no external dependencies) is rarely documented this explicitly.

**Future project types**: Crisis communications, government information sites, healthcare emergency updates, any product that needs a "fallback" site, offline-capable progressive web apps.

**Do not copy**: The content (UK-focused example content). The visual design and branding are minimal but clearly intended as placeholders.

**Risks**: Uses Netlify CMS (now StaticManifest/Decap CMS after Netlify's acquisition). The CMS config may need updating for the new package name. Small repo with no recent significant changes.

## Scores (1–5)

| Dimension | Score |
|-----------|-------|
| Technical quality | 4 |
| Originality | 5 |
| General usefulness | 4 |
| Architecture | 5 |
| Design and UX | 3 |
| Accessibility | 4 |
| Performance | 5 |
| Testing | 1 |
| Documentation | 5 |
| Maintenance health | 3 |
| Licensing clarity | 5 |
| Long-term lab value | 4 |

**Priority**: medium
**Action**: clone
