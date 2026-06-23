# Domain Synthesis: AI Model Runtimes and Chinese AI Models

**Researched:** 2026-06-23
**Sources analyzed:** 2 (glm-model-family / GLM-5, kimi-model-family / Kimi K2)
**Related lab sources:** terminal-coding-agent (kimi-code)

---

## Sources analyzed

| Source | Model | Organization | Parameters | License | Import mode |
|---|---|---|---|---|---|
| `glm-model-family` | GLM-5 | Zhipu AI / Z.ai | 744B total / 40B active | Apache 2.0 (code) + MIT (weights) | reference-only |
| `kimi-model-family` | Kimi K2 | Moonshot AI | 1T total / 32B active | Modified MIT | reference-only |

Both are Chinese-developed, open-weight, Mixture-of-Experts frontier models. Both are reference-only in this lab. Neither contains model weights. Both require pending license and security reviews before any code can be copied or executed.

---

## Why these models are in this lab

[Inference] The lab owner's reasoning is most likely:

1. **The kimi-code connection.** `sources/terminal-coding-agent/` contains `kimi-code` — Moonshot AI's open-source CLI coding agent. `kimi-code` runs on top of Kimi K2 models. Having `kimi-model-family` as a reference source is the natural companion: you need to understand the model's capabilities, context window, tool-use design, and benchmarks to make good decisions about how to build with or on top of kimi-code. These two sources are part of the same system.

2. **Comparative baseline for Chinese open-weight models.** GLM-5 and Kimi K2 represent the two most prominent non-Alibaba (Qwen) and non-DeepSeek Chinese model families. Including both provides a comparative view of the Chinese AI model landscape, which is increasingly relevant to any AI engineering lab watching the frontier.

3. **Evaluating model routing and inference infrastructure.** Both models support the same inference frameworks (vLLM, SGLang, KTransformers). Having their documentation as reference helps with designing model-agnostic inference infrastructure — i.e., infrastructure that could run any of these models depending on task, cost, or compliance constraints.

4. **Long-context benchmark reference.** The SOURCE.yaml for GLM-5 specifically notes "Reference for long-context benchmark." GLM-5's 200K–1M context window is a design target worth understanding — either to compete with, or to inform when to recommend self-hosting a model vs. using an API.

5. [Hypothesis] The lab owner may be building or evaluating a product that needs a capable coding/agentic model deployable without API dependency. Self-hosted Kimi K2 or GLM-5 on EU infrastructure would serve that need at significantly lower per-token cost than Anthropic or OpenAI.

---

## Technical capabilities comparison

### Architecture

| Dimension | GLM-5 | Kimi K2 |
|---|---|---|
| Architecture | MoE, decoder-only | MoE, decoder-only |
| Total params | 744B | 1T |
| Active params/token | ~40B (top-8 of 256) | 32B (8+1 shared of 384) |
| Attention | DeepSeek Sparse Attention | MLA (Multi-head Latent Attention) |
| Context window | 200K–1M (version-dependent) | 128K–256K (version-dependent) |
| Training tokens | 28.5T | 15.5T |
| Weights format | BF16 + FP8 | block-fp8 |

**Architecture comparison:** Both use MoE designs common to frontier Chinese models (alongside DeepSeek V3 and Qwen). The key differentiator is context length: GLM-5.2's 1M token window (achieved via IndexShare sparse attention) is significantly longer than Kimi K2's 128K–256K. Kimi K2 has more total parameters (1T vs 744B) but fewer active parameters per token (32B vs 40B), meaning slightly cheaper per-token inference per activated FLOPs.

### Capabilities

| Benchmark | GLM-5 | Kimi K2 | Notes |
|---|---|---|---|
| SWE-bench Verified | 77.8% (claimed at launch) | 65.8% | GLM-5 figures from aggregator sites; K2 from technical report |
| SWE-bench Pro | 62.1 (GLM-5.2) | 58.6 (K2.6) | More directly comparable — both from similar timeframes |
| Terminal-Bench 2.1 | 81.0 (GLM-5.2) | N/A | Only GLM-5 has Terminal-Bench data in web sources |
| LiveCodeBench | N/A directly | 53.7% (K2 original) | |
| Agentic tool-use | Strong (inferred from Terminal-Bench) | Strong (Tau2: 66.1%, ACEBench: 76.5%) | K2 has better-documented agentic benchmarks |
| Math (AIME 2025) | 82.5 IMO (claimed) | 49.5% AIME 2025 | Different benchmarks; direct comparison difficult |

**[Important caveat]:** Benchmark comparisons between GLM-5 and Kimi K2 are confounded by version proliferation. GLM-5 SWE-bench figures (77.8%) come primarily from third-party aggregator sites and are harder to verify than Kimi K2's 65.8% from its official technical report (arXiv:2507.20534). Do not treat the GLM-5 numbers as settled fact.

### Inference infrastructure

Both models support the same ecosystem:
- vLLM (both)
- SGLang (both)
- KTransformers (both)
- Hugging Face Transformers (GLM-5)
- TensorRT-LLM (Kimi K2)
- Ascend NPU: GLM-5 only (unique to Huawei hardware ecosystem)

Both offer OpenAI-compatible API interfaces (Z.ai platform for GLM-5, platform.moonshot.ai for Kimi K2).

**Minimum hardware for self-hosting:**
- A 40B active parameter model (GLM-5) at FP8 requires ~40B × 1 byte = ~40 GB VRAM just for active weights; full MoE router + all expert weights at FP8 are 744B × 1 byte = ~744 GB
- Practically: multiple H100/A100 (80 GB) nodes, or quantized deployment via KTransformers
- [Inference] Self-hosting either model at full precision requires substantial GPU infrastructure — 4–8× H100 80GB minimum. This is not a "run on a workstation" scenario.

---

## License and compliance analysis

### License summary

| Model | License type | Commercial use | Restrictions |
|---|---|---|---|
| GLM-5 (code) | Apache 2.0 | Yes, unrestricted | Attribution, license preservation |
| GLM-5 (weights, ≥5.1) | MIT | Yes, unrestricted | Standard MIT attribution only |
| Kimi K2 (code + weights) | Modified MIT | Yes, with one condition | If MAU > 100M or revenue > $20M/month: display "Kimi K2" in UI |

### Compliance tiers for EU use

**Tier 1 — Fully compliant (no legal risk):**
- Self-hosted on EU infrastructure: no data leaves the EU, no GDPR processor issues, no China data transfer concerns
- Both GLM-5 and Kimi K2 qualify under this tier

**Tier 2 — Legally uncertain (needs legal counsel):**
- API use with non-personal data (e.g., synthetic data, public code): still involves data transfer to China; no adequacy decision exists; low practical risk but technically non-compliant
- Fine-tuning using European infrastructure but with weights from Zhipu/Moonshot: no data transfer, but inherited training-data provenance questions remain

**Tier 3 — Non-compliant for personal data:**
- Hosted API use (platform.moonshot.ai, docs.z.ai) with any EU personal data: GDPR violation risk established by DeepSeek precedent (Italy Garante, EDPB task force, multiple national probes)
- Source: https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities...

### EU AI Act exposure

Both models almost certainly exceed the 10^25 FLOP training compute threshold triggering Article 55 systemic-risk obligations under the EU AI Act. As downstream deployers in the EU, a company using either model as the core of a commercial service would face:
- Transparency obligations (inform users they're interacting with AI)
- Adversarial testing requirements
- Incident reporting obligations
- Potentially: technical documentation requirements

The open-weights exemption does not apply for models with systemic risk.
Source: https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566 / https://www.europarl.europa.eu/RegData/etudes/STUD/2025/778575/ECTI_STU(2025)778575_EN.pdf

### Content behavior risk

Both GLM-5 and Kimi K2 are trained under Chinese regulatory requirements. Chinese AI regulations require models to:
- Adhere to "core socialist values"
- Not produce content undermining national unity or territorial integrity
- Comply with China's Cybersecurity Law and Data Security Law

This means the models likely exhibit hard-coded refusals or biased responses on topics politically sensitive in China. For products requiring neutral information retrieval or factual answers on topics like Taiwan, Tibet, or Chinese domestic politics, this is a functional limitation, not just a compliance concern.
Source: https://www.cigionline.org/articles/chinese-ai-models-and-the-high-stakes-fight-for-ai-neutrality/ / https://www.isaca.org/resources/news-and-trends/isaca-now-blog/2026/navigating-global-ai-governance...

---

## Market context

### The Chinese open-source AI ecosystem

Chinese open-source models now represent approximately 30% of global AI usage according to data cited by Yahoo Finance (https://finance.yahoo.com/news/chinas-open-source-models-30-093000383.html). The major families as of mid-2026:

| Family | Organization | Notable for |
|---|---|---|
| Qwen | Alibaba Cloud | Most derivatives on HuggingFace (113K+ models); broadest ecosystem |
| DeepSeek V3/R1 | DeepSeek | "DeepSeek moment" — triggered global open-source AI race; MIT license |
| Kimi K2 | Moonshot AI | Best-in-class agentic tool use; fastest-growing company ($4.3B → $20B in <1 year) |
| GLM-5 | Zhipu AI / Z.ai | Long-context specialist; Ascend NPU support; publicly listed |
| MiniMax M2/M3 | MiniMax | Strong SWE-bench scores (80.5% claimed for M3) |

The Kimi K2 open-sourcing (July 2025) was described as "Another DeepSeek moment" — a Chinese lab releasing frontier-quality weights that forced global recalibration of what open-source AI can achieve.
Source: https://huggingface.co/blog/fdaudens/moonshot-ai-kimi-k2-explained / https://www.alphamatch.ai/blog/open-source-llm-comparison-blog-2026

### Chinese models vs. Western alternatives for coding

| Model | SWE-bench Verified | License | Self-hostable | EU origin |
|---|---|---|---|---|
| Claude Opus 4.x (Anthropic) | ~80–82% | Proprietary | No | No (US) |
| Qwen3-Coder (Alibaba) | ~73–77% | Apache 2.0 | Yes | No (China) |
| Devstral 2 (Mistral) | 72.2% | Apache 2.0 | Yes | Yes (France) |
| GLM-5.2 (Zhipu) | ~62% SWE-bench Pro | MIT | Yes | No (China) |
| Kimi K2 (Moonshot) | 65.8% | Modified MIT | Yes | No (China) |
| DeepSeek V3/V4 | ~65–74% | MIT | Yes | No (China) |

For EU-focused engineering labs, **Mistral's Devstral 2** is the only frontier-class coding model with both EU origin and competitive SWE-bench performance. Chinese models are strong technically but carry compliance overhead.

### The inference cost argument

Chinese open-weight models are dramatically cheaper to run via API than Anthropic or OpenAI:
- GLM-5 API: ~95–98% cheaper than GPT-4 per token
- Kimi K2 API: competitive with DeepSeek pricing

For a lab evaluating cost-sensitive AI infrastructure, this cost differential is significant even after factoring in self-hosting GPU costs at scale.
Source: https://anotherwrapper.com/tools/llm-pricing/glm-5/gpt-4

---

## Strategic value for this lab

**High value:**

1. **Understanding the kimi-code agent.** Kimi K2 is the intelligence layer under `terminal-coding-agent`. The dossier research clarifies what that agent can actually do (65.8% SWE-bench, 200–300 sequential tool calls, 128K context) and where it has limits. This is operationally valuable.

2. **Model selection framework.** Both dossiers establish a comparative framework for evaluating model alternatives. When the lab needs to choose between Kimi K2, GLM-5, Qwen, or Devstral for a specific task, the benchmark and capability profiles are now documented.

3. **Infrastructure planning.** Knowing that both models require 4–8× H100 GPUs for full-precision self-hosting sets realistic infrastructure expectations. Quantized inference (KTransformers, FP8) is the practical path.

4. **Compliance guardrails.** The GDPR analysis establishes that API use is risky for EU products with personal data; self-hosting is the compliant path. This is a decision gate that belongs in any AI product specification.

**Moderate value:**

5. **Architecture patterns.** The MoE + sparse attention patterns in both models are academically interesting but unlikely to be reproduced at this scale. The more actionable takeaway is the agent scaffolding patterns.

6. **Benchmark methodology.** Adopting the SWE-bench Verified + Terminal-Bench + Vending Bench suite as the lab's own model evaluation framework would be a direct application of this research.

**Low value (given reference-only status):**

7. **Code reuse.** Neither repo contains reusable training code or inference implementations beyond configuration examples. The actual intellectual property stays in weights and training infrastructure that are not open-sourced.

---

## Business opportunities

### When/how to build on these vs. Anthropic APIs

**Build on Chinese open-weight models (self-hosted) when:**
- Cost is the primary constraint and you're doing high-volume inference (>$50K/month API spend threshold where self-hosting ROI kicks in)
- You need to ensure data never leaves your infrastructure (regulated industries, EU personal data)
- You are building a coding agent or agentic pipeline — Kimi K2's tool-use optimization is a genuine differentiator
- You need the longest context windows available in open weights (GLM-5.2 at 1M tokens)
- You are targeting markets where Chinese AI model relationships are commercially advantageous

**Build on Anthropic (or equivalent proprietary) APIs when:**
- Time-to-market is paramount — API integration is days vs. weeks for self-hosting
- You need the highest absolute capability (Claude Opus 4.x leads on most coding benchmarks by 5–15 points)
- You are building EU products with personal data and cannot self-host
- You need predictable, contractual SLAs and GDPR DPA agreements

**Build on Mistral (Devstral) when:**
- You want open-weight coding performance with EU regulatory provenance
- You want Apache 2.0 (no Modified MIT ambiguity)
- You are building for EU enterprise customers who have procurement requirements against Chinese technology

**The kimi-code opportunity:** The `terminal-coding-agent` source in this lab represents the highest-value application of Kimi K2 in the lab context. kimi-code is a production-grade, MIT-licensed CLI agent. Building on top of kimi-code (adding capabilities, customizing the agent scaffold, integrating with other lab systems) is a legitimate path that uses the CLI (MIT license) while treating the underlying K2 model as a replaceable backend.

---

## Risks and concerns

### Technical risks

1. **Version velocity:** Both model families release major versions every 2–4 months. The pinned commits in this lab will become outdated. Capability references in dossiers should be version-anchored.
2. **Benchmark inflation:** The proliferation of aggregator sites reporting model benchmarks creates noise. Always trace claims to primary sources (official technical reports, arXiv papers).
3. **Self-hosting complexity:** Deploying a 744B or 1T MoE model requires significant GPU infrastructure. Underestimating this leads to failed production deployments.
4. **Context window ≠ quality at context:** Both models advertise long contexts but in-context retrieval quality often degrades in the middle of very long documents ("lost in the middle" problem). Long-context benchmarks like Vending Bench are designed to test this, but results should be validated on your specific use case.

### Compliance risks

1. **GDPR / API use:** Using hosted Chinese AI APIs with EU personal data is the highest-risk action. The DeepSeek precedent makes this a real enforcement risk, not a theoretical one.
2. **AI Act systemic risk:** Products built on these models may inherit AI Act obligations as downstream deployers. This needs legal sign-off before commercial deployment.
3. **Modified MIT (Kimi K2):** Not OSI-certified. Enterprise legal teams may reject it. The commercial attribution clause triggers at thresholds most startups will never reach, but the non-standard license nature creates friction.
4. **Chinese content restrictions:** If your product requires politically neutral AI (journalism, research, public information services), hard-coded Chinese content restrictions are a functional defect, not just a compliance footnote.

### Business risks

1. **Geopolitical exposure:** EU-China technology relations are a dynamic regulatory environment. Dependence on Chinese model weights creates supply chain risk if political conditions change (similar to how some EU companies moved away from Huawei infrastructure). Self-hosting mitigates but does not eliminate this.
2. **Security review pending:** Both sources have `security_review_status: pending` in SOURCE.yaml. This is not a theoretical concern — software supply chain security requires auditing model repos and weight files for embedded vulnerabilities before production use.
3. **Data provenance opacity:** Neither Zhipu nor Moonshot has published comprehensive training data breakdowns. Copyright risk in pre-training data is unquantifiable without this information.

---

## Recommended position

**For the lab's immediate context:**

1. **Complete the pending license and security reviews** for both sources before any production use. The permissive licenses (Apache 2.0 / MIT / Modified MIT) mean the reviews are likely to clear, but they need to actually happen.

2. **Treat Kimi K2 as the primary reference model** for understanding `terminal-coding-agent` (kimi-code). The CLI agent is the lab's actual candidate for use; the model repo contextualizes it.

3. **Treat GLM-5 as a long-context benchmark reference.** Its key contribution to the lab's knowledge base is the 1M-token context window design — useful for understanding what becomes possible in that context, and for evaluating when to recommend self-hosted long-context models in product decisions.

4. **Default to self-hosted deployment for any EU production use.** This resolves the GDPR issue cleanly and transforms both models from "legally risky Chinese APIs" to "open-weight models running on EU infrastructure" — a fundamentally different compliance posture.

5. **Monitor Kimi K2.6/K2.7-Code** as the more current representatives of the Kimi model family for fresh capability assessments. The pinned K2 base commit is the right historical anchor but is ~12 months behind the frontier as of this research date.

6. **Do not use hosted API for EU personal data** until Moonshot and Zhipu establish EU-based processing agreements or receive adequacy decisions. The DeepSeek regulatory precedent makes this a live enforcement risk.

7. [Hypothesis] **Consider Mistral Devstral 2 as a compliance-first alternative** for EU customers with strict procurement requirements. Comparable coding performance, EU origin, Apache 2.0 license, no geopolitical exposure.

---

*Sources consulted:*
- https://github.com/zai-org/GLM-5
- https://github.com/MoonshotAI/Kimi-K2
- https://arxiv.org/html/2507.20534
- https://moonshotai.github.io/Kimi-K2/
- https://presenc.ai/research/zhipu-glm-model-lineage-2026
- https://en.wikipedia.org/wiki/Moonshot_AI
- https://techcrunch.com/2026/05/07/chinas-moonshot-ai-raises-2b-at-20b-valuation...
- https://www.caproasia.com/2025/12/19/china-artificial-intelligence-startup-zhipu-ai-plans-hong-kong...
- https://benchlm.ai/blog/posts/best-chinese-llm
- https://www.deeplearning.ai/the-batch/kimi-k2-6-matches-open-qwen3-6-max-anddeepseek-v4...
- https://www.turingpost.com/p/chinesemodels
- https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities...
- https://simfero.eu/knowledge-base/self-hosted-open-source-llms-for-gdpr-compliance/
- https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566
- https://www.cigionline.org/articles/chinese-ai-models-and-the-high-stakes-fight-for-ai-neutrality/
- https://news.ycombinator.com/item?id=44544358
- https://huggingface.co/blog/fdaudens/moonshot-ai-kimi-k2-explained
- https://intuitionlabs.ai/articles/chinese-open-source-llms-2025
- https://finance.yahoo.com/news/chinas-open-source-models-30-093000383.html
- https://www.alphamatch.ai/blog/open-source-llm-comparison-blog-2026
- https://anotherwrapper.com/tools/llm-pricing/glm-5/gpt-4
- https://isaca.org/resources/news-and-trends/isaca-now-blog/2026/navigating-global-ai-governance...
