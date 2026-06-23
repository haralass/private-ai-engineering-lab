# Source Research Dossier: Kimi-K2 (Moonshot AI)

**Source path:** `sources/kimi-model-family/`
**Researched:** 2026-06-23

---

## Repository identity

| Field | Value |
|---|---|
| Display name | Kimi-K2 |
| Functional name | kimi-model-family |
| GitHub URL | https://github.com/MoonshotAI/Kimi-K2 |
| Pinned commit | `1b4022bbb7187cf4011a8bdf0b4cd10e2daa26c4` |
| Retrieved | 2026-06-22 |
| License (code + weights) | Modified MIT License |
| Import mode | reference-only |
| Weights committed | No |
| Copy allowed | No |
| Execution allowed | No |

**License note:** SOURCE.yaml notes "Modified MIT license — no weights committed. Reference for model family." The "Modified MIT" is not standard MIT — see license section below for the critical commercial attribution clause. `license: NOT-FOUND` in SOURCE.yaml reflects the automated scanner, but the license file is confirmed present at https://github.com/moonshotai/Kimi-K2/blob/main/LICENSE.

---

## Model overview

Kimi K2 is a Mixture-of-Experts (MoE) language model developed by Moonshot AI, a Chinese AI startup founded in 2023. The Kimi brand started as a long-context chatbot in October 2023 and has evolved into a model family explicitly targeting agentic AI use cases.

**Architecture:**
- Total parameters: 1 trillion
- Activated parameters per token: 32 billion
- Architecture: 61 transformer layers (1 dense + 60 MoE layers)
- Expert routing: 384 experts, 8 selected per token + 1 shared expert
- Vocabulary: 160K tokens
- Attention: MLA (Multi-head Latent Attention) — same mechanism as DeepSeek-V3
- Activation: SwiGLU
- Context window: 128K tokens (base release); extended to 256K in the September 2025 update (Kimi-K2-Instruct-0905)

**Training:**
- Tokens: 15.5 trillion pre-training tokens
- Optimizer: Muon optimizer (a novel optimizer applied at unprecedented scale — the Muon optimizer was developed specifically for large-scale distributed training stability)
- Training instability: Zero — the team claims zero training instability events
- Format: block-fp8 (on Hugging Face)

**Two model variants:**
1. `Kimi-K2-Base` — foundation model, for fine-tuning and custom use
2. `Kimi-K2-Instruct` — post-trained for chat and agentic use, the main deployable variant

Source: https://github.com/MoonshotAI/Kimi-K2 (README, WebFetch) / https://arxiv.org/html/2507.20534 (Kimi K2 technical report)

**Family evolution (post-K2 releases, for context):**
- Kimi-K2-Instruct-0905 (September 2025): context doubled to 256K, agentic coding improvements
- Kimi K2 Thinking (November 2025): reasoning-focused variant with extended chain-of-thought
- Kimi K2.5 (January 2026): multimodal upgrade, native vision capability, video-to-code
- Kimi K2.6 (2026): further performance improvements on SWE-bench Pro
- Kimi K2.7-Code (2026): coding-specialized variant
  Sources: https://siliconangle.com/2025/11/07/moonshot-launches-open-source-kimi-k2-thinking-ai... / https://www.kimi.com/blog/kimi-k2-5

---

## Capabilities and benchmarks

Kimi K2 is explicitly designed for agentic tasks — the official site tagline is "Open Agentic Intelligence." It is not primarily a chat model.

**Flagship capability:** Sustained autonomous multi-step tool-use chains. The model is described as able to execute 200–300 sequential tool calls autonomously. Later versions extended this to multi-day project execution.
  Source: https://moonshotai.github.io/Kimi-K2/

**Benchmark results (Kimi K2 Instruct, original release — July 2025):**

| Benchmark | Score | Notes |
|---|---|---|
| SWE-bench Verified | 65.8% | Single attempt, with bash/editor tools |
| SWE-bench Multilingual | 47.3% | Non-English codebases |
| LiveCodeBench v6 | 53.7% | Competitive programming |
| AIME 2025 | 49.5% | Math olympiad |
| GPQA-Diamond | 75.1% | Expert-level science Q&A |
| MATH-500 | 97.4% | Math problems |
| MMLU | 89.5% | General knowledge |
| IFEval | 89.8% | Instruction following |
| Tau2-Bench | 66.1% | Tool-use in agentic settings |
| ACEBench (English) | 76.5% | Agent capability evaluation |

Source: https://arxiv.org/html/2507.20534 / https://moonshotai.github.io/Kimi-K2/

**SWE-bench context (65.8%):** At the time of the original K2 release (July 2025), this score placed it among the top open-source models — above Claude Sonnet 3.5 and comparable to early Claude Opus 4 scores. Subsequent versions (K2.6: 58.6% on SWE-bench Pro, which uses harder problems) maintain top-tier open-source standing.
  Source: https://www.deeplearning.ai/the-batch/kimi-k2-6-matches-open-qwen3-6-max-anddeepseek-v4...

**vs. Competitors (as of K2.6, mid-2026):**
- Kimi K2.6 is broadly competitive with Qwen3.6 Max and DeepSeek V4 on coding benchmarks
- Falls ~2–5 points behind top closed models (GPT-5, Claude Opus 4.x) on SWE-bench Pro
- Leads in sustained agentic execution duration vs. most open-source alternatives
  Source: https://www.deeplearning.ai/the-batch/kimi-k2-6-matches-open-qwen3-6-max-anddeepseek-v4...

**[NOTE: Caution]** Benchmark figures vary significantly across aggregator sites and often conflate K2, K2.5, K2.6, K2.7-Code. The 65.8% SWE-bench Verified figure is from the official technical report (arXiv:2507.20534) for the original K2 Instruct. It is a reliable primary source.

---

## License and commercial terms

**License:** Modified MIT License
  - Full text: https://github.com/moonshotai/Kimi-K2/blob/main/LICENSE
  - Applies to both code and model weights

**What is standard MIT:**
- Use, copy, modify, merge, publish, distribute, sublicense, sell — all permitted
- No fee, no royalty
- Attribution: include the license/copyright notice

**The modification (commercial attribution clause):**
> If the Software (or derivative works) is used for commercial products/services with **>100 million monthly active users** OR **>$20 million monthly revenue**, you must prominently display "Kimi K2" on the user interface.

**Practical implications:**
- For any company below those thresholds: identical to standard MIT. No restrictions on commercial use.
- For companies above those thresholds: one obligation — display "Kimi K2" prominently in the UI. This is an attribution requirement, not a revenue-share or prohibition.
- The $20M/month threshold is ~$240M ARR. This is a high bar; the vast majority of commercial deployments will never hit it.

**The Hacker News concern:** There is a documented HN thread (https://news.ycombinator.com/item?id=44544358) noting that this is not standard OSI-approved open-source. The modification technically makes it non-OSI-compliant. For legal departments that require OSI-certified licenses, this is worth flagging. Practically, the restriction is benign.

**Comparison with GLM-5 license:** GLM-5 weights use standard MIT (no commercial attribution clause). GLM-5 is more permissive in the strict sense.

---

## Company / organization context

**Company:** Moonshot AI (月之暗面, "Dark Side of the Moon")
**Founded:** March 2023, Beijing, China
**Key personnel:**
- CEO/Founder: Yang Zhilin — born 1992, BSc Tsinghua University (top of class 2015), PhD Carnegie Mellon University; worked at Meta and Google Brain during PhD; co-author of Transformer-XL and XLNet papers; co-authored papers with Yoshua Bengio and Yann LeCun
- Co-founders: Zhou Xinyu, Wu Yuxin, Zhang Yutao — all Tsinghua alumni
  Source: https://en.wikipedia.org/wiki/Yang_Zhilin / https://finance.yahoo.com/news/meet-yang-zhilin-moonshot-ai...

**Kimi history:**
- October 2023: Kimi 1.0 launches with 200K Chinese character context window
- March 2024: Upgraded to 2M Chinese character context
- January 2025: Kimi K1.5 — claimed parity with OpenAI o1 on math/coding/multimodal
- July 2025: Kimi K2 open-weights release — described as a "second DeepSeek moment" for the open-source community
  Source: https://en.wikipedia.org/wiki/Moonshot_AI / https://huggingface.co/blog/fdaudens/moonshot-ai-kimi-k2-explained

**Funding (verified):**
- End of 2025 valuation: $4.3 billion (following $500M Series C, December 2025, led by IDG Capital)
- Early 2026: $700M raise → $10B valuation
- May 2026: $2B raise at $20B valuation
- Targeting: $30B valuation in a new round (as of mid-2026)
- ARR: >$200M/month reported as of April 2026
  Source: https://techcrunch.com/2026/05/07/chinas-moonshot-ai-raises-2b-at-20b-valuation... / https://the-decoder.com/moonshot-ai-targets-a-30-billion-valuation...

**Strategic position:** Moonshot is one of the fastest-growing Chinese AI startups. It is primarily consumer-facing (Kimi chatbot with >20M monthly active users by late 2025) and increasingly competing in the developer/open-source space through K2 releases. Its trajectory — open-sourcing a 1T parameter model — parallels Meta's LLaMA strategy: build a developer ecosystem around the model family.

---

## Technical patterns in the repository

Based on the GitHub README (WebFetch) and search results:

**Repository: https://github.com/MoonshotAI/Kimi-K2**

**Contents:**
- `README.md` — Model description, benchmarks, deployment instructions
- `docs/` — Expanded documentation
- `LICENSE` — Modified MIT text
- No training code, no weight files

**What the repository provides:**
1. Model architecture specification (layer counts, expert routing, MLA attention)
2. Inference framework compatibility matrix (vLLM, SGLang, KTransformers, TensorRT-LLM)
3. API access documentation — OpenAI-compatible and Anthropic-compatible endpoints at `platform.moonshot.ai`
4. Links to Hugging Face checkpoints (`moonshotai/Kimi-K2`, block-fp8 format)
5. Benchmark results table

**Related Moonshot GitHub repositories (not in this lab's sources):**
- https://github.com/MoonshotAI/Kimi-K2.5 — K2.5 successor
- https://github.com/MoonshotAI/kimi-code — the CLI coding agent (IS in this lab as `terminal-coding-agent`)
- https://github.com/MoonshotAI/kimi-cli — predecessor CLI

**Inference frameworks supported:**
- vLLM
- SGLang
- KTransformers
- TensorRT-LLM
- Accessible via OpenAI API-compatible interface

---

## Use cases demonstrated

1. **Agentic software engineering** — The primary design target. Multi-step code editing, file system navigation, test execution, bug patching. 65.8% SWE-bench Verified is the benchmark expression of this.
2. **Multi-step tool calling** — Documented capability for 200–300 sequential tool calls. This is the foundation for any long-running agent pipeline.
3. **Large codebase comprehension** — 128K context (256K in later variants) enables ingesting large codebases without chunking.
4. **Mathematical reasoning** — 97.4% MATH-500 and 49.5% AIME 2025 indicate genuine mathematical capability.
5. **Knowledge-intensive Q&A** — 75.1% GPQA-Diamond demonstrates expert-level science reasoning.
6. **Instruction following** — 89.8% IFEval, strong for structured output generation.

**Less appropriate for:**
- Vision/image tasks (K2 is text-only; K2.5 adds vision)
- Very long context (>128K; K2.6 and GLM-5.2 have longer windows)

---

## What is genuinely useful from this source

Given `import_mode: reference-only`:

1. **Architecture reference for MoE at 1T scale** — The MLA attention mechanism, 384-expert routing, and block-fp8 weight format are documented innovations. Understanding these informs system design for any large-scale inference deployment.
2. **Muon optimizer documentation** — The Muon optimizer at 1T scale is a novel training contribution. Relevant for anyone training or fine-tuning large models.
3. **Agentic benchmark methodology** — The combination of SWE-bench Verified, Tau2-Bench, and ACEBench defines the current standard for evaluating agentic model capability. Useful as an evaluation framework for model selection.
4. **API compatibility patterns** — Kimi K2 offers both OpenAI-compatible and Anthropic-compatible API endpoints, which is a pattern that simplifies multi-model routing in production systems.
5. **Deployment configuration patterns** — vLLM and SGLang configs for a 1T MoE model provide infrastructure patterns applicable to similar models.
6. **The `terminal-coding-agent` (kimi-code) in this lab** — Understanding Kimi K2's capabilities directly informs how to evaluate, configure, and extend the `kimi-code` CLI tool (the `terminal-coding-agent` source in this lab). The model is the intelligence layer; the CLI is the scaffolding.

---

## What should NOT be used

1. **No code or weight copying** — `copy_allowed: false`. License review pending. Even though the Modified MIT is permissive, the internal review gate has not been cleared.
2. **No execution** — `execution_allowed: false`.
3. **Modified MIT is not OSI-certified** — If the lab's downstream products must use OSI-certified licenses (common in enterprise legal policies), this needs explicit legal sign-off.
4. **Do not conflate K2 versions** — The pinned commit covers the K2 base release. K2.5, K2.6, K2.7 have different capabilities and potentially different license terms. Ensure the correct version is evaluated for any specific use.
5. **Training data provenance** — Moonshot has not published a detailed breakdown of the 15.5T pre-training tokens. There is non-zero risk of copyright-encumbered content in the training set, as with virtually all frontier models.

---

## EU regulatory considerations

**GDPR — Hosted API use:**
- Using `platform.moonshot.ai` API routes data to Moonshot's servers in China. China has no EU adequacy decision. This is structurally the same situation as DeepSeek: personal data flows to a jurisdiction without GDPR-equivalent protection.
- The Italian Garante blocked DeepSeek (January 2025) under exactly this rationale. Belgium and Ireland opened parallel probes. The EDPB formed an AI enforcement task force.
  Source: https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities...
- [Inference]: Moonshot AI's Kimi APIs face the same GDPR exposure as DeepSeek. If personal data is involved, using the hosted API is legally risky in the EU until either an adequacy decision exists or contractual mechanisms (SCCs + TIA) are established — difficult given Chinese data-access laws.

**GDPR — Self-hosted weights:**
- Running Kimi K2 weights on EU infrastructure eliminates the cross-border transfer risk. No DPA agreement needed; the data never leaves EU infrastructure.
- This is the only reliably GDPR-compliant path for using Kimi K2 with EU personal data.
  Source: https://simfero.eu/knowledge-base/self-hosted-open-source-llms-for-gdpr-compliance/

**EU AI Act:**
- Kimi K2 at 1T parameters almost certainly exceeds the 10^25 FLOP training compute threshold triggering Article 55 systemic-risk requirements under the EU AI Act.
- [Inference]: A European company deploying Kimi K2 as a service for EU users would likely be classified as a downstream deployer of a GPAI model with systemic risk. Legal obligations (transparency, adversarial testing, incident reporting) would apply to the deployer.
  Source: https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566

**Content restrictions:**
- Like other Chinese LLMs, Kimi models may exhibit hard-coded refusals or biased responses on topics sensitive to the Chinese government (Taiwan, Tibet, Tiananmen Square, etc.).
- [Inference, not independently verified for Kimi specifically]: This is documented for DeepSeek; it is reasonable to expect similar behavior in Kimi given the shared training environment and Chinese regulatory requirements for AI models.
  Source: https://www.cigionline.org/articles/chinese-ai-models-and-the-high-stakes-fight-for-ai-neutrality/

**EU AI Act — Open weights exception:**
- The EU AI Act has a limited carve-out for "open-source" models released with model weights. However, this exception does not apply to models with systemic risk. Given K2's parameter scale, the open-weights exception would likely not apply.

---

## Competitive positioning

| Model | SWE-bench Verified | Context | License | Scale | Origin |
|---|---|---|---|---|---|
| Kimi K2 (original) | 65.8% | 128K | Modified MIT | 1T total / 32B active | China / Moonshot |
| GLM-5 | ~77.8% (claimed) | 200K–1M | Apache 2.0 / MIT | 744B / 40B | China / Zhipu |
| DeepSeek V3 | ~50–65% | 128K | MIT | 671B MoE | China / DeepSeek |
| Qwen3-Coder | ~73–77% | 256K | Apache 2.0 | Various sizes | China / Alibaba |
| Devstral 2 (Mistral) | 72.2% | 256K | Apache 2.0 | 123B | France / Mistral |
| Claude Opus 4.x | ~80–82% | 200K | Proprietary | Unknown | USA / Anthropic |

**Kimi K2's unique positioning:**
- Among the most capable open-weight models at its time of release (July 2025)
- Specifically optimized for agentic tool-use chains — this is a deliberate design choice, not just a side effect of scale
- The `kimi-code` CLI agent (in this lab) is the practical manifestation of this agentic capability
- Fast-moving model family with major version upgrades every 2–4 months

Source: https://benchlm.ai/blog/posts/best-chinese-llm / https://www.deeplearning.ai/the-batch/kimi-k2-6-matches-open-qwen3-6-max-anddeepseek-v4...

---

## Business applications

**When Kimi K2 / the kimi-code agent is the right choice:**
- Self-hosted coding agent infrastructure where operator wants an open-weight model (no API dependency)
- Cost-sensitive production deployments — 32B active parameters means significantly cheaper inference than running full-dense 70B+ models
- Agentic pipelines requiring long tool-call chains — this is where K2 is specifically designed to excel
- Teams already using `kimi-code` CLI (in this lab) — the model and the agent scaffold are co-designed

**When to prefer alternatives:**
- EU commercial products using hosted API → GDPR risk; prefer Anthropic (EU-compliant SCCs exist), Mistral (EU company), or EU-hosted self-serve
- Maximum coding performance → Qwen3-Coder or proprietary models lead benchmarks as of mid-2026
- Longest context → GLM-5.2 offers 1M token context vs K2's 128K–256K
- Products requiring OSI-certified licensing → Kimi K2's Modified MIT is not OSI-approved; use Apache 2.0 models (GLM-5, Qwen, Devstral)

---

## Related sources in this lab

- `sources/terminal-coding-agent/` — This is **`kimi-code`** from `MoonshotAI/kimi-code`. The kimi-code CLI is the primary consumer of Kimi K2 model capabilities. It is vendored-snapshot (not reference-only) and is a direct product development candidate. Understanding Kimi K2 is essential context for evaluating kimi-code.
- `sources/glm-model-family/` — The other Chinese model family in this lab. GLM-5 vs Kimi K2 represents two different Chinese labs' approaches to frontier open-weight models. [See companion dossier]
- `sources/structured-agent-development/` — [Inference based on lab structure] Likely contains patterns for building on top of the agentic capabilities that Kimi K2 provides.

---

## Open questions

1. What is the technical report version covered by the pinned commit (`1b4022bbb`)? The official technical report is arXiv:2507.20534 — does this correspond to the base K2, or a later variant?
2. Is there a formal data provenance disclosure for the 15.5T training tokens? The team claims no instability and a clean training run, but training data composition is not published.
3. Does the Modified MIT license's commercial threshold clause apply to the *model weights* specifically, or could an argument be made that a downstream product is not a "derivative work" of the model weights? Legal analysis required.
4. Is there documented behavior of Kimi K2 on Chinese politically sensitive topics? The DeepSeek behavior is well-documented; Kimi's is less so in English-language sources.
5. Given that kimi-code (the CLI) is MIT-licensed, and Kimi K2 (the model) is Modified MIT — what is the legal interaction when kimi-code is used with a self-hosted K2 vs. Moonshot's hosted API?

---

## Final research conclusion

Kimi K2 is a technically impressive, purpose-built agentic language model from Moonshot AI — one of China's fastest-growing AI companies. Its 1T parameter MoE design, explicit optimization for multi-step tool use, and strong SWE-bench performance make it directly relevant to this lab's `terminal-coding-agent` source (`kimi-code`). The Modified MIT license is commercially permissive at most realistic scales, with the only substantive addition being a UI attribution requirement for very large deployments.

For this lab, the source is valuable as a reference model for the `kimi-code` CLI agent: understanding Kimi K2's capabilities, limitations, context window, and agentic design philosophy directly informs how to use, evaluate, and extend `kimi-code`. The reference-only import mode is appropriate given the pending license/security reviews.

The same EU compliance caution applies as with GLM-5: hosted API use would create GDPR risk (data to China), while self-hosted deployment on EU infrastructure is the compliant path. The rapidly evolving model family (K2 → K2.5 → K2.6 → K2.7) means the lab should periodically reassess whether the pinned K2 base version remains the right reference anchor, or whether K2.6/K2.7-Code better represents the current frontier.

---

*Sources consulted:*
- https://github.com/MoonshotAI/Kimi-K2
- https://moonshotai.github.io/Kimi-K2/
- https://arxiv.org/html/2507.20534 (Kimi K2 Technical Report)
- https://github.com/moonshotai/Kimi-K2/blob/main/LICENSE
- https://en.wikipedia.org/wiki/Moonshot_AI
- https://en.wikipedia.org/wiki/Yang_Zhilin
- https://techcrunch.com/2026/05/07/chinas-moonshot-ai-raises-2b-at-20b-valuation...
- https://the-decoder.com/moonshot-ai-targets-a-30-billion-valuation...
- https://siliconangle.com/2025/11/07/moonshot-launches-open-source-kimi-k2-thinking-ai...
- https://github.com/MoonshotAI/kimi-code
- https://www.marktechpost.com/2026/06/06/moonshot-ai-releases-kimi-code-cli...
- https://www.deeplearning.ai/the-batch/kimi-k2-6-matches-open-qwen3-6-max-anddeepseek-v4...
- https://huggingface.co/blog/fdaudens/moonshot-ai-kimi-k2-explained
- https://news.ycombinator.com/item?id=44544358
- https://www.euronews.com/next/2025/01/31/deepseek-ai-blocked-by-italian-authorities...
- https://simfero.eu/knowledge-base/self-hosted-open-source-llms-for-gdpr-compliance/
- https://papers.ssrn.com/sol3/papers.cfm?abstract_id=6106566
- https://benchlm.ai/blog/posts/best-chinese-llm
- https://www.turingpost.com/p/chinesemodels
