# Workflow: Open Source Contribution

Case study source: pydantic/pydantic PR#12740

## Step-by-step process

```
1. find issue
   → Look for "good first issue" or "bug" labels
   → Reproduce the bug locally before claiming the issue

2. reproduce bug
   → Write a minimal reproduction script
   → Confirm the behavior matches the issue description

3. write failing test
   → Add a test that fails with the current code
   → This becomes your success criterion

4. implement focused fix
   → Change as little as possible
   → Do not refactor surrounding code unless asked

5. run full test suite
   → Make sure no existing tests broke

6. open clear PR
   → Title: what changed (not why)
   → Body: why (the problem + the fix approach)
   → Reference the issue
   → Keep the diff small

7. respond to review
   → Answer questions directly
   → Update code as requested
   → Don't argue unless the reviewer is factually wrong

8. merge
   → Maintainer merges — do not merge your own PR in someone else's repo
```

## pydantic/pydantic case study

PR: https://github.com/pydantic/pydantic/pull/12740

This PR is used as a reference for understanding the open-source contribution process at a production-grade library. See `AUDIT.md` in this directory for analysis.
