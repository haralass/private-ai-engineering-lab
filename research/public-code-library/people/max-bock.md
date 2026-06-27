# Max Böck

**GitHub**: maxboeck
**Website**: https://mxb.dev
**Analysis date**: 2026-06-27

## Profile overview
Max Böck is an Austrian frontend developer focused on performance, accessibility, progressive enhancement, and the indie web. He is best known for emergency-site (a template for emergency information websites), eleventastic (an Eleventy starter kit), and his personal site mxb which demonstrates CSS-first, build-light web development philosophy. He is active in the Eleventy community and advocates for resilient, fast-loading web experiences.

## Repository inventory
| Repository | Type | Stars | Status | Decision |
|-----------|------|-------|--------|----------|
| emergency-site | Original | 803 | Active | Clone |
| mxb | Original | 567 | Active | Reference |
| eleventastic | Original | 492 | Active | Clone |
| resume | Original | 353 | Active | Reference |
| webring | Original | 279 | Active | Reference |
| whimsical | Original | 242 | Active | Reference |
| webmention-analytics | Original | 83 | Maintained | Reference |
| static-prototype-kit | Original | 47 | Old | Reject |
| zerobuild | Original | 18 | Experiment | Reject |
| eleventy-plugin-share-highlight | Original | 9 | Niche plugin | Reject |
| webmentions-component | Original | 3 | Old | Reject |
| mxb-jekyll | Original | 38 | Old/superseded | Reject |
| jekyll-gulp | Original | 8 | Old | Reject |
| startkit | Original | 0 | Old WordPress theme | Reject |
| All forks (eleventy-webmentions, speedlify, etc.) | Fork | 0-94 | Various | Reject |

## Original vs forks vs transferred
- **Original**: 10 meaningful original repos
- **Forks**: ~30+ forks of other Eleventy and community repos (eleventy-webmentions, speedlify, 11ty-website, zachleat.com, etc.) — all rejected
- **Transferred**: None detected
- **Archived**: None — Max doesn't archive old repos, he just stops committing
- **No license**: mxb (his personal site) has no LICENSE file — content copyright only, code reference-only

## Strongest repositories

1. **emergency-site** — A battle-tested, zero-dependency-in-spirit template for emergency information websites. Designed to work when CDNs are down, JS fails, and bandwidth is limited. Netlify CMS integration, PWA manifest, RSS, sitemap, multilingual support. MIT licensed. 803 stars. Decision: **clone**

2. **eleventastic** — An opinionated Eleventy starter with Nunjucks templating, SCSS, Babel, image optimization (critical CSS extraction via `critical`), Eleventy Image, Eleventy Navigation, RSS, sitemap, and service worker. MIT licensed. 492 stars. Decision: **clone**

3. **mxb** — Personal site source. Eleventy-based, showcases advanced CSS, webmentions integration, and performant image handling. No LICENSE file — code is reference-only, not reusable. Decision: **reference**

4. **resume** — An Eleventy-based open-source resume template with print CSS, semantic HTML, and a JSON data model. MIT licensed. 353 stars. Decision: **reference**

5. **webring** — A boilerplate for hosting a webring community. Includes member management, navigation scripts, and Netlify deploy. MIT licensed. 279 stars. Decision: **reference**

6. **whimsical** — A curated directory of whimsical websites. Eleventy + Nunjucks, MIT licensed. 242 stars. Useful as a reference for Eleventy data-driven directory sites. Decision: **reference**

## Key findings
Max Böck's unique value in the public code library is his **emergency-site** repo — it is the definitive template for emergency information pages (government alerts, disaster response, etc.) that prioritizes offline-first, no-JS fallback, and minimal footprint. His **eleventastic** starter is one of the most comprehensive Eleventy starters available and includes techniques (critical CSS extraction, image optimization pipeline) that are harder to find elsewhere. His personal site demonstrates how to achieve excellent performance and accessibility scores without a heavy framework. He represents the Eleventy/progressive-enhancement school of web development at its most polished.

## Rejected repositories
- **static-prototype-kit** — Old Jekyll-era static prototyping tool, unused since 2018
- **zerobuild** — 2024 experiment in build-free web development, 18 stars, no clear reuse path
- **eleventy-plugin-share-highlight** — Very niche Eleventy plugin (9 stars)
- **webmentions-component** — Old Web Component from 2019
- **mxb-jekyll** — Superseded by the Eleventy version
- **jekyll-gulp** — Old toolchain boilerplate (2017)
- **startkit** — WordPress PHP starter theme (2017), irrelevant
- **All forks** — Contributions to community repos (zachleat.com, eleventy-netlify-boilerplate, etc.)
- **eleventy-webmentions** — Fork of someone else's repo
- **Front-end-Developer-Interview-Questions** — Fork, content not code
