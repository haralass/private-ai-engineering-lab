---
lab_label: person4
research_date: 2026-06-24
---

# person4 — Repositories Reviewed

---

## 1. llm-structured-object-generator (llm-smart-objects)

**Import mode:** local-research-only (no LICENSE)
**Coverage:** Full code analysis — all source files studied locally, not committed
**Stack:** Node.js 18+, Express, vanilla JS frontend, AJV 8.17.1, node:test (built-in)
**LLM provider:** Google Gemini 2.5 Flash (`gemini-2.5-flash`, structured output mode)

### Architecture

`createApp(options)` — factory function (not a module-level Express app). Injects
`llmClient` for testing. Single route: `POST /api/generate-smart-objects`.
Static files served from `public/`. Server only runs if invoked directly (isDirectRun check).

**Request flow:**
1. `validateGenerationRequest(req.body)` — validates `locationDescription` (non-empty string)
   and `needs` array via `validateNeedCatalogue`
2. `buildSmartObjectPrompt({ locationDescription, needs })` — constructs full LLM prompt
3. `buildSmartObjectResponseSchema(needs)` — builds JSON Schema with dynamic `need` enum
   locked to submitted need names
4. First LLM call: `llmClient({ prompt, responseSchema })`
5. `validateSmartObjectOutput(firstText, needs)` — parse + AJV check + bespoke semantic checks
6. If invalid: `buildSmartObjectRepairPrompt(...)` → second LLM call → second validation
7. If repair fails: 422 with concatenated error list
8. LLM network failures: 502

### Prompt Engineering

`buildSmartObjectPrompt` assembles a long prompt (join of array). Opening verbatim:
```
You generate JSON for an experimental LLM Smart Object Generator.

Task:
Generate a concise set of useful physical or environmental objects appropriate for the described location.
For each object, generate one or more meaningful interactions. Each interaction may advertise
only the supplied NPC needs it can meaningfully satisfy.

Location description: ${locationDescription}
```

Then per-need calibration context (name, definition, weak/strong reference example + weight).
Then extensive domain rules (27 numbered generation rules covering duration, capacity, state,
resources, effects, advertisements, weight calibration). Then `JSON.stringify(responseSchema)`
embedded at the end. Closes with: `"Return raw JSON only. Do not include Markdown, code fences,
comments, or explanations."`

Repair prompt: opens with correction instruction, includes exact validation error list
(`- error1\n- error2`), re-states location + needs + schema + invalid output verbatim.

### JSON Schema (full structure)

`smartObjectSchema.js` defines:
```
Root: location (string), objects (array, minItems 1) of:
  id (string), type (string)
  capacity: type (enum: "limited"|"unlimited"), slots? (int 1–100, only for "limited")
  stateFlags: array of: id (enum: "powered_on"|"operational"|"locked"|"open"|"clean"), initial (bool)
  resources: array of: id (string ^[a-z][a-z0-9_]*$), initial (int 0–100000), maximum (int 1–100000)
  interactions: array (minItems 1) of:
    id (string ^[a-z][a-z0-9_]*$)
    duration: type (enum: "instant"|"fixed"|"continuous"), seconds? (number 0–86400)
    availability: type (enum: "always"|"when_capacity_available")
    requirements: array of: type ("state_equals"|"resource_at_least"), state?, value?, resource?, amount?
    effects: array of: type ("set_state"|"change_resource"), state?, value?, resource?, amount?
    advertisements: array of: need (enum locked to submitted need names), weight (0–1)
```
`additionalProperties: false` at every level.
`buildSmartObjectResponseSchema(needs)` deep-merges base schema to replace `need.type` with
`enum: allowedNeedNames`.

### Validation System

**Layer 1 — AJV 8.17.1** (`allErrors: true`): compiled once at module load.
Catches type errors, missing required fields, enum violations, range constraints, additionalProperties.

**Layer 2 — `validateSmartObjectData`** (bespoke): checks what AJV cannot:
- `fixed` duration: requires `seconds` > 0 and ≤ 86400; `instant`/`continuous`: must NOT have `seconds`
- `limited` capacity: must have `slots`; `unlimited`: must NOT have `slots`
- 13 forbidden availability runtime fields (occupancy concepts: available_slots, occupied, is_free, etc.)
- Deprecated `when_free` availability type
- Capacity-availability consistency: limited → must use `when_capacity_available`; unlimited → must use `always`
- `capacity` field must NOT appear on an interaction
- Every declared stateFlag.id must be referenced in at least one interaction's requirements
  (effect-only does not satisfy the rule)
- Every declared resource.id must be referenced in at least one requirement or effect
- Requirements/effects can only reference states/resources declared on the same object
- `state_equals` must not mix resource fields; `resource_at_least` must not mix state fields
- Duplicate object IDs, interaction IDs, state IDs, resource IDs, requirements, effects,
  advertisement needs
- `change_resource` amount must be non-zero integer
- Empty advertisements with no valid effects → error
- Advertisement need must be in submitted needs set

`parseJsonText`: strips Markdown code fences via regex before `JSON.parse`.

### Default Need Names

`hunger`, `thirst`, `rest`, `comfort`, `entertainment`, `social`, `safety`, `curiosity`,
`bladder`, `hygiene`, `physical_activity`, `mental_activity`. User-configurable;
validated against `^[a-z][a-z0-9_]*$`.

### Gemini Parameters

Model: `GEMINI_MODEL=gemini-2.5-flash` (env), `temperature: 0.35` (hardcoded), 
`responseMimeType: "application/json"`, `responseJsonSchema: responseSchema`,
`GEMINI_TIMEOUT_MS=60000`. API key as `x-goog-api-key` header (not URL param).

### Tests

5 test files using Node.js built-in `node:test` (no external test runner):
- `server.test.js`: 5 integration tests via `createApp` with mock `llmClient` (array of scripted responses)
- `validation.test.js`: ~90 unit tests on all error paths in semantic validator
- `geminiClient.test.js`: 4 tests (API key in header, structured output config, error extraction, timeout)
- `prompt.test.js`: 14 tests on prompt string content via `assert.match` regex
- `needCatalogue.test.js`: 8 tests on catalogue parse/validate/persist

### Frontend

`public/app.js` — vanilla ES module, no framework. Loads need catalogue from localStorage
(`llm-smart-object-need-catalogue`) or `/default-needs.json`. Autosaves with 150ms debounce.
On submit: `collectNeeds()` → client-side validation → POST to `/api/generate-smart-objects`.
Response displayed as formatted JSON in `<pre id="json-viewer">`, saved to history
(localStorage `llm-smart-object-history`, capped at 8 items). Export/import catalogue,
copy/download JSON, clear result, history management.

---

## 2. unity-3d-platformer (3d-platformer-unity)

**Import mode:** local-research-only (no LICENSE)
**Coverage:** All C# scripts analyzed locally — 40+ scripts across 14 directories
**Unity version:** 2022.3.62f3 LTS
**Tools:** Cinemachine, Unity Input System (new), TextMeshPro

### Script Inventory (key scripts)

**Player:**
- `PlayerMotorCC.cs` (1136 lines) — main player physics using CharacterController
- `PlayerInputReader.cs` — wraps Unity new Input System (`InputActionAsset`)
- `PlayerAnimator.cs` — animator state machine from motor events
- `AbilityPickup.cs` / `AbilityProgress.cs` — collectible ability unlocks
- `CinemachineDualLookInput.cs` — custom Cinemachine input provider

**Damage:**
- `PlayerHealthController.cs` — `TryTakeDamage(sourcePosition, amount)`, knockback via
  `motor.ApplyExternalImpulse`, invincibility frames (blink coroutine), death → `RespawnManager`
- `DamageOnTouch.cs` — trigger-based damage applier
- `DamageSource.cs` — marker component

**Systems:**
- `PlayerStats.cs` — persistent singleton (`DontDestroyOnLoad`). Tracks: currentHearts (3),
  maxHearts (3, cap 5), healthShardCount (4→+1 max heart), starsTotal, coins per biome
  (Hub/Biome1/Biome2/Biome3), deathsTotal, lastCheckpoint (CheckpointData: sceneName,
  position, rotation), ability flags (hasDoubleJump, hasDash, hasWallJump).
  Event delegates: `OnHeartsChanged`, `OnHealthShardsChanged`, `OnStarsChanged`,
  `OnCoinChanged`, `OnDeathsChanged`, `OnCheckpointChanged`, `OnAbilitiesChanged`.
- `Bootstrapper.cs` — Awake-time instantiation of persistent prefabs if absent:
  AudioManager → PlayerStats → RespawnManager → HUD → CameraRig → Player → SceneTransitionManager
- `CollectedItemsRegistry.cs` — tracks picked-up collectibles

**Respawn/Checkpoints:**
- `RespawnManager.cs` — persistent singleton. `RespawnPlayer(player)`: loads checkpoint scene,
  teleports. Listens to `SceneManager.sceneLoaded` for default spawn refresh.
- `Checkpoint.cs` — on player trigger enter → `PlayerStats.SetCheckpoint`
- `SpawnPoint.cs` — marker with SpawnId (string) + IsDefaultSpawn (bool)

**Transitions:**
- `SceneTransitionManager.cs` — persistent singleton. `RequestTransition(sceneName, spawnId)`:
  fade-out 0.25s → async scene load → teleport to named SpawnPoint → fade-in.
  `RequestRespawnToCheckpoint(cp)`: same with checkpoint position.

**Platforms:**
- `MovingPlatform.cs` — linear A→B, pause at endpoints, smooth step, player detection via
  OverlapBox + downward raycast, sets `motor.SetPlatformMovement` and `motor.SetOnPlatform`
- `CircularMovingPlatform.cs` — orbital variant
- `DisappearingPlatform.cs` — fade + remove collider on contact, respawn after timer
- `TrampolinePlatform.cs` — `motor.AddUpwardVelocityThisFrame`
- `IcePlatform.cs` — `motor.ApplyIceEffect(slipperiness, duration)`
- `GravityFlipVolume.cs` — `motor.SetGravityOverride(+25f)` + `motor.SetGravityFlip(true)`

**Enemies:**
- `HailShooterEnemy.cs` — cylindrical detection zone (10m radius, 20m height), burst fire,
  defeated by stomp (player falling + contact normal pointing up → bounce)

**Audio:**
- `AudioManager.cs` — persistent singleton. Music via 1 looping AudioSource. SFX via pool
  of 10 AudioSources (round-robin). `PlayAtPosition()` creates temp GameObject destroyed
  after clip length. Routes to AudioMixer groups "Music" and "SFX".

**UI:**
- `HUDController.cs` — subscribes to all PlayerStats events: hearts, health shards, deaths,
  stars, coins (Hub + Biome1/2/3). Uses TMP_Text.
- `PauseSystem.cs` — `Time.timeScale = 0/1` + cursor lock

**Editor:**
- 9 UnityEditor menu item scripts for setup automation (player model setup, animation import,
  shader application, toon shader applier, prefab placement, missing script tools)

### Player System (PlayerMotorCC.cs)

**Movement:** Camera-relative (project camera forward/right onto XZ plane).
`acceleration = 25f`, `deceleration = 30f`. `airControlMultiplier = 0.6f`. Ice reduces both by up to 90%.

**Jump physics:** `v = sqrt(2 * jumpHeight * -gravity)` (`jumpHeight = 1.6m`, `gravity = -25f/s²`).
Variable jump height: `2.2×` extra gravity when button released while ascending.
Coyote time: 0.12s. Jump buffer: 0.12s before landing.

**Wall jump:** 4-direction raycasts from height 0.9m. Coyote time 0.12s. One wall jump per
wall contact (`_wallJumpConsumedThisContact`). Priority: wall jump > coyote jump > air jump.

**Abilities:** 3 boolean flags (`doubleJumpUnlocked`, `dashUnlocked`, `wallJumpUnlocked`).
Unlock via `UnlockDoubleJump()`, `UnlockDash()`, `UnlockWallJump()`.

**Dash:** 0.18s at 16m/s, 0.6s cooldown. Direction from camera-relative input.

**Gravity flip:** smooth 360°/s X euler rotation (0→180). Inverted `IsGrounded` via
upward raycast when flipped. `DoJump` inverts velocity sign when flipped.

### Level System

8 scenes: `MainMenu`, `StartingArea`, `MainArea` (hub), `DoubleJumpUnlockArea`,
`WallJumpUnlockRoom`, `Sandbox_Playtest`, `Unfinished/BonusRoom`, `Unfinished/SampleScene`.

Scene transitions: 0.25s fade → async load → player teleport to named SpawnPoint.
`DoorPortal` triggers call `SceneTransitionManager.RequestTransition(sceneName, spawnId)`.

### Architecture Pattern

All persistent singletons (AudioManager, PlayerStats, RespawnManager, SceneTransitionManager)
use `DontDestroyOnLoad` with duplicate-destruction pattern. `Bootstrapper` lazily instantiates
them. `PlayerStats` is the single source of truth, communicating via C# `event Action<>`.
UI and health logic subscribe independently — fully decoupled.

---

## 3. person4-github-profile

**Import mode:** reference-only
**Coverage:** Single README file

Profile README: Computer Science student at University of Cyprus, Larnaca. Specializes in
computer graphics and game development (Unity, C#). Exploring LLMs and AI-driven game design.
Seeking internships 2025–2026.
