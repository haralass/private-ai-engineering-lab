# bchiang7/spotify-profile

## Identity
- **Owner**: bchiang7
- **Repository**: spotify-profile
- **URL**: https://github.com/bchiang7/spotify-profile
- **Live URL**: https://spotify-profile.herokuapp.com (Heroku, may be down)
- **Commit SHA**: d78c3c43f23232a7ddf0d859bacb003ad2ffac89
- **Analysis date**: 2026-06-27
- **Original / Fork**: original
- **Status**: active (archived updates Jan 2023 — last push)
- **Transferred**: no

## Relationship classification
full-open-source-product

Evidence: Complete client + server application with OAuth, API integration, data visualization, and deployment.

## Licensing
- **Code license**: ISC (from package.json; no root LICENSE file)
- **Attribution required**: yes
- **Asset restrictions**: none significant
- **Reuse verdict**: directly reusable (ISC is permissive, equivalent to MIT)

## Technical profile
- **Languages**: JavaScript (React + Node.js/Express)
- **Frameworks**: React 16, Express, Axios
- **Key dependencies**: react-router-dom, styled-components, chart.js (via CDN or package), axios
- **Build system**: Create React App (client), Node.js (server)
- **Package manager**: yarn (separate for client and server)
- **Tests**: minimal (App.test.js shell only)
- **CI**: no
- **Architecture**: Client/server split: `server/` is Express that handles Spotify OAuth token exchange (auth code flow) and proxies API requests. `client/` is CRA React app. Server keeps tokens out of browser storage, avoiding PKCE complexity while maintaining security. Multiple React components map to Spotify API resources.
- **State management**: React local state (no Redux)
- **Rendering model**: Client-side React SPA, API-driven

## Useful content (exact files)

### Directly reusable code
- `server/index.js` — Spotify OAuth auth code flow: `/login`, `/callback`, `/refresh_token` endpoints. Shows the correct server-side token exchange pattern: client redirects to `/login`, server exchanges code for tokens, returns to client with tokens in URL hash. Refresh token stored server-side.
- `client/src/components/App.js` — Root component with React Router setup and auth token handling from URL hash
- `client/src/components/FeatureChart.js` — Radar chart of Spotify track audio features (acousticness, danceability, energy, instrumentalness, liveness, speechiness, valence). Shows chart.js radar chart integration.
- `client/src/components/Profile.js` — Displays user profile data: followers, top artists, top tracks, recently played
- `client/src/components/TopTracks.js` / `TopArtists.js` — Paginated Spotify data with time range toggle (4 weeks, 6 months, all time)
- `client/src/components/Playlist.js` / `Playlists.js` — Playlist listing and detail view with track analysis
- `client/src/components/Recommendations.js` — Spotify recommendations API integration based on top tracks as seeds

### Adaptable patterns
- **Spotify OAuth code flow with Express backend**: The `server/index.js` auth code pattern is the correct approach for web apps using Spotify (or any OAuth provider) — the backend exchanges the code for tokens, preventing client-side token exposure. The refresh token rotation is handled server-side. Directly reusable for any OAuth2 integration.
- **URL hash token passing**: After the server obtains tokens, it redirects to `/#access_token=...&expires_in=...`. The client reads from `window.location.hash` on load. This is a common pattern for SPAs receiving tokens from a server-side OAuth flow.
- **Audio feature radar chart**: `FeatureChart.js` maps Spotify's 7 audio features to a radar chart. The mapping of API fields to visual dimensions is directly adaptable for any radar/spider chart visualization.
- **Time range toggle pattern**: Top tracks/artists components toggle between `short_term`, `medium_term`, `long_term` via tab-like buttons and re-fetch. Simple but correctly implemented.

### Architecture reference
- Client/server split for OAuth: keeping the client stateless (tokens only in memory + URL hash) while the server holds refresh tokens is the correct security model for SPAs.
- Component-per-resource pattern: one component per Spotify API resource type (Artist, Track, Playlist, etc.) with a container component per page.

### Reference-only
- `client/src/components/Loader.js` — Simple CSS loading spinner
- `client/src/components/ScrollToTop.js` — React Router scroll restoration
- `client/public/` — App icons and manifest

## Evaluation
**Problem solved**: Visualizes a user's Spotify listening history — top tracks, top artists, recently played, playlists, and audio feature analysis — with full OAuth integration.
**Original value**: Medium — the Spotify API integration is real and complete. The audio feature radar chart is a clever use of Spotify's analysis data. The OAuth backend pattern is the correct reference for any Spotify integration.
**Future project types**: Spotify integration in any app, OAuth integration patterns, music data visualization, API-proxy backend pattern.
**Do not copy**: The Heroku deployment is no longer the standard (Heroku removed free tier). The CRA setup is dated. The server-side token storage in memory (not Redis/database) means tokens are lost on server restart — fine for personal tools, not for production.
**Risks**: ISC license is fine. Last push 2023. Some dependencies are dated (React 16, CRA). Spotify API terms of service restrict commercial use of their data.

## Scores (1–5)
| Dimension | Score |
|-----------|-------|
| Technical quality | 3 |
| Originality | 3 |
| General usefulness | 4 |
| Architecture | 3 |
| Design and UX | 4 |
| Accessibility | 2 |
| Performance | 3 |
| Testing | 1 |
| Documentation | 3 |
| Maintenance health | 2 |
| Licensing clarity | 3 |
| Long-term lab value | 3 |

**Priority**: medium
**Action**: clone
