# Useful Path Index - config_utilities

## Source Record

- Upstream: [MIT-SPARK/config_utilities](https://github.com/MIT-SPARK/config_utilities)
- Pinned commit: `629688a1f6c24ff38130aebd528838a569179dac`
- Default branch inspected: `main`
- Date inspected: 2026-06-27
- License status: `BSD-3-Clause`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `config_utilities/include/config_utilities/config.h` - core typed-config interface pattern for C++ systems.
- `config_utilities/src/yaml_parser.cpp` - YAML parsing boundary that keeps external config format handling out of domain logic.
- `config_utilities/src/validation.cpp` - validation layer pattern for rejecting bad config early.
- `config_utilities/demos/demo_config.cpp` - small usage example showing how configs are declared and loaded.
- `config_utilities/test/tests/yaml_parsing.cpp` - focused tests for parser behavior and edge cases.
- `config_utilities_ros/include/config_utilities_ros/ros_dynamic_config_server.h` - ROS adapter boundary for exposing dynamic config without mixing it into the core package.

## Risks And Limitations

- ROS/C++ assumptions require compatibility review before any adaptation.
- Future vendoring, if any, should be limited to a specifically approved subsystem.
