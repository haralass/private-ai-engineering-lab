# Source Research Dossier: GLM-5 (Zhipu AI / Z.ai)

**Source path:** `sources/glm-model-family/`
**Researched:** 2026-06-23

---

## Repository identity

| Field | Value |
|---|---|
| Display name | GLM-5 |
| Functional name | glm-model-family |
| GitHub URL | https://github.com/zai-org/GLM-5 |
| Pinned commit | `bd7fafe70ba9ac7cbfe070573f8acf76e5944d5d` |
| Retrieved | 2026-06-22 |
| License (repo code) | Apache 2.0 (confirmed in LICENSE file) |
| License (model weights) | MIT (for GLM-5.1, GLM-5.2; GLM-5.0 was Apache 2.0 per SOURCE.yaml notes) |
| Import mode | reference-only |
| Weights committed | No |
| Copy allowed | No |
| Execution allowed | No |

**License note:** The SOURCE.yaml notes say "Apache 2.0. No weights committed. Reference for long-context benchmark." The repo's LICENSE file is Apache 2.0. More recent model weight releases (GLM-5.1, GLM-5.2) are under MIT per web research. This is consistent — the GitHub repo infrastructure is Apache 2.0, the released checkpoints carry MIT. Both are permissive.

---

## Model overview

GLM-5 is a large language model series developed by Zhipu AI (trading as Z.ai), a Chinese AI company incubated at Tsinghua University. The GLM (General Language Model) family has been developed since approximately 2021.

**Architecture:** Decoder-only Transformer with a Mixture-of-Experts (MoE) design.

**Parameter counts:**
- Total parameters: 744 billion (sometimes cited as ~745B)
- Active parameters per token: ~40 billion (top-8 routing out of 256 experts)
- Expert sparsity: ~5.9%

**Pre-training:** ~28.5 trillion tokens. Employs DeepSeek Sparse Attention (DSA) for efficient long-context processing, RoPE positional encoding, SwiGLU activations, post-LN normalization.

**Context windows by version:**
- GLM-5 (February 2026): 200K tokens
- GLM-5.1 (April 2026): ~202,752 tokens (up to ~131K output tokens)
- GLM-5.2 (June 2026): 1 million tokens (up to ~128K–131K output tokens), using IndexShare attention optimization reducing per-token FLOPs by 2.9× at 1M context

Source: https://github.com/zai-org/GLM-5 / https://codersera.com/blog/glm-5-2-complete-guide-2026/ / https://webscraft.org/blog/glm5-2026-arhitektura-benchmarki-mozhlivosti-ta-obmezhennya

The repo's tag line is "From Vibe Coding to Agentic Engineering" — explicitly positioning the series as an agentic engineering model, not just a chat model.

---

## Capabilities and benchmarks

GLM-5 is positioned as a frontier-class coding and agentic model. Benchmark claims sourced from web research (treat with appropriate skepticism — third-party benchmark aggregator sites may conflate versions):

**Coding / Software Engineering:**
- SWE-bench Verified: 77.8% (claimed for GLM-5 at launch) — this would place it among the top open-source models. Note: GLM-5.1 and GLM-5.2 scores differ.
  - Source: https://glm-5.org/ / https://presenc.ai/research/zhipu-glm-model-lineage-2026
- SWE-Bench Pro: 62.1 (GLM-5.2), up from 58.4 (GLM-5.1)
  - Source: https://github.com/zai-org/GLM-5 README
- Terminal-Bench 2.1: 81.0 (GLM-5.2), up from 62.0 (GLM-5.1)
  - Source: GitHub README via WebFetch

**Long-horizon agentic tasks:**
- Vending Bench 2: Ranked #1 among open-source models ($4,432 score metric)
  - Source: https://github.com/zai-org/GLM-5

**Math / Reasoning (GLM-5 launch):**
- HMMT Nov. 2025: 96.9
- IMOAnswerBench: 82.5
- Humanity's Last Exam (with tools): 50.4 (vs GPT-5.2's 45.5)
  - Source: https://glm-5.org/

**Comparison vs proprietary models:**
- GLM-5 was reported to rank #1 among open models on LMArena Text Arena and Code Arena at launch, broadly comparable to Claude Opus 4.5 and Gemini 3 Pro on aggregated human preference
  - Source: https://blog.galaxy.ai/compare/glm-5-vs-gpt-4

**Cost vs GPT-4:** Approximately 95–98% cheaper per token when accessed via API
  - Source: https://anotherwrapper.com/tools/llm-pricing/glm-5/gpt-4

**[NOTE: Caution required]** Many benchmark figures are reported by third-party aggregator sites (glm-5.org, benchlm.ai, presenc.ai) rather than peer-reviewed papers. Version confusion is common — GLM-5, GLM-5.1, GLM-5.2 are distinct models. The pinned commit in this lab predates some of these releases. Treat all benchmark figures as indicative, not definitive, without checking the primary technical report.

---

## License and commercial terms

**Repo code:** Apache 2.0
- Permits commercial use, distribution, modification, patent use
- Requires attribution and preservation of license/NOTICE files
- Source: https://github.com/zai-org/GLM-5 LICENSE file (confirmed via WebFetch)

**Model weights (GLM-5.1, GLM-5.2):** MIT
- Unrestricted commercial deployment, fine-tuning, redistribution, no royalties
- Source: https://www.layer3labs.io/guides/glm-5-2-by-zhipu-ai-for-business / https://help.apiyi.com/en/glm-5-1-open-source-api-launched-on-apiyi-en.html

**Practical conclusion:** Both Apache 2.0 (code) and MIT (weights) are permissive open-source licenses with no use-case restrictions, no commercial fees, and no attribution requirements beyond standard license headers. This is a genuinely open model family. The SOURCE.yaml `license: NOT-FOUND` reflects the automated scanner not finding a license file at the pinned commit — it is present in the repo.

**[NOTE]** The lab's `license_review_status: pending` and `copy_allowed: false` reflect unresolved internal due-diligence, not an inherent licensing problem with GLM-5 itself.

---

## Company / organization context

**Company:** Zhipu AI (智谱AI), now operating consumer-facing products under the brand Z.ai
**Founded:** 2019
**Origin:** Incubated at Tsinghua University. Founders: Tang Jie and Li Juanzi (Tsinghua professors)
**Location:** Beijing, China
**Mission statement:** Described as "China's OpenAI"

**Funding (verified from sources):**
- Total raised since founding: ~US$1.5 billion
- 2023 valuation: ~$2.79 billion (~20B yuan)
- Series D (December 2024): $420M
- July 2025: 1B yuan ($140M) from Shanghai state-backed investors
- IPO: Listed January 8, 2026 (Hong Kong)
  - Source: https://www.caproasia.com/2025/12/19/china-artificial-intelligence-startup-zhipu-ai-plans-hong-kong...

**Major investors:** Alibaba, Ant Group, Tencent, Meituan, Xiaomi, HongShan (Sequoia China), Saudi Aramco via Prosperity7 Ventures, Beijing AI Industry Investment Fund, Hangzhou state funds
  - Source: https://www.caproasia.com/2025/12/19/china-artificial-intelligence-startup-zhipu-ai-plans-hong-kong... / https://tracxn.com/d/companies/zhipu/...

**Strategic context:** Zhipu is publicly listed, backed by major Chinese tech companies and state funds. It has relationships with Huawei (Ascend NPU deployment support in GLM-5 repo). The company competes directly with Baidu, Alibaba DAMO, and DeepSeek for the Chinese enterprise AI market, and with OpenAI and Anthropic in the global open-weight model space.
  - Source: https://theairankings.com/zhipu/

---

## Technical patterns in the repository

Based on the GitHub repo at https://github.com/zai-org/GLM-5 (WebFetch + search):

**The repository is primarily a documentation and distribution hub, not a training or inference codebase.** Key contents:

- `README.md` / `README_zh.md` — English and Chinese documentation
- `example/` — Usage examples including `ascend.md` (Ascend NPU deployment guide)
- `resources/` — Logos, benchmark graphics
- `skills/glm-master-skill/` — Skill implementation files [purpose unclear without inspection]
- `requirements.txt` — Python dependencies for inference
- `.github/` — CI/GitHub Actions configuration

**What is NOT in this repo:**
- Model weights (hosted on Hugging Face: `zai-org/GLM-5`, `zai-org/GLM-5.1`, `zai-org/GLM-5.2` and on ModelScope)
- Training code (described as using "slime," a custom async RL infrastructure — not open-sourced separately)
- Fine-tuning scripts

**Supported inference frameworks (documented):**
- vLLM (v0.23.0+)
- SGLang (v0.5.13.post1+)
- Hugging Face Transformers (v0.5.12+)
- KTransformers (v0.5.12+)
- Unsloth (v0.1.47-beta+)
- Ascend NPU: vLLM-Ascend, xLLM, SGLang variants

The "From Vibe Coding to Agentic Engineering" subtitle and `skills/glm-master-skill/` directory suggest the repo is evolving toward agentic patterns — prompt templates, agent scaffolding, tool-use examples — consistent with Zhipu's positioning.
  - Source: https://github.com/zai-org/GLM-5 (WebFetch)

---

## Use cases demonstrated

Based on the SOURCE.yaml note ("Reference for long-context benchmark") and web research:

1. **Long-context code understanding** — The 200K–1M context window is the flagship capability. Reading entire large codebases in a single pass.
2. **Agentic software engineering** — SWE-bench and Terminal-Bench results indicate multi-step code editing/patching workflows.
3. **Long-horizon autonomous tasks** — Vending Bench #1 suggests extended multi-step reasoning chains.
4. **Multilingual coding** — SWE-bench Multilingual (73.3 claimed) suggests non-English codebase handling.
5. **Mathematical reasoning** — Strong HMMT/IMO scores suggest research-grade math problem solving.
6. **Enterprise deployment on Ascend NPU** — Specific documentation for Huawei chip deployment is unusual and relevant for China-based deployments.

---

## What is genuinely useful from this source

Given `import_mode: reference-only`, no code can be copied or executed. Legitimate derivable value:

1. **Architecture design patterns** — The MoE design with IndexShare sparse attention is a documented innovation for long-context efficiency. Understanding this pattern informs infrastructure decisions.
2. **Deployment stack documentation** — The vLLM/SGLang configuration patterns for running 744B MoE models apply to any comparable model (DeepSeek, Qwen, Mixtral).
3. **Benchmark methodology** — The combination of Terminal-Bench, SWE-bench Pro, and Vending Bench represents a modern agentic evaluation stack useful as a reference for evaluating *any* model choice.
4. **Agent skill structure** — The `skills/glm-master-skill/` directory may contain prompt engineering patterns relevant to agentic systems design.
5. **Long-context use case patterns** — Understanding what 1M-token context actually enables (codebase ingestion, long-horizon tasks) is useful for product design.
6. **API comparison baseline** — GLM-5 API is OpenAI-compatible; patterns for calling it apply to any model with a compatible API.

---

## What should NOT be used

1. **No code or weight copying** — `copy_allowed: false` in SOURCE.yaml. License reviews are pending.
2. **No execution** — `execution_allowed: false`.
3. **No data derived from weights** — If the weights embed training data from proprietary sources (unknown — data provenance not published), fine-tuning outputs could inherit problems.
4. **Do not rely on benchmark figures from aggregator sites** — glm-5.org, benchlm.ai, and similar sites produce plausible but unverified numbers. Always trace back to the official technical report.

---

## EU regulatory considerations

**GDPR — API use:**
- If using GLM-5 via the Z.ai hosted API (`docs.z.ai`), data transfers to servers in China. The EU has no adequacy decision for China, making this non-compliant for personal data without explicit legal basis.
- The DeepSeek precedent (Italy Garante blocking access, EDPB task force, multiple EU probes — https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities...) establishes that EU DPAs will act against Chinese AI services handling EU personal data.

**GDPR — Self-hosted use:**
- Downloading open weights and running on EU infrastructure eliminates the cross-border transfer risk entirely. No DPA agreement needed; data stays under operator control. This is the recommended GDPR-compliant path for Chinese open-weight models.
- Source: https://simfero.eu/knowledge-base/self-hosted-open-source-llms-for-gdpr-compliance/

**EU AI Act:**
- GLM-5 at 744B parameters likely exceeds the 10^25 FLOP training threshold triggering Article 55 systemic-risk obligations. Zhipu is not an EU entity but if GLM-5 is "placed on the EU market" (which Hugging Face distribution arguably constitutes), some obligations may apply.
- Source: https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566
- [Inference]: A European company deploying GLM-5 in a product for EU users as a service would need legal advice on whether it inherits any AI Act obligations as a downstream deployer.

**Content behavior:**
- Chinese LLMs are known to have hard-coded restrictions on topics related to Chinese political red lines (Taiwan, Tiananmen, etc.). This is documented for DeepSeek and inferred for GLM-5. This is relevant for products requiring unbiased information retrieval.
- Source: https://www.cigionline.org/articles/chinese-ai-models-and-the-high-stakes-fight-for-ai-neutrality/

**Backdoor risk:**
- European policy analysts have noted that Chinese LLMs could theoretically embed non-removable backdoors. This is a theoretical risk, not a demonstrated one for GLM-5. It is referenced in EU policy literature.
- Source: https://www.cep.eu/eu-topics/details/european-ai-sovereignty-instead-of-chinese-models.html

---

## Competitive positioning

| Model | SWE-bench Verified | Context | License | Parameter count | Origin |
|---|---|---|---|---|---|
| GLM-5.2 | ~62.1 (Pro) | 1M | MIT (weights) | 744B MoE, 40B active | China / Zhipu |
| Kimi K2 | 65.8% | 128K | Modified MIT | 1T MoE, 32B active | China / Moonshot |
| DeepSeek V3 | ~50–65% (version-dependent) | 128K | MIT | 671B MoE | China / DeepSeek |
| Qwen3-Coder | ~73%+ | 256K | Apache 2.0 | Various | China / Alibaba |
| Devstral 2 | 72.2% | 256K | Apache 2.0 | 123B | France / Mistral |
| Claude Opus 4.x | ~80–82% | 200K | Proprietary | Unknown | USA / Anthropic |

GLM-5 is a competitive open-weight model but not currently the leader in coding benchmarks. Its main differentiator within the Chinese open-source ecosystem is the 1M context window (GLM-5.2) — longer than Kimi K2's 128K. Qwen and Devstral are more widely adopted in European production deployments due to origin and cleaner compliance profiles.
  - Source: https://artificialanalysis.ai/models/comparisons/gpt-5-4-vs-glm-5 / https://benchlm.ai/blog/posts/best-chinese-llm

---

## Business applications

**When GLM-5 is a reasonable choice:**
- Self-hosted, EU-infrastructure deployment where cost is critical and 40–80B active parameters suffice
- Long-document / long-context analysis (1M window is rare in open weights as of 2026)
- Teams with Ascend NPU hardware — GLM-5 has unique official Ascend support, unlike most Western open models
- Research use where studying Chinese LLM design patterns matters

**When to prefer alternatives:**
- EU commercial products serving EU customers via API → prefer Mistral, Anthropic, or EU-hosted inference of Llama/Qwen
- Best-in-class coding agents → Qwen3-Coder, Devstral 2, or proprietary models have better-verified benchmark performance
- Compliance-sensitive products → provenance uncertainty and pending license reviews in this lab make GLM-5 higher risk until resolved

---

## Related sources in this lab

- `sources/kimi-model-family/` — The other Chinese model family in this lab (Moonshot AI's Kimi K2). Both are reference-only Chinese MoE models. [See companion dossier]
- `sources/terminal-coding-agent/` — This is `kimi-code`, Moonshot AI's CLI coding agent. It *uses* Kimi K2 as its backbone model. The GLM and Kimi sources contextualize the model families underlying these agents.

---

## Open questions

1. What is in `skills/glm-master-skill/`? This directory could contain reusable prompt patterns — worth inspecting without copying.
2. What does the pinned commit `bd7fafe70ba9ac7cbfe070573f8acf76e5944d5d` correspond to? Is it a specific version (GLM-5.0, 5.1, 5.2)? The retrieved date (2026-06-22) suggests it could be GLM-5.2-era content.
3. Does Zhipu AI publish a technical paper for GLM-5? The GLM-4 paper is at arXiv:2406.12793. A GLM-5 technical report would clarify training data provenance.
4. What is the actual data provenance of GLM-5 pre-training? The 28.5T token claim has no published breakdown — this matters for data copyright risk.
5. Can the lab's internal license review be resolved by comparing the Apache 2.0 repo license with standard Apache 2.0 terms? The license text should be inspectable.

---

## Final research conclusion

GLM-5 is a credible, frontier-competitive open-weight model from a publicly-listed Chinese AI company (Zhipu AI / Z.ai) with significant state and tech-giant backing. Its headline differentiator is an expanding context window (reaching 1M tokens in GLM-5.2) combined with strong agentic and coding capabilities. The Apache 2.0 (code) + MIT (weights) licensing is genuinely permissive — there are no commercial use fees or meaningful restrictions for businesses below large-scale thresholds.

For this lab, the source is correctly classified as reference-only: useful as a design and benchmark reference for understanding what a 744B MoE model can do, how to deploy it, and how it compares to alternatives — but not suitable for code copying or direct execution without completing the pending license and security reviews.

The primary concern for EU-context use is not the license itself but the compliance posture: API-based use would transfer data to China (GDPR red flag given the DeepSeek precedent); self-hosted deployment on EU infrastructure would be compliant. The content-restriction behavior on politically sensitive topics is an inherent property of Chinese LLMs that persists regardless of deployment mode.

---

*Sources consulted:*
- https://github.com/zai-org/GLM-5
- https://glm-5.org/
- https://presenc.ai/research/zhipu-glm-model-lineage-2026
- https://docs.z.ai/guides/llm/glm-5.2
- https://www.caproasia.com/2025/12/19/china-artificial-intelligence-startup-zhipu-ai-plans-hong-kong...
- https://tracxn.com/d/companies/zhipu/
- https://artificialanalysis.ai/models/comparisons/gpt-5-4-vs-glm-5
- https://codersera.com/blog/glm-5-2-complete-guide-2026/
- https://webscraft.org/blog/glm5-2026-arhitektura-benchmarki-mozhlivosti-ta-obmezhennya
- https://benchlm.ai/blog/posts/best-chinese-llm
- https://www.layer3labs.io/guides/glm-5-2-by-zhipu-ai-for-business
- https://www.trendingtopics.eu/glm-5-2-chinas-zhipu-ai-beats-even-googles-top-models-with-its-new-open-llm/
- https://wandb.ai/wandb_fc/genai-research/reports/Tutorial-Running-inference-with-Zhipu-AI-s-GLM-5...
- https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566
- https://simfero.eu/knowledge-base/self-hosted-open-source-llms-for-gdpr-compliance/
- https://www.cep.eu/eu-topics/details/european-ai-sovereignty-instead-of-chinese-models.html
- https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities
- https://www.cigionline.org/articles/chinese-ai-models-and-the-high-stakes-fight-for-ai-neutrality/
