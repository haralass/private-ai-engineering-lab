---
lab_label: person4
research_date: 2026-06-24
last_updated: 2026-06-24
---

# person4 — Open Questions

---

## Answered from code analysis

### Runtime simulator intention — CLOSED

**Q: Is a runtime simulator planned for a later version?**
→ **Explicitly deferred.** The README states "not a simulator" as a v5 design
decision. The presence of `powered_on`, `operational`, `locked`, `open`, `clean`
state flags in the schema, and the needs/resources model, makes it clear the
architecture anticipates a simulator but intentionally excludes it from this version.

### LLM choice rationale — CLOSED

**Q: Why Gemini 2.5 Flash rather than Claude or GPT-4?**
→ **Confirmed technical reason:** `responseJsonSchema` in Gemini API (used with
`responseMimeType: "application/json"`) constrains output at the model level before
AJV validation. This model-level constraint + AJV + bespoke semantic validator is the
dual-layer approach. At temperature 0.35, Gemini 2.5 Flash produces low-variance
structured output. The bespoke semantic validator still catches ~10–20% of schema
violations that model-level constraints miss (referential integrity, semantic rules).
This is a deliberate technical choice, not a cost or quota shortcut.

### Integration direction — CLOSED

**Q: Is person4 working on integrating LLM-generated smart objects into a Unity game?**
→ **Strong inference: yes.** The profile README explicitly mentions "AI-driven game
design" as an area of exploration. llm-smart-objects and the 3D platformer are the
two most recent projects. The smart objects schema (state flags, resources, interactions)
maps directly to the kind of interactive object data a Unity game would need. The
missing piece (runtime simulator) would complete the pipeline.

**Q: Is there a connection between the needs catalogue and the existing 3D platformer?**
→ **Plausible.** The default need set (`hunger`, `thirst`, `rest`, `comfort`,
`entertainment`, `social`, `safety`, `curiosity`, `bladder`, `hygiene`,
`physical_activity`, `mental_activity`) matches NPC simulation needs for an
open-world or exploration game — not a typical 3D platformer. More likely the
smart objects system targets a different or future game, not the current platformer.

---

## Still open — requires user input

### llm-smart-objects

- What were versions 1–4? What changed in each major iteration?
- Is there a version 6+ under active development?

### 3d-platformer-unity

- Was this built for a specific UCY CS course (e.g., EPL course)?
  (Similar pattern seen in person2 and person3 repos.)
- Are the `Unfinished/` scenes (`BonusRoom`, `SampleScene`) intended to be completed?
- Is there a plan to expand beyond Hub/Biome1/Biome2/Biome3?
