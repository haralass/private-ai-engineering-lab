# Verified Live-Site and Official-Demo Source Pairs

**Public Code Research Library**
**Verification method**: GitHub commit history cross-referenced with live site deployment; CI/CD pipelines; official author confirmation in README or docs.
**Date**: 2026-06-27

---

## Verified Exact Live-Site Sources

These repositories are confirmed to be the exact source code deployed to the listed live URL. Not approximations, demos, or inspirations — the actual build artifact.

| Repository | Live URL | SHA Analyzed | Verification Evidence |
|-----------|----------|-------------|----------------------|
| MaximeHeckel/blog.maximeheckel.com | https://blog.maximeheckel.com | c3ef52f | README states "source code for blog.maximeheckel.com"; Vercel deployment in package.json; vercel.json present |
| nhsuk/nhsuk-service-manual | https://service-manual.nhs.uk | 229b262 | Maintained by NHS Digital; Heroku deploy config in repo; Crown Copyright notice confirms official deployment |
| brunosimon/folio-2019 | https://bruno-simon.com | 540f135 | Author confirmed on Twitter/X; Three.js car demo matches live site exactly; commit message references deployment |
| payloadcms/website | https://payloadcms.com | df88d1d | Self-referential: Payload CMS site built with Payload CMS; README states "payloadcms.com source" |
| antfu/antfu.me | https://antfu.me | 7db9f1e | README confirms; netlify.toml deploy config; same styling/components visible on live site |
| maxboeck/mxb | https://mxb.dev | 5a922ea | Eleventy config with output to deploy; GitHub Pages action in .github/workflows/ |

---

## Verified Official Demo Sources

These repositories are the official demonstration or showcase for a product, framework, or library — maintained by the same organization or person who owns the product.

| Repository | Product / URL Demonstrated | SHA Analyzed | Verification Evidence |
|-----------|--------------------------|-------------|----------------------|
| medplum/foomedical | Medplum platform | 92b9c07 | Official Medplum org repo; README: "Reference application built on Medplum"; same org as Medplum core |
| medplum/medplum-chart-demo | Medplum clinical UX | 52d0817 | Official Medplum org; README: "Demo of a provider-facing chart built with Medplum" |
| medplum/medplum-demo-bots | Medplum Bot runtime | d28fda6 | Official Medplum org; README references medplum.com bot documentation |
| Shopify/hydrogen | Shopify Headless | b4df055 | Official Shopify org; README: "Shopify's headless commerce framework"; shipped to shopify.dev |
| vercel/commerce | https://demo.vercel.store | 3761e52 | Official Vercel org; README links live demo; vercel.json deployment present |
| 14islands/codrops-scroll-rig-tutorial | r3f-scroll-rig tutorial | f903c1e | Same org as r3f-scroll-rig; linked in r3f-scroll-rig README; Codrops article companion |
| saleor/storefront | Saleor commerce | 14ffe08 | Official Saleor org; README: "official Saleor storefront starter" |

---

## Relationships Explicitly Excluded

The following were investigated for live-site or official-demo status but **not verified** and therefore excluded from this list.

| Repository | Claimed Relationship | Exclusion Reason |
|-----------|---------------------|-----------------|
| 4Akera/openkairo | Possible product demo | Author identity not verified; no public live URL |
| brunosimon/folio-2025 | Newer personal portfolio | Not yet cloned; license unknown; relationship unverified |
| raunofreiberg/interfaces | rauno.me/craft/interfaces | Not exactly the live site — interfaces is a sub-page; no deploy config confirmed |
| bchiang7/v4 | brittanychiang.com | v4 was a past version; current site may have diverged; deployment config absent |
| maxboeck/emergency-site | Emergency template | A template/starter, not Bruno's live deployed site |
| antfu-collective/vitesse | vitesse.netlify.app | Official demo deploys automatically; confirmed via netlify.toml BUT classified as production-starter not live-site-source |

---

## Classification Notes

- **exact-live-site-source**: Repository IS the deployed site. Building and deploying this repo produces the live URL.
- **official-demo-source**: Repository is an official demonstration maintained by the same org/person as the product. It shows how to use the product correctly.
- If a repository's live-site relationship cannot be confirmed from public evidence, it is excluded from this file regardless of probability.
