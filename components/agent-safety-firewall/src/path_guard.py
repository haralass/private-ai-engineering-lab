"""
Enforces protected-paths.yaml.
Checks whether a target path is protected or requires confirmation.
"""

from __future__ import annotations

from dataclasses import dataclass
from enum import Enum
from fnmatch import fnmatch
from pathlib import Path

import yaml


class PathAction(str, Enum):
    BLOCK = "block"
    CONFIRM = "confirm"
    ALLOW = "allow"


@dataclass
class PathDecision:
    action: PathAction
    reason: str
    target: str
    matched_pattern: str | None


_DEFAULT_CONFIG = Path(__file__).parent.parent / "config" / "protected-paths.yaml"


def _load_config(config_path: Path) -> dict:
    with open(config_path) as f:
        return yaml.safe_load(f)


def check(
    target_path: str,
    config_path: Path = _DEFAULT_CONFIG,
) -> PathDecision:
    """
    Check whether a write to target_path is allowed, blocked, or requires confirmation.
    target_path should be relative to the project root.
    """
    config = _load_config(config_path)
    protected = config.get("protected", [])
    require_confirmation = config.get("require_confirmation", [])

    for pattern in protected:
        if _matches(target_path, pattern):
            return PathDecision(
                action=PathAction.BLOCK,
                reason=f"Path matches protected pattern: {pattern}",
                target=target_path,
                matched_pattern=pattern,
            )

    for pattern in require_confirmation:
        if _matches(target_path, pattern):
            return PathDecision(
                action=PathAction.CONFIRM,
                reason=f"Path requires confirmation: {pattern}",
                target=target_path,
                matched_pattern=pattern,
            )

    return PathDecision(
        action=PathAction.ALLOW,
        reason="Path not protected",
        target=target_path,
        matched_pattern=None,
    )


def _matches(path: str, pattern: str) -> bool:
    """Match path against a glob pattern including ** support."""
    path = path.lstrip("/")
    pattern = pattern.lstrip("/")

    # Direct fnmatch
    if fnmatch(path, pattern):
        return True

    # Match any path component against non-** patterns that start with **
    if pattern.startswith("**/"):
        sub = pattern[3:]
        parts = path.split("/")
        for i in range(len(parts)):
            candidate = "/".join(parts[i:])
            if fnmatch(candidate, sub):
                return True

    return False


def report(decision: PathDecision) -> str:
    if decision.action == PathAction.ALLOW:
        return f"[ALLOW] {decision.target}"
    return (
        f"[{decision.action.upper()}] {decision.target!r} — {decision.reason}"
    )
