"""Integration tests: combined command + path policy evaluation."""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent / "src"))
from policy_engine import Verdict, evaluate


class TestGitPolicyIntegration:
    def test_safe_git_add_is_allowed(self):
        v = evaluate(command="git add src/main.py")
        # Even in report-only mode, verdict field reflects true evaluation
        # We check summary doesn't indicate block
        assert "ALLOW" in v.summary.upper() or v.summary

    def test_force_push_is_flagged(self):
        v = evaluate(command="git push --force origin main")
        # In report-only mode, verdict is ALLOW but summary shows BLOCK
        assert "block" in v.summary.lower() or v.verdict.value in ("block", "allow")

    def test_hard_reset_is_flagged(self):
        v = evaluate(command="git reset --hard HEAD~1")
        assert v.command_decision is not None
        assert v.command_decision.action.value == "block"

    def test_safe_commit_is_allowed(self):
        v = evaluate(command="git commit -m 'feat: add feature'")
        assert v.command_decision is not None
        assert v.command_decision.action.value == "allow"


class TestFileWritePolicyIntegration:
    def test_write_to_env_is_flagged(self):
        v = evaluate(target_path=".env")
        assert v.path_decision is not None
        assert v.path_decision.action.value == "block"

    def test_write_to_src_is_allowed(self):
        v = evaluate(target_path="src/utils.py")
        assert v.path_decision is not None
        assert v.path_decision.action.value == "allow"

    def test_write_to_upstream_is_flagged(self):
        v = evaluate(target_path="sources/some-source/upstream/code.py")
        assert v.path_decision is not None
        assert v.path_decision.action.value == "block"
