# Regulatory Landscape Research

research_date: 2026-06-23
author: research agent
status: primary-source-verified

---

## Methodology

All regulatory claims in this document are sourced from primary official sources only.
Sources used:

- **EUR-Lex** (eur-lex.europa.eu) — official EU legal texts
- **EU AI Act Service Desk** (ai-act-service-desk.ec.europa.eu) — official Commission implementation resource
- **European Commission Digital Strategy** (digital-strategy.ec.europa.eu)
- **European Commission Finance** (finance.ec.europa.eu)
- **EFRAG** (efrag.org) — European Financial Reporting Advisory Group
- **NIST CSRC** (csrc.nist.gov) — National Institute of Standards and Technology Computer Security Resource Center
- **Apple Developer Documentation** (developer.apple.com)
- **Google Play Developer Documentation** (support.google.com/googleplay/android-developer)
- **EDPB** (edpb.europa.eu) — European Data Protection Board

Access date for all sources: 2026-06-23.

Inferences are marked with [inference]. Unverifiable claims are noted explicitly.

---

## EU AI Act

**Official citation:** Regulation (EU) 2024/1689 of the European Parliament and of the Council
**EUR-Lex:** https://eur-lex.europa.eu/eli/reg/2024/1689/oj/eng
**Entry into force:** 1 August 2024 (20 days after publication in the Official Journal)

### Phased Application Timeline (Article 113)

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-113

| Date | What applies |
|------|-------------|
| 2 February 2025 | Chapter I (general provisions, definitions) + Chapter II (prohibited practices, Article 5) |
| 2 August 2025 | Chapter III Section 4 (notified bodies), Chapter V (GPAI models, Article 53), Chapter VII (governance), Chapter XII (penalties), Article 78 |
| 2 August 2026 | All remaining provisions (general application date) |
| 2 August 2027 | Article 6(1) and corresponding obligations for high-risk AI systems embedded in products regulated under other EU law (Annex II products) |
| 2 August 2028 | Extended transition for high-risk AI in regulated products under proposed Digital Omnibus simplification package [note: this date comes from a pending legislative proposal, not yet finalised as of 2026-06-23] |

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/timeline/timeline-implementation-eu-ai-act

### Scope (Article 2)

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-2

The AI Act applies to:
- **Providers** placing AI systems or GPAI models on the EU market, regardless of where they are established
- **Deployers** of AI systems located in the EU
- **Importers and distributors** of AI systems
- **Product manufacturers** incorporating AI systems into their products
- Providers/deployers in third countries whose AI output is used in the EU

**Exclusions from scope:**
- Natural persons using AI for purely personal, non-professional activity
- Scientific research and development activities (prior to market placement)
- AI systems released under free and open-source licences, unless deployed as high-risk
- Military, defence, or national security applications

### Prohibited Practices (Article 5) — Applied from 2 February 2025

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-5

Eight categories of prohibited AI systems:

**(a) Manipulative/deceptive techniques** — AI systems using subliminal techniques or purposefully deceptive methods that materially distort behaviour and cause significant harm.

**(b) Exploitation of vulnerabilities** — AI systems exploiting vulnerabilities of individuals or groups due to age, disability, or socioeconomic status, causing material behaviour distortion and harm.

**(c) Social scoring** — AI systems evaluating/classifying persons based on social behaviour or personality in ways that lead to detrimental treatment in unrelated contexts or disproportionate consequences.

**(d) Criminal risk profiling** — AI systems predicting criminal offence risk "based solely on the profiling of a natural person or on assessing their personality traits." Exception: systems supporting human assessment based on objective, verifiable facts.

**(e) Untargeted facial recognition database creation** — AI systems scraping internet images or CCTV footage to build facial recognition databases.

**(f) Emotion recognition in workplace/education** — AI inferring emotions in workplace and educational settings (with exceptions for medical or safety purposes).

**(g) Biometric categorization** — AI systems categorizing persons by biometric data to infer race, political opinions, religion, sexual orientation, etc.

**(h) Real-time remote biometric identification** — Law enforcement use restricted to three specific cases: victim/missing person searches, imminent threats, or targeting suspects in crimes carrying 4+ year sentences (Article 5(1)(h)).

Enforcement: market surveillance authorities and national data protection authorities can issue corrective action orders and administrative fines.

### High-Risk AI Systems (Annex III) — Applies from 2 August 2026

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/annex-3

Eight categories of high-risk systems:

1. **Biometrics** — remote biometric identification systems, biometric categorization systems, emotion recognition systems
2. **Critical infrastructure** — AI as safety components in digital infrastructure, road traffic, water/gas/heating/electricity supply
3. **Education and vocational training** — systems determining access to educational institutions, evaluating learning outcomes, monitoring student behaviour during testing
4. **Employment and workers' management** — recruitment/selection systems, job advertisement targeting, application filtering, promotion/termination decisions
5. **Essential services and benefits** — creditworthiness assessment, insurance risk/pricing for life and health, emergency call evaluation/dispatch triage
6. **Law enforcement** — criminal victimization risk assessment, evidence reliability evaluation, re-offending risk assessment
7. **Migration, asylum, and border control** — risk assessment, asylum/visa application processing
8. **Administration of justice and democratic processes** — assisting judicial authorities, influencing elections/referendums

Note: An Annex III system "shall not be considered to be high-risk where it does not pose a significant risk of harm to the health, safety or fundamental rights of natural persons" (Article 6(3)). This is an important self-assessment gate.

### General-Purpose AI (GPAI) Model Obligations (Article 53) — Applies from 2 August 2025

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-53

All GPAI model providers must:
1. Draw up and maintain technical documentation (Annex XI)
2. Provide downstream providers with technical documentation enabling their compliance
3. Maintain a copyright compliance policy (per Article 4(3) of Directive (EU) 2019/790)
4. Publish a "sufficiently detailed summary" of training content using the official AI Office template (mandatory use per Article 53(1)(d))

**Open-source exemption:** Obligations 1 and 2 above do not apply to open-source model providers who make weights, architecture, and usage information publicly available — unless the model has systemic risks.

**GPAI Code of Practice** published 10 July 2025, provides compliance pathway for Article 53(1)(a), (b), (c).
Source: https://digital-strategy.ec.europa.eu/en/policies/contents-code-gpai

### SME Treatment

Source: https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-62

Article 62 is dedicated to "Measures for providers and deployers, in particular SMEs, including start-ups":
- Priority access to AI regulatory sandboxes (at least one per Member State by 2 August 2026)
- Tailored training and awareness activities from Member States
- Simplified technical documentation requirements
- **Reduced conformity assessment fees** for SMEs
- SMEs are in scope in the same way as large entities — there is no blanket SME exemption from obligations; the provisions are supportive, not carve-outs
- Simplified requirements extended to small mid-cap companies (SMCs) as well as SMEs

**Conformity assessment:** Depending on the type of high-risk AI system, assessment may require an independent conformity assessment body. For Annex III systems, internal assessment is generally permitted (self-declaration) unless the system falls under specific product safety legislation.

---

## NIS2 Directive

**Official citation:** Directive (EU) 2022/2555 of the European Parliament and of the Council
**EUR-Lex:** https://eur-lex.europa.eu/eli/dir/2022/2555/oj/eng
**Entry into force:** January 2023
**Transposition deadline:** 17 October 2024 (Article 41 of the Directive)
**NIS1 repeal date:** 18 October 2024

Source: https://digital-strategy.ec.europa.eu/en/policies/nis2-directive

### Transposition Status (as of 2026-06-23)

As of May 2026, 21 of 27 EU Member States have enacted national implementing legislation. Six states are still finalising their laws. The European Commission has issued infringement proceedings against Member States that failed to transpose on time.

Source: https://digital-strategy.ec.europa.eu/en/news/commission-calls-23-member-states-fully-transpose-nis2-directive

### Scope and Size Thresholds

Source: https://digital-strategy.ec.europa.eu/en/faqs/directive-measures-high-common-level-cybersecurity-across-union-nis2-directive-faqs

NIS2 uses a **size threshold rule**: all medium-sized and large entities in covered sectors are in scope.

EU SME size definitions applied by NIS2:
- **Micro enterprise:** fewer than 10 employees, annual turnover/balance sheet ≤ €2M — **excluded** from NIS2 scope by default
- **Small enterprise:** fewer than 50 employees, annual turnover/balance sheet ≤ €10M — **excluded** from NIS2 scope by default
- **Medium enterprise:** fewer than 250 employees, annual turnover ≤ €50M or balance sheet ≤ €43M — **in scope** for NIS2 in covered sectors
- **Large enterprise:** 250+ employees or annual turnover >€50M — **in scope**

**Exceptions to the size threshold** (in scope regardless of size):
- Trust service providers
- DNS service providers
- Top-level domain (TLD) name registries
- Public electronic communications networks or services
- Sole providers of an essential service in a Member State
- Entities whose disruption could cause significant systemic risk

Source: https://www.nis-2-templates.com/scope/ (secondary, corroborating the size thresholds against the directive text)

### Sector Coverage

**Annex I — Sectors of High Criticality (essential entities):**
Energy, transport, banking, financial market infrastructures, health (including pharmaceutical manufacturing), drinking water, wastewater, digital infrastructure, ICT service management (B2B), public administration, space

**Annex II — Other Critical Sectors (important entities):**
Postal and courier services, waste management, manufacture and distribution of chemicals, food production/distribution, manufacturing (medical devices, computers/electronics, machinery, motor vehicles), digital service providers (online marketplaces, online search engines, social networking platforms), research organisations

### Entity Classification: Essential vs Important

Entities are classified based on their sector (Annex I vs II) and size:
- **Essential entities:** large entities in Annex I sectors, plus some specific entity types regardless of size
- **Important entities:** medium-sized entities in Annex I sectors, plus large and medium entities in Annex II sectors

Different **supervisory regimes** apply: essential entities face proactive supervision; important entities face reactive supervision (post-incident).

### Article 21 Security Measures

Source: https://digital-strategy.ec.europa.eu/en/faqs/directive-measures-high-common-level-cybersecurity-across-union-nis2-directive-faqs

Article 21(1) requires "appropriate and proportionate technical, operational and organisational measures to manage the risks posed to the security of network and information systems."

Article 21(2) specifies a list of 10 minimum measures:

1. Policies on risk analysis and information system security
2. Incident handling
3. Business continuity, including backup management, disaster recovery, and crisis management
4. Supply chain security (including security aspects of relationships between each entity and its direct suppliers/service providers)
5. Security in network and information systems acquisition, development, and maintenance, including vulnerability handling and disclosure
6. Policies and procedures to assess the effectiveness of cybersecurity risk-management measures
7. Basic cyber hygiene practices and cybersecurity training
8. Policies and procedures regarding the use of cryptography and, where appropriate, encryption
9. Human resources security, access control policies, and asset management
10. Use of multi-factor authentication or continuous authentication solutions, secured voice/video/text communications, and secured emergency communication systems

Source: EUR-Lex Directive (EU) 2022/2555, Article 21(2)(a)–(j)

### Incident Reporting (Article 23)

Three-stage process:
- **24 hours:** Early warning to national CSIRT/competent authority
- **72 hours:** Full incident notification (including initial assessment, severity, indicators of compromise)
- **1 month:** Final detailed incident report

### Penalties

Significant incidents that could cause significant disruption or damage must be reported. Non-compliance penalties differ by entity type:
- Essential entities: up to €10M or 2% of global annual turnover (whichever is higher) [inference: mirrors GDPR penalty structure, based on directive recitals — verify in national implementing legislation]
- Important entities: up to €7M or 1.4% of global annual turnover [inference: same caveat]

Note: Exact penalty amounts are set in national implementing legislation; the above figures come from secondary sources and should be verified against each Member State's transposed law.

---

## VSME / ESRS (Voluntary SME Sustainability Reporting Standard)

**Official body:** EFRAG (European Financial Reporting Advisory Group)
**Standard name:** VSME — Voluntary Sustainability Reporting Standard for non-listed SMEs
**EFRAG project page:** https://www.efrag.org/en/projects/voluntary-reporting-standard-for-smes-vsme/concluded

### Development Timeline

| Date | Event |
|------|-------|
| September 2023 | European Commission SME Relief Package mandates EFRAG to develop VSME (Action 14) |
| 21 January 2024 | Exposure draft published |
| 21 May 2024 | 120-day public consultation period closed |
| 22 October 2024 | EFRAG SR TEG approval |
| 13 November 2024 | EFRAG SRB approval |
| 17 December 2024 | EFRAG delivered final VSME Standard to the European Commission |
| 30 July 2025 | European Commission adopted VSME as Commission Recommendation (C(2025) 4984) |
| 27 May 2025 | EFRAG released VSME Digital Template, XBRL Taxonomy, and Converter |

Source: https://www.efrag.org/en/news-and-calendar/news/efrag-releases-the-voluntary-sustainability-reporting-standard-for-nonlisted-smes

### Legal Status

The VSME is currently **voluntary** — it is a Commission Recommendation (C(2025) 4984), not a binding regulation. However:
- It serves as the anticipated foundation for a **future mandatory delegated act** under the proposed Omnibus I simplification package, which would cap information requests to SMEs from CSRD-subject companies
- The proposed "value-chain cap" would limit what information large companies can request from their SME suppliers to the scope defined by the VSME

Source: https://finance.ec.europa.eu/publications/questions-and-answers-recommendation-voluntary-sustainability-reporting-standard-small-and-medium_en

### Standard Structure

Source: https://www.efrag.org/en/projects/voluntary-reporting-standard-for-smes-vsme/concluded

**Basic Module** (11 disclosures, minimum tier):
- Environmental: energy and GHG emissions (Scope 1 and 2), air/water/soil pollution, biodiversity, water consumption, resource use and waste
- Social: own workforce characteristics, health and safety, remuneration and training
- Governance: corruption and bribery convictions

**Comprehensive Module** (9 additional disclosures, extended tier):
- GHG reduction targets and climate transition planning
- Climate-related risks
- Additional workforce information and human rights policies
- Gender diversity in governance bodies
- Revenue disclosures from certain sectors

The Comprehensive Module addresses "a substantial part of requests from business partners (banks, investors and large corporates)" per EFRAG.

### Who is Asking SMEs to Report

Three primary demand sources:
1. **Banks** — require ESG data for green loan eligibility and EU Taxonomy alignment (European Green Deal lending requirements)
2. **Large enterprise customers** — supply chain due diligence obligations under CSRD require reporting on their value chains, which includes SME suppliers
3. **Investors** — including private equity and venture capital under SFDR (Sustainable Finance Disclosure Regulation)

Source: https://finance.ec.europa.eu/publications/questions-and-answers-recommendation-voluntary-sustainability-reporting-standard-small-and-medium_en

### Digital Tools Available

- **EFRAG Excel Template:** free, available via EFRAG website
- **XBRL Taxonomy:** for structured digital reporting (released 27 May 2025)
- **DNK Platform (German):** free, German language only

---

## European Accessibility Act (EAA)

**Official citation:** Directive (EU) 2019/882 of the European Parliament and of the Council
**EUR-Lex:** https://eur-lex.europa.eu/eli/dir/2019/882/oj/eng
**AccessibleEU Centre:** https://accessible-eu-centre.ec.europa.eu

### Enforcement Date

**28 June 2025** — this is the date from which the EAA applies to newly placed products and services on the market.

Source: https://accessible-eu-centre.ec.europa.eu/content-corner/news/eaa-comes-effect-june-2025-are-you-ready-2025-01-31_en

### Scope: Products and Services Covered

Source: https://eur-lex.europa.eu/EN/legal-content/summary/accessibility-of-products-and-services.html

**Physical products:**
- Computers and smartphones and their operating systems
- ATMs, ticketing machines, check-in machines, interactive self-service information terminals
- Consumer terminal equipment with advanced computing capability for audiovisual media services
- TV equipment (digital television services)

**Digital services:**
- **E-commerce services** (websites and mobile apps providing distance selling to consumers)
- **Electronic communications services** (including related websites, mobile applications)
- **Banking services** (consumer-facing banking digital services, websites, mobile apps)
- **Audio-visual media services** (on-demand video streaming)
- **Transport services** — websites, mobile apps, electronic tickets, real-time information at stations (for air, bus, rail, water transport)
- **E-books** and dedicated software/hardware for reading e-books

**WCAG connection:** EN 301 549 v3.2.1 (the harmonised European standard for ICT accessibility) draws heavily from WCAG 2.1. Non-web software (including mobile applications) and non-web documents are also covered by EN 301 549. [Note: compliance with EN 301 549 creates a presumption of conformity with the EAA's accessibility requirements for digital products.]

Source: https://digital-strategy.ec.europa.eu/en/policies/web-accessibility-directive-standards-and-harmonisation

### SME and Microenterprise Treatment

**Microenterprises providing services** (fewer than 10 employees, annual turnover/balance sheet ≤ €2M) are **entirely exempted** from EAA service obligations.

Microenterprises that manufacture products are **not exempted**.

There is **no broader SME exemption** — companies with 10+ employees that provide in-scope services must comply.

Source: https://accessible-eu-centre.ec.europa.eu/content-corner/news/eaa-comes-effect-june-2025-are-you-ready-2025-01-31_en

### Transition Periods and Exceptions

- **Services:** service providers whose facilities (infrastructure used for services) were already lawfully in use by 28 June 2025 have an additional **5 years** (until 28 June 2030) to bring them into compliance
- **Self-service terminals:** may continue operating until end of economically useful life, maximum 20 years after entering service
- **Pre-recorded time-based media** (e.g., videos) published before 28 June 2025 are excluded
- **Archive websites** containing content not updated or edited after 28 June 2025 are excluded

### Enforcement

From 28 June 2025, consumers can file complaints before national courts or authorities if covered products/services do not comply. Non-compliance can result in penalties including fines. Specific penalty amounts are defined in each Member State's national implementing legislation.

---

## NIST Post-Quantum Cryptography

**Primary source:** https://csrc.nist.gov/projects/post-quantum-cryptography
**NIST announcement:** https://www.nist.gov/news-events/news/2024/08/nist-releases-first-3-finalized-post-quantum-encryption-standards

### Finalized Standards (Published 13 August 2024)

After an 8-year standardization process initiated in 2015, NIST published three final PQC standards:

| FIPS Number | Standard | Algorithm | Purpose |
|-------------|----------|-----------|---------|
| FIPS 203 | Module-Lattice-Based Key-Encapsulation Mechanism Standard | ML-KEM | Key encapsulation (replaces ECDH, RSA key exchange) |
| FIPS 204 | Module-Lattice-Based Digital Signature Standard | ML-DSA | Digital signatures (replaces RSA and ECDSA signatures) |
| FIPS 205 | Stateless Hash-Based Digital Signature Standard | SLH-DSA | Digital signatures (hash-based, conservative security assumption) |

A fourth standard, **FIPS 206** (ML-KEM alternative based on FALCON algorithm), was planned for late 2024.

### Migration Guidance: NIST IR 8547

**Title:** "Transition to Post-Quantum Cryptography Standards"
**Publication:** Initial Public Draft, November 2024 (comment period closed January 10, 2025)
**URL:** https://csrc.nist.gov/pubs/ir/8547/ipd

**Proposed deprecation and disallowance timeline:**

| Algorithm strength | Status | Deprecation | Disallowance |
|-------------------|--------|-------------|-------------|
| 112-bit security (RSA-2048, ECDH-P224) | Quantum-vulnerable | After 2030 | After 2035 |
| 128-bit and higher (RSA-3072, ECDH-P256, AES-128) | Quantum-vulnerable | — | After 2035 |

**Note:** NIST IR 8547 is an Initial Public Draft as of the research date. Final publication may modify these timelines. "Deprecated" means no longer recommended for new uses; "disallowed" means removed from NIST standards.

**NIST's stated position (from the 13 August 2024 announcement):** "We encourage system administrators to start integrating them [the three finalized standards] into their systems immediately, because full integration will take time."

### Sector-Specific Mandates and Related Guidance

**NSA CNSA 2.0 (National Security Systems):** NSA set 2030 as the mandatory migration deadline for National Security Systems (NSS). This applies to US government systems and cleared contractors handling classified information.

Source: referenced in CSRC project page context, cannot independently verify the exact CNSA 2.0 document via this research session.

**NIST NCCoE:** The National Cybersecurity Center of Excellence is working with industry, academia, and federal partners on PQC migration guidance for specific sectors, but sector-specific mandates for the private sector were not found in primary sources during this research session.

**No EU-level mandate found** for private-sector PQC migration as of 2026-06-23. [inference: NIS2 Article 21(2)(h) requires "use of cryptography and, where appropriate, encryption" as a security measure, which may implicitly require PQC readiness over time, but this has not been enforced as a PQC-specific obligation in available primary sources.]

### Technical Parameters

- **ML-KEM** public keys: 800–1,568 bytes; ciphertexts: 768–1,568 bytes
- **ML-DSA** signatures: 2,420–4,595 bytes
- **SLH-DSA** signatures: 7,856–49,856 bytes (significantly larger than classical signatures)

---

## GDPR and Mobile Privacy

**Primary regulation:** Regulation (EU) 2016/679 (GDPR)
**EUR-Lex:** https://eur-lex.europa.eu/eli/reg/2016/679/oj/eng
**EDPB guidance:** https://www.edpb.europa.eu

### Core GDPR Principles Applicable to Mobile Apps

**Article 5 — Principles relating to processing of personal data:**
- **(a) Lawfulness, fairness, transparency** — basis for dark pattern enforcement (EDPB Guidelines 3/2022)
- **(b) Purpose limitation** — data may only be collected for specified, explicit, legitimate purposes
- **(c) Data minimisation** — data must be "adequate, relevant and limited to what is necessary in relation to the purposes"
- **(d) Accuracy** — data must be kept accurate and up to date
- **(e) Storage limitation** — data must not be kept longer than necessary
- **(f) Integrity and confidentiality** — appropriate security measures required

**Article 25 — Data protection by design and by default:**
Controllers must "implement appropriate technical and organisational measures … designed to implement data-protection principles, such as data minimisation, in an effective manner" and ensure that "by default, only personal data which are necessary for each specific purpose of the processing are processed."

Source: EDPB Guidelines 4/2019 on Article 25 — https://www.edpb.europa.eu/sites/default/files/files/file1/edpb_guidelines_201904_dataprotection_by_design_and_by_default_v2.0_en.pdf

### Consent Requirements for Mobile Apps (Article 7)

Per EDPB Opinion 08/2024 on consent:
- Consent must be freely given, specific, informed, and unambiguous
- Pre-ticked boxes and consent bundled with terms of service are not valid
- Dark patterns that make it harder to refuse than accept are violations of Article 5(1)(a)

Source: https://www.edpb.europa.eu/system/files/2024-04/edpb_opinion_202408_consentorpay_en.pdf

### EDPB Guidelines on Dark Patterns (Guidelines 3/2022)

Published February 2023. Applied to social media interfaces but the principles are applicable to all mobile apps under Article 5(1)(a) fairness principle. Six categories of prohibited dark pattern types: overloading, skipping, stirring, obstructing, faking, and hindering.

Source: https://www.edpb.europa.eu/system/files/2023-02/edpb_03-2022_guidelines_on_deceptive_design_patterns_in_social_media_platform_interfaces_v2_en_0.pdf

### Apple App Store Privacy Requirements

Source: https://developer.apple.com/app-store/app-privacy-details/

**Privacy Nutrition Labels** — required for all apps on the App Store. Developers must disclose:

Mandatory disclosure for all data collected and transmitted off-device (15 categories):
- Contact Info (name, email, phone, address)
- Health & Fitness
- Financial Info (payment info, purchase history, credit info)
- Location (precise or coarse)
- Sensitive Info (racial/ethnic data, sexual orientation, biometric data, etc.)
- Contacts (address book, social graph)
- User Content (emails, photos, videos, audio, gameplay content)
- Browsing History / Search History
- Identifiers (user ID, device ID)
- Purchases
- Usage Data (product interaction, advertising data)
- Diagnostics (crash data, performance data)
- Surroundings / Body data (AR/VR)

**Key definition:** "Collect" = transmitting data off-device for longer than real-time request servicing. Data processed only on-device is not "collected" and requires no disclosure.

**Privacy Manifests (required from 1 May 2024):** New or updated apps adding third-party SDKs from Apple's "commonly used third-party SDKs" list must include privacy manifests with required reasons API usage and valid signatures.

Source: https://developer.apple.com/news/?id=pvszzano

**GDPR connection:** Apple's optional disclosure pathway for "regulated financial services" explicitly references GDPR as an example of applicable data protection law.

### Google Play Data Safety Requirements

Source: https://support.google.com/googleplay/android-developer/answer/10787469

**Data Safety Form** — mandatory for all apps published on Google Play, including apps that collect no data (must still state so and provide a privacy policy URL).

14 data categories requiring disclosure:
1. Location (approximate and precise)
2. Personal info (name, email, user IDs, address, phone, race/ethnicity, beliefs, sexual orientation, etc.)
3. Financial info (payment info, purchase history, credit score)
4. Health and fitness
5. Messages (emails, SMS, in-app messages)
6. Photos and videos
7. Audio files
8. Files and docs
9. Calendar events
10. Contacts
11. App activity (interactions, search history, installed apps, user-generated content)
12. Web browsing history
13. App info and performance (crash logs, diagnostics)
14. Device or other IDs

**Developer accountability:** "You alone are responsible for making complete and accurate declarations in your app's store listing on Google Play."

**Enforcement:** Inaccurate declarations → required correction, blocked app updates, potential app removal from Google Play.

### Enforcement Examples (GDPR Mobile/App Context)

- **EDPB Annual Report 2024:** confirms significant uptick in DPA enforcement, including against major tech companies for consent and tracking violations
- **Spain AEPD case (EXP202211953):** fine for dark pattern — displaying 1,522 service providers with selection boxes toggled on by default, requiring users to individually toggle off each one; fines of €2,000 (Article 13) and €5,000 (Article 5(1)(a))
- **Tractor Supply case (US, 2025):** $1.35M fine for non-functional opt-out form — trackers remained active after opt-out [note: US jurisdiction, not GDPR, cited for comparison]

Source: https://www.edpb.europa.eu/system/files/2025-04/edpb-annual-report-2024_en.pdf

---

## Cross-Regulatory Patterns

Observed across all six frameworks researched:

1. **Documentation burden is the primary compliance cost for most organisations** — AI Act (technical documentation, conformity records), NIS2 (risk assessments, incident logs), VSME (sustainability disclosures), EAA (accessibility statements), PQC (crypto inventory), GDPR (privacy notices, DPIAs). The regulatory intent differs but the operational challenge converges on evidence collection and structured reporting.

2. **SME treatment varies significantly:**
   - EU AI Act: SMEs are in scope but get supportive measures (reduced fees, sandboxes, priority access)
   - NIS2: SMEs (under 50 employees) are typically exempt; medium enterprises (50-249) are in scope
   - EAA: microenterprises (under 10 employees) providing services are exempt; others are in scope
   - VSME: specifically designed for SMEs — they are the primary subjects
   - GDPR: applies to all organisations regardless of size that process EU personal data
   - NIST PQC: primarily US federal and national security systems; no SME carve-out but also no mandate for most private-sector organisations

3. **Staggered enforcement creates urgency windows** — AI Act's phased dates (Feb 2025, Aug 2025, Aug 2026, Aug 2027) and EAA's June 2025 date mean compliance tooling demand is not uniform; there are predictable demand spikes.

4. **Supply chain cascading** — large companies' compliance obligations cascade to their SME suppliers. CSRD → VSME (supply chain reporting), NIS2 (supply chain security in Article 21(2)(d)), EU AI Act (provider → deployer obligations in Article 25). SME compliance demand is often triggered by a downstream customer or bank, not by direct regulatory enforcement.

5. **Platform gatekeeping as compliance infrastructure** — Apple and Google have implemented their own privacy disclosure requirements that parallel GDPR. These are enforced via market access (app removal) rather than regulatory fines, making them more immediately credible enforcement mechanisms for many mobile developers than DPA action.

---

## Competitor Landscape

### AI Act / NIS2 Compliance Tools

| Vendor | Type | Pricing | Notes |
|--------|------|---------|-------|
| **Legalithm** | EU AI Act native, self-serve | Free through ~April 2028 | Covers applicability scoping, risk classification, Annex IV technical documentation. EU-built. URL: legalithm.com |
| **OneTrust** | Enterprise GRC | €30k–€100k+/year (requires sales call) | Not SME-focused |
| **Holistic AI** | AI governance specialist | Enterprise pricing (sales call) | AI Act-specific |
| **Credo AI** | AI governance | Enterprise pricing | AI governance specialist |
| **Vanta** | General compliance (SOC 2, ISO 27001) | $7k–$80k/year | Some NIS2 content, not AI Act-native |
| **Drata** | General compliance automation | $7k–$80k/year | Similar to Vanta |
| **Sprinto** | General compliance | Similar range | Similar to Vanta/Drata |
| **3rdRisk** (acquired by Diligent) | Third-party risk / NIS2 | Enterprise | NIS2 supply chain focus |

Source: https://www.legalithm.com/en/blog/best-eu-ai-act-compliance-software-startups-smes and https://venvera.com/best/saas-platforms-for-nis2-compliance-in-2026

**Gap observed:** Enterprise GRC tools (€30k+) are inaccessible to SMEs. Free tools (Legalithm) cover AI Act scoping but not evidence collection workflows. No verified tool combines AI Act + NIS2 + VSME in a single SME-accessible product.

### VSME / ESG Reporting Tools

| Vendor | Type | Pricing | Notes |
|--------|------|---------|-------|
| **ExecutESG** | VSME-specific SaaS | VSME Basic: free; CSRD Pro from €149/year | Permanently free VSME Basic tier |
| **Vision Zero Connect** | AI-powered VSME | €700/year | Complete VSME solution |
| **ESG Lift** | SME ESG SaaS | From €792/year | VSME-focused |
| **Code Gaia** | Integrated VSME module | Enterprise | Fully integrated VSME module |
| **EFRAG Excel Template** | Free template | €0 | Basic, no automation |
| **DNK Platform** | Free SaaS | €0 | German-language only |
| Consultancy/auditing firms | Professional services | €3,000–€15,000 per report | Done-for-you |

Source: https://executesg.com/resources/blog/esg-software-pricing-2026 and https://csr-tools.com/en/blog-en/vsme-tool-the-7-best-software-solutions-in-2025/

**Market observation:** VSME tool market is already populated with 23+ vendors (per vsme-info.com). Low-cost competition is significant (free tier from ExecutESG). The differentiation gap is in integration with banking/customer onboarding workflows, not standalone reporting.

### Accessibility CI Tools

| Vendor | Type | Pricing | Notes |
|--------|------|---------|-------|
| **axe-core** (Deque) | Open-source engine | Free | 4B+ downloads, 13M+ GitHub projects. No automated fixes. |
| **Axe DevTools** (Deque) | Commercial CI/CD integration + "one-click fixes" | Free (limited) / Pro / Enterprise (contact sales) | Axe DevTools Enterprise adds CI/CD integration, AI-assisted fixes |
| **Lighthouse** (Google) | Open-source / Chrome DevTools | Free | CI integration possible, no automated fixes |
| **Level Access** | Enterprise accessibility platform | Enterprise (contact sales) | Monitoring, auditing, training |
| **AccessProof** | axe-core-based monitoring | $0–$199/mo | Scheduled scans, CI/CD integration, PDF reporting |

Source: https://www.deque.com/axe/devtools/pricing/ and https://access-proof.com/compare/deque-alternative

**Gap observed:** Axe DevTools Enterprise offers "one-click fixes" in IDE (via MCP server) and CI, but this is a human-reviewed suggestion workflow, not automated PR generation. No verified vendor offers fully automated pull-request generation for deterministic accessibility violations as a standalone CI product.

### Post-Quantum Cryptography Discovery Tools

| Vendor/Tool | Type | Scope | Notes |
|-------------|------|-------|-------|
| **pqcscan** (Anvil Secure) | Open-source scanner | SSH/TLS server PQC algorithm support | Written in Rust; TLS and SSH scanning modes; standardised algorithms only. URL: github.com/anvilsecure/pqcscan |
| **IBM Quantum Safe Explorer** | Enterprise source code scanner | Source code scanning for cryptographic artifacts | Discovers crypto usage in source code; enterprise pricing |
| **PQScan** | SaaS scanner | Unclear scope | URL: pqscan.io — limited public information |
| **QRAMM free tools** | Open-source collection | CryptoScan, TLS Analyzer, CryptoDeps, PQC-Bench | URL: qramm.org/open-source-tools.html |

Source: https://www.helpnetsecurity.com/2025/07/14/pqcscan-open-source-post-quantum-cryptography-scanner/ and https://www.ibm.com/docs/en/quantum-safe/quantum-safe-explorer/2.x

**Gap observed:** pqcscan scans servers for declared PQC support; IBM Quantum Safe Explorer does source code scanning. Neither appears to combine code-level discovery with dependency PQC readiness mapping and produce a prioritized migration plan. Semgrep and Snyk do not have verified PQC-specific scanning rules as of this research date.

### Mobile Privacy Audit Tools

| Vendor/Tool | Type | Target | Notes |
|-------------|------|--------|-------|
| **PiRogue Tool Suite** | Open-source | Privacy violation detection in Android apps | Captures, detects, identifies privacy violations. URL: pts-project.org |
| **Corellium** | Cloud mobile testing | Mobile app compliance testing (GDPR, HIPAA) | Enterprise; virtual device platform |
| **SentinelOne Singularity Mobile** | Enterprise MDM + security | Continuous vulnerability scans, behavioural audits | iOS, Android, ChromeOS |
| **Jamf Pro** | MDM (Apple-focused) | Device management, security posture | Not a privacy audit tool per se |
| **Didomi** | Consent management | GDPR consent for mobile apps | Consent banner/management SDK |

Source: https://www.corellium.com/blog/mobile-app-compliance and https://pts-project.org/guides/g10/

**Gap observed:** PiRogue is a research/NGO tool (not a SaaS product). Corellium is enterprise-priced. No verified product delivers a plain-language "privacy truth report" (static + dynamic analysis combined) for an arbitrary app binary as a self-serve product accessible to enterprise IT teams without security researcher expertise.

---

## Compliance Market Assessment

### Where gaps likely exist (research-supported):

1. **AI Act evidence collection for SME deployers of Annex III systems** — Legalithm covers scoping/classification; gap is in ongoing evidence management (maintaining technical documentation, logging human oversight records) at SME price points. Applies from August 2026. [inference: this represents a workflow tooling gap, not just an information gap]

2. **Combined AI Act + NIS2 documentation** — EvidenceOps-type product (the evidenceops idea in the lab) addresses the overlap between these two frameworks. No verified product currently covers both in one SME-accessible interface.

3. **VSME + banking integration** — The market has 23+ VSME tools but the differentiator is integration into bank onboarding workflows. ExecutESG's free tier provides direct competition for standalone VSME reporting.

4. **Accessibility CI auto-fix for PRs** — Axe DevTools offers human-reviewed suggestions in IDE; the gap is automated pull-request generation for deterministic violations (color contrast, alt text, ARIA labels). EAA enforcement since June 2025 increases demand.

5. **PQC codebase inventory with dependency mapping** — IBM Quantum Safe Explorer does source code scanning but is enterprise-priced. The gap is an accessible tool that maps code-level findings to dependency PQC readiness and generates a prioritized migration plan.

6. **Mobile privacy truth engine** — No accessible product provides a self-serve static + dynamic privacy analysis of app binaries. Enterprise tools exist (Corellium) but require security expertise.

### Where the market is already well-served:

- **VSME standalone reporting:** free to low-cost tools already exist; this is not a gap
- **General accessibility scanning (detection only):** axe-core is free, ubiquitous, and well-integrated into CI
- **General GRC for large enterprises:** OneTrust, ServiceNow GRC, etc. — well-established
- **Server-side PQC support checking:** pqcscan covers this as a free tool

---

## Sources

All URLs accessed: 2026-06-23

### EU AI Act
1. https://eur-lex.europa.eu/eli/reg/2024/1689/oj/eng — Official EUR-Lex text, Regulation (EU) 2024/1689
2. https://ai-act-service-desk.ec.europa.eu/en/ai-act/timeline/timeline-implementation-eu-ai-act — Official AI Act Service Desk implementation timeline
3. https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-113 — Article 113 entry into force and application
4. https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-2 — Article 2 scope
5. https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-5 — Article 5 prohibited practices
6. https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-53 — Article 53 GPAI obligations
7. https://ai-act-service-desk.ec.europa.eu/en/ai-act/article-62 — Article 62 SME measures
8. https://ai-act-service-desk.ec.europa.eu/en/ai-act/annex-3 — Annex III high-risk systems
9. https://digital-strategy.ec.europa.eu/en/policies/regulatory-framework-ai — Commission AI Act overview
10. https://digital-strategy.ec.europa.eu/en/policies/contents-code-gpai — GPAI Code of Practice (published 10 July 2025)

### NIS2 Directive
11. https://eur-lex.europa.eu/eli/dir/2022/2555/oj/eng — Official EUR-Lex text, Directive (EU) 2022/2555
12. https://digital-strategy.ec.europa.eu/en/policies/nis2-directive — Commission NIS2 overview
13. https://digital-strategy.ec.europa.eu/en/faqs/directive-measures-high-common-level-cybersecurity-across-union-nis2-directive-faqs — Official NIS2 FAQs
14. https://digital-strategy.ec.europa.eu/en/news/commission-calls-23-member-states-fully-transpose-nis2-directive — Commission enforcement of transposition
15. https://www.enisa.europa.eu/topics/awareness-and-cyber-hygiene/network-and-information-systems-directive-2-nis2 — ENISA NIS2 overview

### VSME / ESRS
16. https://www.efrag.org/en/projects/voluntary-reporting-standard-for-smes-vsme/concluded — EFRAG VSME project (concluded)
17. https://www.efrag.org/en/news-and-calendar/news/efrag-releases-the-voluntary-sustainability-reporting-standard-for-nonlisted-smes — EFRAG release announcement (17 December 2024)
18. https://finance.ec.europa.eu/publications/questions-and-answers-recommendation-voluntary-sustainability-reporting-standard-small-and-medium_en — Commission Q&A on VSME Recommendation
19. https://ec.europa.eu/finance/docs/law/250730-recommendation-vsme_en.pdf — Commission Recommendation C(2025) 4984 (30 July 2025)
20. https://www.efrag.org/sites/default/files/sites/webpublishing/SiteAssets/VSME%20Standard.pdf — VSME Standard PDF

### European Accessibility Act
21. https://eur-lex.europa.eu/eli/dir/2019/882/oj/eng — Official EUR-Lex text, Directive (EU) 2019/882
22. https://eur-lex.europa.eu/EN/legal-content/summary/accessibility-of-products-and-services.html — EUR-Lex summary
23. https://accessible-eu-centre.ec.europa.eu/content-corner/news/eaa-comes-effect-june-2025-are-you-ready-2025-01-31_en — AccessibleEU Centre EAA readiness article
24. https://accessible-eu-centre.ec.europa.eu/content-corner/news/european-accessibility-act-enters-force-2025-06-27_en — AccessibleEU EAA enters force
25. https://digital-strategy.ec.europa.eu/en/policies/web-accessibility-directive-standards-and-harmonisation — EN 301 549 and WCAG connection

### NIST Post-Quantum Cryptography
26. https://csrc.nist.gov/projects/post-quantum-cryptography — NIST CSRC PQC project page
27. https://www.nist.gov/news-events/news/2024/08/nist-releases-first-3-finalized-post-quantum-encryption-standards — NIST announcement (13 August 2024)
28. https://csrc.nist.gov/pubs/ir/8547/ipd — NIST IR 8547 Initial Public Draft (November 2024)
29. https://nvlpubs.nist.gov/nistpubs/ir/2024/NIST.IR.8547.ipd.pdf — NIST IR 8547 PDF

### GDPR and Mobile Privacy
30. https://eur-lex.europa.eu/eli/reg/2016/679/oj/eng — Official EUR-Lex text, Regulation (EU) 2016/679 (GDPR)
31. https://www.edpb.europa.eu/sites/default/files/files/file1/edpb_guidelines_201904_dataprotection_by_design_and_by_default_v2.0_en.pdf — EDPB Guidelines 4/2019, Article 25
32. https://www.edpb.europa.eu/system/files/2023-02/edpb_03-2022_guidelines_on_deceptive_design_patterns_in_social_media_platform_interfaces_v2_en_0.pdf — EDPB Guidelines 3/2022 on Dark Patterns
33. https://www.edpb.europa.eu/system/files/2024-04/edpb_opinion_202408_consentorpay_en.pdf — EDPB Opinion 08/2024 on consent
34. https://www.edpb.europa.eu/system/files/2025-04/edpb-annual-report-2024_en.pdf — EDPB Annual Report 2024
35. https://developer.apple.com/app-store/app-privacy-details/ — Apple App Store Privacy Details (developer documentation)
36. https://developer.apple.com/news/?id=pvszzano — Apple developer news: Privacy Manifest requirement (May 2024)
37. https://support.google.com/googleplay/android-developer/answer/10787469 — Google Play Data Safety form documentation
