# Experiment: Kimi Code terminal agent runtime

Source: `sources/terminal-coding-agent/` (MoonshotAI/kimi-code)
Status: research — not yet active

## Safe configuration for this experiment

When activating this experiment, apply these settings:

```
manual permissions          (no automatic action approval)
plan mode enabled           (show plan before executing)
telemetry disabled          (no data sent to external services)
skills auto-merge disabled  (review skills before installing)
no YOLO mode                (no autonomous unreviewed execution)
no automatic push           (all git pushes require human confirmation)
no automatic deployment     (no auto-deploy hooks enabled)
limited background tasks    (max 2 concurrent background jobs)
```

## What we study

- How Kimi Code's hook architecture compares to Claude Code's
- Permission model and confirmation UI
- Plugin/skill integration approach
- Session and context management
- Telemetry controls

## How to activate

1. Complete security audit in `sources/terminal-coding-agent/AUDIT.md`
2. Set up isolated project directory (not this lab)
3. Configure with safe settings above
4. Test with low-risk tasks before expanding scope

Do not install Kimi Code globally until the audit is complete.
