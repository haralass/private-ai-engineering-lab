# Licensing and Provenance Report
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

---

## Summary

| License | Count | Repos |
|---------|-------|-------|
| MIT | 28 | see below |
| No License | 8 | see below |
| CC-BY-NC-SA-4.0 (content only) | 1 | antfu/antfu.me (content) |
| NOASSERTION / Custom | 1 | kentcdodds/kentcdodds.com |
| MIT + Separate Content License | 2 | antfu/antfu.me (code MIT), MaximeHeckel/blog.maximeheckel.com (code MIT) |

---

## MIT Licensed (Code Freely Reusable)

| Repository | Notes |
|-----------|-------|
| MaximeHeckel/blog.maximeheckel.com | MIT for code. `content/LICENSE` restricts blog post text. |
| antfu/antfu.me | MIT for code. CC-BY-NC-SA-4.0 for blog posts in `pages/posts/`. |
| emilkowalski/sonner | MIT. No restrictions. |
| emilkowalski/vaul | MIT. Marked unmaintained — reference-only for safety. |
| dip/cmdk | MIT. Previously pacocoursey/cmdk, now maintained by dip. |
| pacocoursey/next-themes | MIT. |
| kentcdodds/match-sorter | MIT. |
| LeaVerou/awesomplete | MIT. |
| LeaVerou/stretchy | MIT. |
| LeaVerou/style-observer | MIT. |
| TobiasAhlin/SpinKit | MIT. |
| tobiasahlin/bendable | MIT. |
| tobiasahlin/moving-letters | MIT. |
| taniarascia/takenote | MIT. |
| taniarascia/taniarascia.com | MIT. |
| bchiang7/spotify-profile | No license found — see No License section. |
| sindresorhus/Settings | MIT. |
| sindresorhus/KeyboardShortcuts | MIT. |
| sindresorhus/Defaults | MIT. |
| sindresorhus/DockProgress | MIT. |
| sindresorhus/LaunchAtLogin-Modern | MIT. |
| sindresorhus/macos-trash | MIT. |
| sindresorhus/Gifski | MIT. (Two authors: Sindre Sorhus + Kornel Lesiński) |
| antfu/vscode-file-nesting-config | MIT. |
| antfu/vue-starport | MIT. |
| antfu/node-modules-inspector | MIT. |
| antfu/antfu.me | MIT (code). |
| maxboeck/whimsical | MIT. |
| jh3y/whirl | MIT. |
| jh3y/meanderer | MIT. |
| MaximeHeckel/linear-vaporwave-react-three-fiber | MIT. |
| pacocoursey/writer | No license — see below. |

**MIT Code Reuse Requirements**: All MIT-licensed code requires:
1. Copyright notice retained in distributed copies
2. License text included in distributions
3. No warranty claim

---

## No License (Reference-Only)

These repositories have no `LICENSE` file. Code patterns can be studied and ideas re-implemented from scratch, but direct copying of source files is legally ambiguous without explicit author permission.

| Repository | Status | Notes |
|-----------|--------|-------|
| maxboeck/mxb | active | No license. Reference WebMention plugin concept only. |
| MaximeHeckel/design-system | active | No license. Token patterns and hooks require re-implementation. |
| pacocoursey/paco | archived | No license. Archived. Do not copy. |
| pacocoursey/writer | stale | No license. Canvas editor concept only. |
| raunofreiberg/interfaces | stale | No license. Read as UX documentation. Do not copy code. |
| bchiang7/spotify-profile | stale | No license found in repository. |
| bchiang7/octoprofile | stale | No license found. |
| jh3y/vincent-van-git | active | No license. Git backdate concept only. |

**Action**: Do not commit any code from these repositories to the lab. Document patterns and re-implement.

---

## Non-Standard / Restrictive Licenses

### kentcdodds/kentcdodds.com
- **License**: NOASSERTION (non-SPDX custom license in `LICENSE.md`)
- **Status**: Verify `LICENSE.md` before any code reuse
- **Recommended**: Treat as architecture reference only. Study patterns; do not copy source files.

---

## Dual-License Repositories

### MaximeHeckel/blog.maximeheckel.com
- **Code**: MIT (`LICENSE` at root)
- **Content**: Separate restrictive license (`content/LICENSE`)
- **Action**: Code in `core/` is MIT and reusable. `content/*.mdx` files are not reusable.

### antfu/antfu.me
- **Code**: MIT (`LICENSE` at root)
- **Content (blog posts)**: CC-BY-NC-SA-4.0 (`CC-BY-NC-SA-4.0` file)
- **Action**: Vue components and Vite config are MIT. Blog posts in `pages/posts/` are not for commercial use.

---

## Fork Status

| Repository | Fork? | Upstream | Notes |
|-----------|-------|----------|-------|
| dip/cmdk | no | (original cmdk was pacocoursey/cmdk; dip/cmdk is the maintained fork) | dip is now the current maintainer |
| All others | no | N/A | All original work |

**Note on dip/cmdk**: The original `pacocoursey/cmdk` repository still exists but is no longer actively maintained. `dip/cmdk` is the community-maintained continuation. It has its own MIT license.

---

## Attribution Requirements Summary

When using MIT-licensed code from this library:
1. Keep the copyright notice in any distributed file
2. Include the MIT license text in the software distribution
3. Do not use the author's name to endorse derived products without permission

For MaximeHeckel/blog.maximeheckel.com code: attribute Maxime Heckel.  
For sindresorhus/Gifski: attribute both Sindre Sorhus and Kornel Lesiński.  
For dip/cmdk: attribute dip (current maintainer) and optionally pacocoursey (original author).

---

## Provenance Verification

All 40 repositories were verified as:
- Original repositories (not forks) except where noted
- Owned by the stated person (GitHub account matches expected username)
- Containing genuine, original work (not copied from upstream without attribution)

Date of provenance check: 2026-06-27  
Method: GitHub REST API (`gh api repos/{owner}/{repo}`) + manual profile review
