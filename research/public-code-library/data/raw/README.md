# Raw Public-Code-Library Data

This directory contains raw or generated data captured during the public-code-library research pass.

These files are evidence and reproducibility inputs, not human-authored synthesis:

| File | Purpose | Reproducibility | Canonical Consumer |
|---|---|---|---|
| `all_profile_repos.json` | Raw profile-repository inventory grouped by researched person/profile. | Regenerable by rerunning the original GitHub profile inventory process; exact command was not preserved in this cleanup pass. | Historical evidence for candidate discovery. |
| `additional_candidates_raw.json` | Raw additional candidate list from later discovery agents. | Regenerable only by repeating that discovery pass; treat as captured evidence. | `candidate-pool.yaml`, `synthesis/new-candidates-discovered.md`. |
| `repo_metadata.json` | Snapshot of selected repository metadata. | Regenerable from GitHub API for public repositories, but values may change over time. | Manifest verification and historical context. |
| `repo_shas.json` | Commit SHAs recorded for selected analyzed repositories. | Regenerable from local clones or GitHub if refs still exist. | `manifest.yaml` pinned commit audit. |

Future raw dumps should be added here only when they contain evidence not already represented in `manifest.yaml`, repository dossiers, or synthesis reports. Disposable caches should remain untracked.
