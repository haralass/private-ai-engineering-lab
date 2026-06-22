# Functional Generative Design

Status: `research`
Inspiration: barkod.studio (proprietary — no code copied)

## Summary

A tool that generates visually refined, branded assets that remain technically valid and machine-readable. The key insight: most generative design tools produce beautiful but non-functional outputs. This platform generates assets where aesthetics and machine-readability are both guaranteed.

## Target asset types

- Branded QR codes (custom shapes, embedded logos, color schemes — all scannable)
- Event tickets (design-forward, tamper-evident, barcode-valid)
- Loyalty cards and membership passes
- Certificates and diplomas (printable, verifiable)
- Product packaging with embedded machine-readable elements
- Branded barcodes (EAN-13, Code-128 with visual customization within spec)

## Core challenge

Generative AI can produce visually appealing designs but cannot natively guarantee that:
- A QR code still scans after visual modification
- A barcode passes checksum validation
- A certificate's layout satisfies print-safe margins

The platform must enforce technical constraints as hard constraints, not aesthetic suggestions.

## Technical approach

1. Generate visual design (AI or template-based)
2. Validate machine-readable elements programmatically
3. Iterate only within the valid design space
4. Output: both print-ready and digital formats

## Status

Concept only. No code. No proprietary assets copied. Clean-room exploration.
