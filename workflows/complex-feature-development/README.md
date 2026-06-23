# Workflow: Complex Feature Development

Derived from study of `sources/structured-agent-development/` (obra/superpowers).

## Steps

```
1. brainstorm
   → Generate 3+ approaches, including non-obvious ones
   → Write them down before evaluating

2. specify
   → Define success criteria (how do we know this is done?)
   → Define failure criteria (what would make this wrong?)
   → Define scope (what is explicitly out of scope?)

3. plan
   → Break into independently verifiable steps
   → Identify risks at each step

4. implement with worktrees (for large changes)
   → Use git worktree to avoid disrupting main working tree
   → One branch per feature

5. test-driven development
   → Write failing tests before implementation
   → Each test maps to a success criterion from step 2

6. verify before calling done
   → Run the feature manually, not just tests
   → Check edge cases defined in the spec

7. specification compliance review
   → Does the implementation match the spec?
   → Any scope creep?

8. code quality review
   → Readability, correctness, security
   → No unnecessary abstractions

9. PR
   → Reference the spec
   → Describe what changed and why
```
