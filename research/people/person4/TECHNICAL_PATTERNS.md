---
lab_label: person4
research_date: 2026-06-24
sources_with_full_code: []
sources_local_research_only: [llm-structured-object-generator, unity-3d-platformer]
sources_reference_only: [person4-github-profile]
---

# person4 — Technical Patterns

All patterns below derived from actual code analysis. Clean-room reimplementation required
for all patterns (no license on either repository).

---

## Pattern 1 — Dynamic schema injection for LLM structured generation

**Source:** `llm-smart-objects`, `src/validation/smartObjectSchema.js` + `server.js`
(local-research-only, no license — clean-room reimplementation only)

`buildSmartObjectResponseSchema(needs)` takes caller-supplied need names and deep-merges
them into a base JSON Schema to produce a dynamic `enum: [allowedNeedNames]` for the
`need` field in `advertisements`. The same schema object is:
1. Sent to Gemini as `responseJsonSchema` (generation constraint)
2. Compiled by AJV at module load time (validation constraint)

This creates a single source of truth: schema drift between generation constraint and
validator is structurally impossible. The dynamic enum prevents the LLM from hallucinating
values from outside the caller's input.

**Generalizes to:** any LLM generation task where the valid value set is caller-supplied
at runtime (product types, action names, entity tags, user-defined categories).

---

## Pattern 2 — Dual-layer LLM output validation (AJV + bespoke)

**Source:** `llm-smart-objects`, `src/validation/` (local-research-only)

Two validation stages on every LLM output:

**Stage 1 — AJV JSON Schema** (`allErrors: true`, compiled once):
handles syntax/type/range/enum/additionalProperties. Fast. Language-declarative.
Cannot express referential integrity or cross-field logic.

**Stage 2 — Bespoke semantic checker** (`validateSmartObjectData`):
handles: declared-but-unreferenced states/resources, cross-field consistency
(capacity type ↔ availability type), forbidden runtime concept names (occupancy fields),
empty advertisement without effect, same-object-only requirement references.

Errors from both stages are concatenated into a single flat list before the repair prompt
and the 422 response.

**Why this matters:** JSON Schema cannot express "every declared state must be used."
AJV handles the 80% of structural errors; the bespoke checker handles the 20% of semantic
errors that LLMs reliably hallucinate. The separation is clean and independently testable.

**Generalizes to:** any constrained generation task where the output has referential integrity
requirements between declared entities and their usages.

---

## Pattern 3 — Single LLM repair pass with error list injection

**Source:** `llm-smart-objects`, `src/prompt/repairPrompt.js` (local-research-only)

After failed first LLM call:
```
"Correct the invalid smart-object JSON output."
"Validation errors:"
"- ${error1}"
"- ${error2}"
...
[invalid output verbatim at end]
```

Key design decisions:
- Exact error list injected (not "there are some errors" — each error message is specific)
- Invalid output included verbatim (LLM can see what it produced)
- Schema re-embedded (LLM sees the constraint again)
- One repair attempt only (no third try — latency cap)
- Hard fail on second failure (422 with repair attempt's error list)

**Generalizes to:** any LLM pipeline where the output must satisfy constraints.
The pattern (generate → validate → repair once → fail hard) is more predictable than
silent retry loops and easier to debug.

---

## Pattern 4 — Injectable LLM client for testable pipelines

**Source:** `llm-smart-objects`, `server.js` `createApp(options)` (local-research-only)

```javascript
function createApp({ llmClient } = {}) {
  const client = llmClient || defaultGeminiClient;
  // ... Express setup
}
```

Tests inject a mock `llmClient`:
```javascript
const mockLLM = (responses) => {
  const q = [...responses];
  return async () => ({ text: q.shift() });
};
const app = createApp({ llmClient: mockLLM([validJson, repairJson]) });
```

This pattern makes the entire HTTP + validation + repair pipeline testable without
any network calls. The mock pre-scripts up to N responses (for repair tests: 2 responses).

**Generalizes to:** any LLM-backed service. The factory function pattern (`createApp`,
`createService`, `createAgent`) with injected LLM client should be the default
architecture for testable LLM applications.

---

## Pattern 5 — CharacterController platformer physics with unlockable abilities

**Source:** `3d-platformer-unity`, `Assets/Scripts/Player/PlayerMotorCC.cs`
(local-research-only, no license — study only)

Key manual physics decisions:
- Jump: `v = sqrt(2 * jumpHeight * -gravity)` — parametric formula, `jumpHeight = 1.6m`
- Variable jump height: `2.2×` gravity multiplier when jump released while ascending
- Coyote time: 0.12s window after leaving ledge
- Jump buffer: 0.12s pre-ground window
- Ability gates: `doubleJumpUnlocked`, `dashUnlocked`, `wallJumpUnlocked` booleans
- Priority: wall jump > coyote jump > double jump

Gravity flip: smooth rotation (360°/s on X euler) + inverted `IsGrounded` raycast + inverted
velocity sign in `DoJump`. Platform integration: `OverlapBox` + downward raycast to detect
player standing, then `motor.SetPlatformMovement(_deltaMovement)` to inherit platform velocity.

**Generalizes to:** any 3D platformer in Unity needing precise movement with progressive
ability unlocking.

---

## Pattern 6 — Singleton event-bus with DontDestroyOnLoad

**Source:** `3d-platformer-unity`, `Assets/Scripts/Systems/PlayerStats.cs`
(local-research-only, study only)

`PlayerStats` acts as centralized game state + event bus:
```csharp
public event Action<int, int> OnHeartsChanged;
public event Action<int, int> OnStarsChanged;
// ... etc.
```

UI subscribes: `PlayerStats.Instance.OnHeartsChanged += UpdateHeartsDisplay`.
Health controller subscribes: `PlayerStats.Instance.OnHeartsChanged += CheckGameOver`.

Bootstrapper instantiates all singletons in dependency order on Awake:
AudioManager → PlayerStats → RespawnManager → HUD → CameraRig → Player → SceneTransitionManager.
Duplicate-destruction pattern: `if (Instance != null && Instance != this) { Destroy(gameObject); return; }`.

**Generalizes to:** any Unity game with persistent cross-scene state. The key is that
`PlayerStats` is the single source of truth and all consumers are observers, not pollers.

---

## Reuse summary

| Pattern | Usability | Condition |
|---|---|---|
| Dynamic schema injection | Clean-room reimplementation | No license |
| AJV + bespoke dual validation | Clean-room reimplementation | No license |
| Repair prompt with error list | Clean-room reimplementation | No license |
| Injectable LLM client factory | Clean-room reimplementation | No license |
| CharacterController platformer | Study only | No license |
| Singleton event-bus (Unity) | Study only | No license |
