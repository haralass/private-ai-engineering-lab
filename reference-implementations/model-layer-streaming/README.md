# Reference Implementation: Model Layer Streaming

Based on study of: `sources/model-layer-streaming/` (lyogavin/airllm)
Status: research

## Pattern

AirLLM implements layer-by-layer model loading: instead of loading a full model into GPU memory, it loads and executes one transformer layer at a time, offloading each layer to CPU after use.

This allows running very large models (70B+) on a single consumer GPU with ~4GB VRAM.

## Key patterns studied

| Pattern | Description |
|---|---|
| Layer-wise loading | Load one transformer layer at a time from disk |
| GPU offloading | Move each layer back to CPU after forward pass |
| Memory management | Explicit cache clearing between layers |
| Compatibility | Works with HuggingFace model format |
| Quantization | Optional 4-bit quantization for further memory reduction |

## Memory requirement

```
Model: Llama-2-70B
Without AirLLM: ~140GB VRAM (8x A100)
With AirLLM:    ~4GB VRAM (consumer GPU)
Trade-off:      ~2-10x slower inference (I/O bound)
```

## Use cases in this lab

- Running local model baselines on Mac for evaluation
- Cost comparison: local vs. API inference
- Privacy-sensitive inference (nothing leaves the machine)

## Important caveats

- Not production-ready without a fresh benchmark on our hardware
- Inference speed is significantly slower than full-load
- Some models may have compatibility issues

See `sources/model-layer-streaming/upstream/` for the full AirLLM source.
