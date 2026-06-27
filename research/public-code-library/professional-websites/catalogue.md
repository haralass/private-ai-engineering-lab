# Professional Websites and Applications — Curated Catalogue

**Public Code Research Library**
**Focus**: Strongest professional websites and production applications organized by industry and role.
**Date**: 2026-06-27

---

## Tier 1 — Elite (score 45–50/50)

These represent the highest craft density found in the library. Set the benchmark.

### blog.maximeheckel.com
**URL**: https://blog.maximeheckel.com
**Source**: MaximeHeckel/blog.maximeheckel.com (SHA: c3ef52f, MIT)
**Type**: Developer blog / creative portfolio
**Stack**: Next.js App Router, MDX, Framer Motion, React Three Fiber, Orama

**What makes it exceptional**:
- MDX widgets that embed live GPU particle systems and shader previews within prose articles
- Custom GPU tier detection (`useGPUTier`) that degrades 3D effects on low-end hardware
- Orama full-text search with stemming, fuzzy matching, and instant results
- Custom command palette (⌘K) with keyboard navigation and animated transitions
- View Transition API navigation between blog posts
- Glass-morphism UI with CSS custom property token system
- Production evidence: ships to 100k+ monthly readers, actively updated through 2026

**Reuse value**: GPU tier detection, MDX shader integration, search implementation, command menu pattern

---

### nhsuk-service-manual
**URL**: https://service-manual.nhs.uk
**Source**: nhsuk/nhsuk-service-manual (SHA: 229b262, MIT)
**Type**: Healthcare design system documentation
**Stack**: Node.js, Nunjucks, Eleventy, nhsuk-frontend

**What makes it exceptional**:
- Evidence-based component documentation: each component page cites user research that motivated its design
- Accessibility classified by user role (screen reader users, keyboard users, low-vision users, cognitive impairments)
- Do/Don't examples for every content and component decision
- WCAG 2.2 AA compliance built into component specifications, not retrofitted
- Progressive enhancement as first principle: every feature works without JavaScript
- Crown copyright content maintained by NHS Digital team with clinical review process

**Reuse value**: Accessibility documentation methodology, evidence-based design process, component specification format

---

## Tier 2 — High Craft (score 40–44/50)

### antfu.me
**URL**: https://antfu.me
**Source**: antfu/antfu.me (SHA: 7db9f1e, MIT)
**Type**: Developer personal site
**Stack**: Vue 3, Vite, UnoCSS, VueUse

**Key techniques**:
- Minimal dependency footprint; entire site builds in under 3 seconds
- UnoCSS with custom rules for signature gradient text effect
- Accessible dark/light toggle with system-preference sync
- Markdown-driven blog with footnotes and math rendering
- Work page generated from GitHub activity

---

### payloadcms.com
**URL**: https://payloadcms.com
**Source**: payloadcms/website (SHA: df88d1d, MIT)
**Type**: Product marketing site (self-referential)
**Stack**: Next.js App Router, Payload CMS 3.0, TypeScript

**Key techniques**:
- Self-hosted CMS powering its own marketing site — "dogfooding" at production scale
- Payload 3.0 custom fields and block types for flexible marketing sections
- Next.js RSC pages with CMS-driven dynamic content
- Code block components with syntax highlighting embedded in CMS content

---

### demo.vercel.store (vercel/commerce)
**URL**: https://demo.vercel.store
**Source**: vercel/commerce (SHA: 3761e52, MIT)
**Type**: Commerce showcase
**Stack**: Next.js App Router, RSC, Server Actions, Shopify Storefront API

**Key techniques**:
- Server Actions for cart mutations (no client-side API calls)
- RSC-driven product pages with streaming product recommendations
- Provider-agnostic product interface: swap Shopify for BigCommerce without UI changes
- Edge-compatible architecture: no Node.js APIs in request path

---

## Industry Sections

### Healthcare

| Application | URL / Source | Stack | What to Study |
|------------|-------------|-------|---------------|
| Foomedical (patient portal) | medplum/foomedical | React + FHIR R4 + Mantine | FHIR auth flow, health record UI |
| Chart Demo (clinician) | medplum/medplum-chart-demo | React + FHIR R4 | Clinical timeline, encounter notes |
| NHS Service Manual | nhsuk-service-manual | Nunjucks + Eleventy | Evidence-based design docs, accessibility |

**Healthcare-specific notes**:
- All Medplum examples use FHIR R4 standards — clinical data must be structured to spec
- SMART on FHIR OAuth2 is the authentication standard; see foomedical for implementation
- NHS Service Manual is code+content; only the code is MIT; Crown Copyright applies to content

---

### Commerce / E-commerce

| Application | URL / Source | Stack | What to Study |
|------------|-------------|-------|---------------|
| Vercel Commerce | demo.vercel.store | Next.js App Router + RSC | Server Actions cart, RSC product pages |
| Hydrogen Skeleton | Shopify/hydrogen | React Router v7 + GraphQL | Form-action cart, Oxygen edge cache |
| Medusa Starter | medusajs/nextjs-starter | Next.js + React Query | Self-hosted commerce backend |
| Saleor Storefront | saleor/storefront | Next.js + Stripe | In-app checkout (no redirect) |
| Spree Storefront | spree/storefront | Next.js + Radix | Playwright test architecture |

**Commerce-specific notes**:
- Saleor storefront: FSL-1.1 license — free for non-competing products only
- Hydrogen: requires Shopify store; Oxygen for edge deployment
- Best cart pattern comparison: Server Actions (Vercel) vs. HTML form actions (Hydrogen) vs. React Query (Medusa)

---

### Developer Tools / Infrastructure

| Application | URL / Source | Stack | What to Study |
|------------|-------------|-------|---------------|
| node-modules-inspector | node-modules.dev | Vue 3 + WebContainer | Dependency graph visualization |
| antfu/eslint-config | — | ESLint Flat Config | Composable config factory |
| vitesse | vitesse.netlify.app | Vue 3 + Vite | Auto-import + file-routing pattern |

---

### Personal Sites / Portfolio

| Site | Source | Stack | Notable Techniques |
|------|--------|-------|-------------------|
| blog.maximeheckel.com | MaximeHeckel/blog.maximeheckel.com | Next.js + MDX + R3F | GPGPU in articles, command menu |
| antfu.me | antfu/antfu.me | Vue 3 + UnoCSS | Minimal, fast, markdown-first |
| mxb.dev | maxboeck/mxb | Eleventy | Web standards, no-JS fallbacks |
| paco.me | pacocoursey/paco | Next.js (archived 2022) | Minimal dark aesthetic |
| brittanychiang.com | bchiang7/v4 | Gatsby (2021) | Portfolio structure reference |
| taniarascia.com | taniarascia/taniarascia.com | Gatsby | Long-form technical blog |

---

### 3D / Creative

| Site | Source | Stack | Notable Techniques |
|------|--------|-------|-------------------|
| bruno-simon.com | brunosimon/folio-2019 | Vanilla Three.js | Application singleton, shader loading |
| 14islands.com | (not fully open source) | R3F | Scroll-driven 3D (see r3f-scroll-rig) |

---

### Design Systems / Documentation

| System | Source | Stack | Notable Techniques |
|--------|--------|-------|-------------------|
| NHS Service Manual | nhsuk-service-manual | Nunjucks + Eleventy | Evidence-based component docs |
| MaximeHeckel Design System | MaximeHeckel/design-system | Stitches + Radix | Glass material, token architecture |
| Rauno UI Playbook | raunofreiberg/ui-playbook | Next.js + MDX | UI behavior specification format |

---

## Emergency and Resilience Sites

### maxboeck/emergency-site
**URL**: Template (no live URL)
**Stack**: Eleventy + Netlify CMS

Purpose-built for crisis scenarios: zero external dependencies, zero JavaScript (all inline), PurgeCSS-stripped CSS. Deploy survives infrastructure outages because it requires no CDN, no external fonts, no API calls. The anti-fragile reference for sites that must work when everything else fails.

---

## Accessibility-First Sites

### nhsuk-service-manual — Gold Standard
The NHS Service Manual documents every component with:
1. **Research basis**: why this pattern was chosen over alternatives
2. **Accessibility specification**: which WCAG success criteria it addresses
3. **User research citations**: links to GDS/NHS Digital research reports
4. **Known failure modes**: documented edge cases where the pattern breaks

No other public design system documentation matches this level of rigor.

### antfu.me — Lightweight Accessible
No motion without `prefers-reduced-motion` check. Dark/light via CSS media query with `<script>` injection to prevent flash. Color contrast validated by UnoCSS custom rules.
