---
lab_label: person4
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person4 — Open Questions

Evidence labels used in this file:
- `confirmed-from-code` — verified in vendored upstream/ or directly read files
- `confirmed-from-documentation` — verified from README, docs, or comments in the repo
- `strong-inference` — high-confidence conclusion from indirect evidence; not confirmed
- `hypothesis` — plausible but speculative; requires verification
- `still-open` — cannot be answered from available lab content

Only `confirmed-from-code` and `confirmed-from-documentation` are treated as CLOSED.

---

## Confirmed [CLOSED]

**Gemini structured output:** `confirmed-from-code` — `responseJsonSchema` with
`responseMimeType: "application/json"` is used in the API call. This is the Gemini
model-level structured output constraint, used in combination with AJV validation and
a bespoke semantic validator.

**Runtime simulation out of scope:** `confirmed-from-documentation` — The README explicitly
states the current version is "not a simulator." Runtime simulation is out of scope for
this version.

---

## Inferences requiring confirmation

**Gemini over Claude/GPT — rationale:** `strong-inference` — A possible technical rationale
is that `responseJsonSchema` in the Gemini API applies structural constraints at the model
level before validation. Whether this was the deciding factor vs. cost, quota access,
or personal familiarity with the API is not stated anywhere in the code or documentation.

**Unity + LLM integration:** `hypothesis` — The profile README mentions "AI-driven game design"
as a current area of exploration. Given that both llm-smart-objects and the 3D platformer are
person4's two most recent projects, integration is plausible. No evidence of an active
integration branch, combined repository, or integration planning document was found.

**Runtime simulator planned for a future version:** `hypothesis` — The README's "not a simulator"
phrasing is consistent with deferral rather than rejection, but this is not explicitly stated.
Whether a simulator is planned for a later version remains open.

---

## Still open — requires user input or upstream access

### llm-smart-objects [`still-open`]

- What were versions 1–4? What changed in each major iteration?
- Is a version 6+ under active development?
- What was the deciding factor for choosing Gemini over Claude or GPT?
  (currently `strong-inference`)

### 3d-platformer-unity [`still-open`]

- Was this built for a specific UCY CS course?
  (Profile README says "made for Computer Science course" — which course?)
- Are the `Unfinished/` scenes (`BonusRoom`, `SampleScene`) intended to be completed?
- Is there a plan to expand beyond Hub/Biome1/Biome2/Biome3?

### Intersection of both projects [`still-open`]

- Is person4 actively integrating LLM-generated smart objects into a Unity game?
  (currently `hypothesis`)
- Is a runtime simulator planned for a future version of llm-smart-objects?
  (currently `hypothesis`)
