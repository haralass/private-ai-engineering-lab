"""Tests for path_guard.py"""

import sys
from pathlib import Path

import pytest

sys.path.insert(0, str(Path(__file__).parent.parent / "src"))
from path_guard import PathAction, check


class TestProtectedPaths:
    def test_env_file_is_blocked(self):
        d = check(".env")
        assert d.action == PathAction.BLOCK

    def test_nested_env_file_is_blocked(self):
        d = check("myapp/.env")
        assert d.action == PathAction.BLOCK

    def test_env_local_is_blocked(self):
        d = check(".env.local")
        assert d.action == PathAction.BLOCK

    def test_pem_file_is_blocked(self):
        d = check("certs/server.pem")
        assert d.action == PathAction.BLOCK

    def test_private_key_is_blocked(self):
        d = check("id_rsa")
        assert d.action == PathAction.BLOCK

    def test_upstream_source_is_blocked(self):
        d = check("sources/deterministic-agent-safety/upstream/main.py")
        assert d.action == PathAction.BLOCK

    def test_normal_python_file_is_allowed(self):
        d = check("src/main.py")
        assert d.action == PathAction.ALLOW

    def test_normal_test_file_is_allowed(self):
        d = check("tests/test_something.py")
        assert d.action == PathAction.ALLOW


class TestConfirmationPaths:
    def test_claude_md_requires_confirm(self):
        d = check("CLAUDE.md")
        assert d.action == PathAction.CONFIRM

    def test_claude_dir_requires_confirm(self):
        d = check(".claude/settings.json")
        assert d.action == PathAction.CONFIRM

    def test_settings_json_requires_confirm(self):
        d = check("settings.json")
        assert d.action == PathAction.CONFIRM
