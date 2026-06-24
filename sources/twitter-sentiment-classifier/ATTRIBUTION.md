# Attribution — twitter-sentiment-classifier

## License status

No LICENSE file was found in this repository at the pinned commit.
License status: **unknown / pending review**.

**Import mode:** `local-research-only` — code was studied but not committed to this lab.
No code from this source may be copied into any component, reference-implementation,
or redistributed artifact until a license is confirmed.

## Source

- **Functional name:** twitter-sentiment-classifier
- **Source label:** student-collab
- **Upstream repository:** Matheos-Ioannides/epl499_group_project_TeamWIP
- **Pinned commit SHA:** `cc1651262581da8fef830e47031310b216557bf5`
- **Retrieved:** 2026-06-23
- **Retrieval method:** read-only clone; no star, fork, watch, or follow
- **upstream/ status:** Removed 2026-06-24 (never committed to main branch)

## Security review

Secret scan: CLEAN. No credentials or sensitive data found. Training/test CSV files
contain only public tweet text (anonymized with `@user` placeholders).

## Patterns studied

Group NLP project (EPL499, TeamWIP):
- ekphrasis tokenizer for Twitter-specific text normalization
- Feature stacking: Bag-of-Words, TF-IDF, and Word2Vec representations
- L1 feature selection to reduce dimensionality
- Logistic regression classifier (85% accuracy on Twitter sentiment)

## Lab use restrictions

- No code from this source may be copied until a license is confirmed.
- Study of patterns is permitted; clean-room reimplementation is allowed.
- `copy_allowed: false` in SOURCE.yaml and license-matrix.yaml.
