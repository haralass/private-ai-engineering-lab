# Rejected Repositories
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Repositories from the approved list that were found to have insufficient lab value after analysis.

---

## Rejected from the Approved List

### pacocoursey/paco
- **URL**: https://github.com/pacocoursey/paco
- **Status**: ARCHIVED (April 2022)
- **Reason for rejection**: Archived personal website with no license. No novel patterns not better covered by other repositories from the same author (cmdk, next-themes). Personal branding content not reusable.
- **What was valuable**: Nothing extractable.
- **Decision**: reject

### tobiasahlin/animated-menu
- **URL**: https://github.com/tobiasahlin/animated-menu
- **Last Push**: 2016-05-03 (10 years ago)
- **Reason for rejection**: jQuery-based tutorial from 2016. The hamburger-to-X morphing concept is the only value, but it is better demonstrated in modern CSS without JavaScript. No standalone reusable components.
- **What was valuable**: The concept (hamburger → X CSS morph). The technique is documented better in modern references and is implementable in 10 lines of CSS.
- **Decision**: reject (concept noted in UI Pattern Catalogue)

### bchiang7/octoprofile
- **URL**: https://github.com/bchiang7/octoprofile
- **Last Push**: 2023-01-05
- **Reason for rejection**: No license. GitHub profile stats visualizer — a common category with many better examples. The language frequency aggregation algorithm is trivial. No novel patterns.
- **What was valuable**: Nothing extractable due to no license.
- **Decision**: reject

### maxboeck/whimsical
- **URL**: https://github.com/maxboeck/whimsical
- **Status**: Stale (2023)
- **Reason for rejection**: A curated link list website. The website URLs constitute the value, and they are better accessed at the live site (https://whimsical.club) than via the repo. No reusable code.
- **What was valuable**: The live site itself as a discovery resource. The GitHub issue template for community submissions is noted in datasets catalogue.
- **Decision**: metadata-only (the live site is the resource)

### MaximeHeckel/linear-vaporwave-react-three-fiber
- **URL**: https://github.com/MaximeHeckel/linear-vaporwave-react-three-fiber
- **Last Push**: 2022-11-16
- **Reason for rejection**: Three.js/R3F demo from 2022. R3F API has changed significantly. The displacement map terrain technique is the only value, but it is better learned from current Three.js documentation. Very few source files.
- **What was valuable**: Visual inspiration; displacement map + metalness PBR technique in R3F.
- **Decision**: low/metadata-only

### taniarascia/taniarascia.com
- **URL**: https://github.com/taniarascia/taniarascia.com
- **Reason for rejection**: Gatsby personal website. Gatsby is a declining framework (no longer relevant for new projects). The dark mode via CSS custom properties pattern and the SEO component are the only reusable pieces, both easily found elsewhere.
- **What was valuable**: Dark mode CSS custom properties pattern (documented in UI catalogue).
- **Decision**: low (not cloned with fresh clone; reject for active reference)

### bchiang7/octoprofile (duplicate entry)
- See above.

---

## Additional Candidates Rejected (From Profile Discovery)

These repositories were discovered during profile exploration but are rejected after review.

### sindresorhus/LaunchAtLogin-Legacy
- **Status**: ARCHIVED
- **Reason**: Superseded by sindresorhus/LaunchAtLogin-Modern. The legacy Login Items API it uses is deprecated by Apple. Do not use.

### sindresorhus/touch-bar-simulator
- **Status**: ARCHIVED
- **Reason**: Touch Bar was removed from all MacBook Pro models as of 2021. This simulator has no modern use case.

### antfu/shikiji
- **Status**: ARCHIVED
- **Reason**: Experimental pre-release of what became Shiki v1. Superseded by the main shiki package.

### pacocoursey/paco (personal website)
- Already rejected above.

---

## Repositories Outside Scope (Cloned by Previous Process, Removed)

These repositories were incorrectly cloned by a previous automated process. They do not belong to any of the 13 people in scope. Their analysis files and external-sources entries have been removed.

| Repository | Owner | Why removed |
|-----------|-------|-------------|
| 14islands/r3f-scroll-rig | 14islands (org) | Not one of 13 people |
| Shopify/hydrogen | Shopify | Not one of 13 people |
| brunosimon/folio-2019 | Bruno Simon | Not one of 13 people |
| epicweb-dev/epic-stack | epicweb-dev (org) | Not one of 13 people |
| nhsuk/nhsuk-service-manual | nhsuk (org) | Not one of 13 people |
| vercel/commerce | Vercel (org) | Not one of 13 people |
| medplum/* | medplum (org) | Not one of 13 people |

---

## Summary

| Decision | Count |
|---------|-------|
| Rejected from approved list | 4 |
| Downgraded to metadata-only | 2 |
| Additional candidates rejected | 4 |
| Out-of-scope repos cleaned up | 7 |
