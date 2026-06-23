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

---

## Related sources

- `sources/design-taste/` — design quality reference material (no code dependency)
- `sources/design-quality-and-review/` — design review patterns
- `sources/ui-ux-reference/` — UI/UX reference material

No source code for generative design or QR/barcode generation has been imported.

## Research connections

None filed. See `business-research/BUSINESS_IDEAS_INDEX.md` for context.

## Origin

Concept originated from observing barkod.studio (proprietary product). No code or
assets from that product have been copied or imported. This is a clean-room exploration
of the same problem space.

## Current evidence level

`assumption` — concept only, no source research, no user validation.

## Open assumptions

- Customers creating branded QR codes / event tickets have a real problem with existing tools
- The constraint (aesthetically pleasing AND machine-readable) is the actual differentiator
- The target customer is willing to pay over free QR code generators

## Next validation step

1. Identify which asset type (branded QR, event tickets, certificates) has the clearest
   willingness to pay
2. Find 3–5 potential users (event organizers, brand managers, marketing agencies): what
   tools do they use today and what's broken?
3. Build a one-asset POC to validate the technical constraint can be solved reliably
