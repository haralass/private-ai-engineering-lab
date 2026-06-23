# White Space: Agent Specification Firewall (Pre-flight Ambiguity Checker)

status: research
research_date: 2026-06-23
evidence_level: initial-research

---

## Problem

**41.77% of multi-agent orchestration failures originate in specification ambiguity** — the agent's
system prompt or inter-agent handoff instructions are underspecified, contradictory, or missing
edge-case handling before the agent ever runs.

The MAST (Multi-Agent System Taxonomy) failure taxonomy, published in early 2026, identifies
14 failure modes across 3 root causes. Specification failures are the single largest root cause,
producing the most damaging downstream effects:

| Failure mode | Frequency |
|---|---|
| Agent repeats previously executed steps without progress | 15.7% |
| Fails to recognize task completion | 12.4% |
| Ignores explicit instructions in system prompt | 11.8% |
| Contradictory instructions across agents in pipeline | 9.3% |

These failures are **detectable pre-deployment** — but no tool validates agent specifications
before they run in production.

Source: augmentcode.com ("Why Multi-Agent LLM Systems Fail and How to Fix Them"), galileo.ai ("7 AI Agent Failure Modes and How to Prevent Them"), cogentinfo.com ("When AI Agents Collide"), verified 2026-06-23.

---

## The gap in existing tools

The closest analogy is SAST (Static Application Security Testing) for code — which lints
code before it runs and catches issues pre-execution. No equivalent exists for agent specifications.

| Tool | Approach | Gap |
|---|---|---|
| LangSmith | Traces running agents | Post-execution; reactive, not pre-flight |
| AgentEvals / Braintrust | Runs evaluation harnesses | Requires the agent to run first; not static analysis |
| Prompt optimization tools (DSPY) | Optimizes prompts for performance | Optimization, not validation; doesn't check for ambiguity or contradiction |
| Manual code review | Human reads system prompts | Doesn't scale; humans miss non-obvious edge cases; no structured taxonomy |
| Microsoft Agent Governance Toolkit | Policy enforcement (YAML/OPA/Cedar) | Runtime enforcement; not pre-deployment specification validation |

**Gap confirmed**: No tool performs static analysis on agent specifications before deployment — checking for ambiguity, internal contradiction, missing termination conditions, and scope creep risks.

---

## What "specification validation" means concretely

An agent specification consists of:
1. **System prompt**: The agent's identity, capabilities, constraints, and decision rules
2. **Tool definitions**: What tools the agent can call and under what conditions
3. **Inter-agent handoff contracts**: What the agent expects to receive from upstream agents, and what it guarantees to deliver downstream
4. **Termination conditions**: When should the agent stop? (Often absent → failure mode #2 above)

A **specification firewall** would check:
- **Ambiguity detection**: "Handle edge cases appropriately" is ambiguous; "If the user provides an invalid date, return an error with code 400" is not
- **Contradiction detection**: System prompt says "always confirm before deleting" but tool definition allows a delete tool with no confirmation parameter
- **Termination condition checker**: Does the specification define an explicit stopping condition? (Missing = infinite loop risk)
- **Scope creep risk**: Does the agent's tool set exceed what its stated task requires? (A "draft an email" agent with a "send email" tool = scope mismatch)
- **Inter-agent contract compatibility**: Does Agent B's expected input format match Agent A's actual output format?
- **Permission consistency**: Does the agent request permissions consistent with its stated task scope?

---

## Target customer

**Primary**: AI engineering teams at enterprises deploying multi-agent systems to production.
The team that writes, reviews, and merges agent specifications.

**Secondary**: Agent platform vendors (LangChain, CrewAI, AutoGen ecosystem) who want to
reduce their support burden from specification-related failures.

**Trigger event**: A multi-agent system failure in production where root cause analysis reveals
the problem was in the system prompt all along. "If only we'd caught this before deployment."

---

## Proposed product

A **static analysis tool for agent specifications** — "linting for agents":

1. **Specification ingestion**: Accepts agent system prompts, tool definitions, and inter-agent handoff contracts as input (JSON, YAML, or plain text)
2. **Ambiguity scanner**: Uses an LLM to identify instructions that are underspecified — produces specific rewrites ("replace 'handle gracefully' with 'return {error: true, code: X}'")
3. **Contradiction detector**: Checks for logical contradictions between different parts of the specification (system prompt vs. tool definition vs. handoff contract)
4. **Termination condition validator**: Flags specifications that lack explicit termination conditions — suggests specific stopping criteria based on task type
5. **Scope creep analyzer**: Compares the agent's stated task against its available tools — flags where tool permissions exceed stated scope
6. **Inter-agent compatibility checker**: Given two agent specifications connected in a pipeline, validates that the upstream agent's output format matches the downstream agent's expected input
7. **CI/CD integration**: GitHub Action, GitLab CI step — runs the specification firewall on every PR that modifies an agent spec file

**Output**: A structured report with severity-tagged findings (ERROR / WARNING / INFO), specific locations in the specification, and suggested rewrites.

---

## Why now

- **MAST taxonomy published 2026**: The academic framework that quantifies the 41.77% failure rate from specification ambiguity gives the product a concrete scientific foundation
- **Multi-agent systems are now in production**: Until 2025, multi-agent pipelines were mostly demos. In 2026, they are running production workflows — the blast radius of a spec failure is real
- **The analogy to SAST is obvious**: Every developer team understands the value of linting code before running it; the pitch transfers directly
- **No tool exists yet**: MAST was published in early 2026 and has not been productized by any vendor
- **Low technical barrier**: This is fundamentally an LLM + structured output problem — buildable as a focused product

---

## Competitor landscape (verified 2026-06-23)

No dedicated product found for pre-deployment agent specification validation.

Adjacent tools that do not solve this:
- **Prompt optimization tools (DSPY, OPRO)**: Optimize for performance, not validate for completeness/consistency
- **LLM linting tools** (general): Flag prompt injection risks, not specification logic errors
- **Agent testing frameworks**: Run agents against test cases (reactive, not static)

**First-mover gap confirmed.**

---

## Founder fit

- CS background: LLM-powered static analysis is a well-scoped engineering problem; the lab already has agent safety infrastructure
- Experience writing and debugging agent specifications (Claude Code usage)
- EU-based: the product is geography-agnostic (developer tool)
- B2B; engineering team buyer; pure software
- Natural distribution: open-source core (drive developer adoption) + paid enterprise (CI/CD integration, team-wide reporting, audit log)

---

## Revenue model

- **Freemium**: Free CLI tool (open-source core); drives adoption through developer distribution
- **SaaS**: $200–$1,000/month per team (10 team members, unlimited spec validations, CI/CD integration)
- **Enterprise**: $5,000–$20,000/year with audit logging, SSO, and custom rule definition

---

## Technical approach

1. Build a structured representation format for agent specifications (JSON schema with: system_prompt, tools[], handoff_in, handoff_out, termination_conditions)
2. Build the validation rules as prompts to a reasoning model (chain-of-thought for ambiguity detection)
3. CLI tool: `agent-lint spec.json` — outputs structured report
4. GitHub Action: triggered on `.agent/` directory changes
5. Open-source on GitHub under MIT — drive adoption, monetize enterprise features

**Lab connection**: The fail-closed hook engine (`sources/deterministic-agent-safety/`) already validates agent behavior at runtime. This extends that concept to pre-runtime specification validation — a complementary layer.

---

## Risks

- LangChain / CrewAI / Microsoft could add built-in specification validation to their frameworks
- The problem "feels" like a free developer tool problem — hard to monetize beyond open-source
- False positives (flagging valid specifications as ambiguous) reduce trust in the tool
- The MAST taxonomy may not generalize across all multi-agent frameworks

---

## Next validation step

1. Take 20 published open-source agent system prompts from GitHub → manually classify them using the MAST failure taxonomy → identify which ones have detectable specification issues
2. Build a prototype: LLM (Claude Sonnet) + structured prompt that takes a system prompt and outputs ambiguity warnings. Run on the 20 examples. Validate against the manual classification.
3. Publish the prototype on GitHub as `agent-lint` — measure GitHub stars as a leading indicator of developer demand
4. Post the MAST failure rate finding on Hacker News with the tool link — "41.77% of multi-agent failures come from specification errors. We built a linter to catch them before production."

---

## Lab sources relevant

- `sources/deterministic-agent-safety/` — fail-closed hook engine; runtime analog to what this does pre-runtime
- `components/agent-safety-firewall/` — existing safety layer; specification firewall is the upstream complement
- `research/REUSABLE_PATTERNS_CATALOG.md` — Pattern 1 (fail-closed hook engine) concept extends to pre-deployment
- `research/domain-synthesis/agent-engineering-and-safety.md` — agent safety synthesis

---

## Related ideas

- `agent-permission-firewall.md` — runtime enforcement of what agents can do; this is pre-deployment validation of what they are specified to do
- `agent-liability-attribution-engine.md` — post-incident attribution; this prevents the incidents upstream
