# Contributing

This is a private single-owner laboratory. The contribution process below documents the workflow for the owner and authorized AI assistant.

## Branch naming

```
research/<topic>    — initial analysis and knowledge base work
feature/<name>      — component and workflow development
experiment/<name>   — isolated experiments
```

## Commit messages

Use small, focused commits with conventional prefixes:

```
chore:   infrastructure, tooling, repo setup
docs:    documentation changes
feat:    new component, workflow, or product concept
vendor:  new upstream source snapshot
test:    new or updated tests
ci:      CI configuration changes
fix:     bug fixes in components or scripts
refactor: restructuring without behavior change
```

## Adding a source

See `docs/SOURCE_POLICY.md` for the full import policy.

Never commit to `main` directly. Always open a PR and let CI pass.

## Modifying upstream snapshots

Never modify files inside `sources/<name>/upstream/`. All changes go into `sources/<name>/adapted/` or the relevant `components/` directory.

## Tests

All components in `components/` must have tests in `tests/component-tests/<component-name>/`. CI runs these on every PR.
