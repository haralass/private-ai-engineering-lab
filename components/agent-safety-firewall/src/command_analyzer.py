"""
Analyzes shell commands against dangerous-commands.yaml.
Returns a PolicyDecision with action and reason.
"""

from __future__ import annotations

import re
from dataclasses import dataclass
from enum import Enum
from pathlib import Path
from typing import Optional

import yaml


class Action(str, Enum):
    BLOCK = "block"
    CONFIRM = "confirm"
    WARN = "warn"
    LOG = "log"
    ALLOW = "allow"


class Severity(str, Enum):
    CRITICAL = "critical"
    HIGH = "high"
    MEDIUM = "medium"
    LOW = "low"


@dataclass
class PolicyDecision:
    action: Action
    severity: Optional[Severity]
    pattern: Optional[str]
    reason: str
    command: str


_DEFAULT_CONFIG = Path(__file__).parent.parent / "config" / "dangerous-commands.yaml"
_DEFAULT_ALLOW = Path(__file__).parent.parent / "config" / "allowed-actions.yaml"


def _load_patterns(config_path: Path) -> list[dict]:
    with open(config_path) as f:
        return yaml.safe_load(f).get("patterns", [])


def _load_allowed(allow_path: Path) -> list[dict]:
    with open(allow_path) as f:
        return yaml.safe_load(f).get("allowed", []) or []


def analyze(
    command: str,
    config_path: Path = _DEFAULT_CONFIG,
    allow_path: Path = _DEFAULT_ALLOW,
) -> PolicyDecision:
    """
    Analyze a shell command against the policy.
    Returns a PolicyDecision. BLOCK and CONFIRM require human action.
    """
    patterns = _load_patterns(config_path)
    allowed = _load_allowed(allow_path)

    # Check allowed list first — explicit bypasses win
    for entry in allowed:
        if re.search(re.escape(entry["command"]).replace(r"\*", ".*"), command):
            return PolicyDecision(
                action=Action.ALLOW,
                severity=None,
                pattern=entry["command"],
                reason=entry.get("reason", "Explicitly allowed"),
                command=command,
            )

    # Check dangerous patterns
    for entry in patterns:
        if entry.get("regex"):
            matched = re.search(entry["pattern"], command, re.IGNORECASE)
        else:
            matched = re.search(re.escape(entry["pattern"]).replace(r"\*", ".*"), command, re.IGNORECASE)
        if matched:
            return PolicyDecision(
                action=Action(entry["action"]),
                severity=Severity(entry["severity"]),
                pattern=entry["pattern"],
                reason=entry.get("reason", ""),
                command=command,
            )

    return PolicyDecision(
        action=Action.ALLOW,
        severity=None,
        pattern=None,
        reason="No dangerous pattern matched",
        command=command,
    )


def report(decision: PolicyDecision) -> str:
    """Format a human-readable report line."""
    if decision.action == Action.ALLOW:
        return f"[ALLOW] {decision.command}"
    return (
        f"[{decision.action.upper()}] ({decision.severity}) "
        f"{decision.command!r} — {decision.reason}"
    )
