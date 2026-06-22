"""
Composite policy engine that aggregates command and path decisions.
Produces a single final verdict for an agent action.
"""

from __future__ import annotations

import sys
from dataclasses import dataclass
from enum import Enum
from pathlib import Path
from typing import Optional

from command_analyzer import Action, PolicyDecision, Severity, analyze, report as cmd_report
from path_guard import PathAction, PathDecision, check as path_check, report as path_report


class Verdict(str, Enum):
    BLOCK = "block"
    CONFIRM = "confirm"
    WARN = "warn"
    ALLOW = "allow"


@dataclass
class AgentActionVerdict:
    verdict: Verdict
    command_decision: Optional[PolicyDecision]
    path_decision: Optional[PathDecision]
    summary: str


# report-only mode: never block, always log and return ALLOW
REPORT_ONLY = True


def evaluate(
    command: Optional[str] = None,
    target_path: Optional[str] = None,
) -> AgentActionVerdict:
    """
    Evaluate a proposed agent action (command and/or file write).
    In report-only mode (default) returns ALLOW but prints warnings.
    """
    cmd_decision = None
    path_decision = None
    worst_verdict = Verdict.ALLOW

    if command:
        cmd_decision = analyze(command)
        if cmd_decision.action == Action.BLOCK:
            worst_verdict = Verdict.BLOCK
        elif cmd_decision.action == Action.CONFIRM and worst_verdict != Verdict.BLOCK:
            worst_verdict = Verdict.CONFIRM
        elif cmd_decision.action == Action.WARN and worst_verdict not in (Verdict.BLOCK, Verdict.CONFIRM):
            worst_verdict = Verdict.WARN

    if target_path:
        path_decision = path_check(target_path)
        if path_decision.action == PathAction.BLOCK:
            worst_verdict = Verdict.BLOCK
        elif path_decision.action == PathAction.CONFIRM and worst_verdict != Verdict.BLOCK:
            worst_verdict = Verdict.CONFIRM

    parts = []
    if cmd_decision:
        parts.append(cmd_report(cmd_decision))
    if path_decision:
        parts.append(path_report(path_decision))
    summary = " | ".join(parts) if parts else "No action evaluated"

    if REPORT_ONLY and worst_verdict in (Verdict.BLOCK, Verdict.CONFIRM):
        print(f"[AGENT-SAFETY-FIREWALL] REPORT-ONLY: would {worst_verdict.upper()} — {summary}", file=sys.stderr)
        return AgentActionVerdict(
            verdict=Verdict.ALLOW,
            command_decision=cmd_decision,
            path_decision=path_decision,
            summary=f"REPORT-ONLY: {summary}",
        )

    return AgentActionVerdict(
        verdict=worst_verdict,
        command_decision=cmd_decision,
        path_decision=path_decision,
        summary=summary,
    )
