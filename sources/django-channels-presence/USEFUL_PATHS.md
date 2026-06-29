# Useful Path Index - django-channels-presence

## Source Record

- Upstream: [mitmedialab/django-channels-presence](https://github.com/mitmedialab/django-channels-presence)
- Pinned commit: `bcc9f71ca2162d8d8539466ad9787715d43c0faf`
- Default branch inspected: `master`
- Date inspected: 2026-06-27
- License status: `MIT`
- Import mode: `local-research-only`

## Useful Files And Subdirectories

These are local research pointers only. No upstream files are copied into this repository.

- `channels_presence/models.py` - room/presence data model for tracking live websocket participants.
- `channels_presence/decorators.py` - lightweight decorator API for joining/leaving presence-aware channels.
- `channels_presence/tasks.py` - cleanup task pattern for expiring stale presence rows.
- `channels_presence/management/commands/prune_presences.py` - operational maintenance command for scheduled pruning.
- `channels_presence/tests.py` - compact tests for presence lifecycle behavior.
- `docs/usage.rst` - concise package usage documentation structure.

## Risks And Limitations

- Django Channels APIs may have changed since the pinned commit.
- Keep current use as pattern research unless a future PR approves exact MIT-file reuse.
