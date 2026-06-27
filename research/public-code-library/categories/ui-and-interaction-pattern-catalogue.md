# UI and Interaction Pattern Catalogue
**Public Code Research Library — lab/research/public-code-library**  
Generated: 2026-06-27

Items that inform interaction design, UX patterns, animation approaches, and interface behavior worth referencing for future projects.

---

## Notification Patterns

### Stacked Toasts with Expand-on-Hover
**Source**: emilkowalski/sonner  
**Pattern**: Toasts stack visually (showing partial edges of hidden toasts). On hover, stack expands to show all toasts in full. Swipe-to-dismiss with velocity threshold. Auto-dismiss with pauseable timer (exact remaining time preserved on hover).  
**Key behaviors**:
- Stack collapse uses CSS `--index` and `--offset` custom properties
- Expand triggered by `mouseenter` on the container, not individual items
- Timer pause on hover preserves remaining time exactly (not restart)
- Velocity-based dismiss: `abs(swipeAmount) >= 45px OR velocity > 0.11px/ms`

### Promise Toast Pattern
**Source**: emilkowalski/sonner — `src/state.ts`  
**Pattern**: `toast.promise(fetchFn, { loading, success, error })`. Toast starts in loading state, transitions to success or error when the Promise resolves/rejects. Handles both synchronous and async success/error message renderers.  
**UX value**: Users get real-time feedback on async operations without custom code for each operation.

---

## Drawer / Sheet Patterns

### Bottom Sheet with Velocity-Based Snap Points
**Source**: emilkowalski/vaul  
**Pattern**: Drawer can snap to multiple heights. Drag releases with velocity determine whether to snap up, stay, or dismiss. Natural "throw" behavior.  
**Key behaviors**:
- Snap points defined as array of fractions (0.25, 0.5, 1.0)
- Velocity determines direction: fast throw up → expand; fast throw down → dismiss
- Overscroll resistance at boundaries (rubber band feel)

---

## Command/Search Patterns

### Command Menu with Category Groups
**Source**: dip/cmdk  
**Pattern**: `<Command>`, `<Command.Group>`, `<Command.Item>`. Items filterable across all groups simultaneously. Keyboard navigation with arrow keys, Enter to select, Escape to dismiss. Empty state support.  
**Key behavior**: Filtering collapses empty groups automatically.

### Accessible Smart Search
**Source**: LeaVerou/awesomplete  
**Pattern**: Enhances any `<input>` with autocomplete. Options can come from a `list`, `data-list`, or JS array. Supports custom item render, custom filter, custom sort. Zero framework dependency.

### Match-Sorter Ranking
**Source**: kentcdodds/match-sorter  
**Pattern**: 7-tier ranking: exact → equal string → starts with → word starts with → contains → acronym → fuzzy. Items matching at higher tiers always rank above lower-tier matches, regardless of substring position.  
**UX value**: Users intuitively expect "ma" to rank "mark" above "smart" — this algorithm implements that correctly.

---

## Animation Patterns

### Pauseable Auto-Dismiss Timer
**Source**: emilkowalski/sonner — `src/index.tsx`  
**Pattern**: On hover, record `lastCloseTimerStartTimeRef` timestamp. On un-hover, start a new `setTimeout` with `remainingTime - elapsedTime`. If dismissed during pause, remaining time is exactly correct.  
**UX value**: User doesn't lose notification time when briefly hovering.

### FLIP Shared Element Transition
**Source**: antfu/vue-starport  
**Pattern**: Same component instance persists across route changes. Before the route transition, reads the element's current position. After transition, reads new position. Animates the difference using CSS transform (FLIP technique).  
**UX value**: Maintains visual context across navigation — user can follow where content went.

### CSS Loading Spinners (No JS)
**Source**: TobiasAhlin/SpinKit — `spinkit.css`  
**Pattern**: 10 CSS animation variants using only `@keyframes` on `transform` and `opacity`. No JavaScript required. Works on any element with class `sk-{variant}`.  
**Key variants**: Circle, Chase, Bounce, Wave, WanderingCubes, Pulse, Plane, Bounce, Flow, Folding.

### Physics-Informed Easing (CSS)
**Source**: tobiasahlin/bendable — `bendable.css`  
**Pattern**: 30+ named `--ease-*` CSS custom properties using `linear()` for multi-stop easings that simulate elastic, bounce, and spring physics in pure CSS.  
**UX value**: Natural-feeling animations without JavaScript animation libraries.

### Hamburger → X Morphing Icon
**Source**: tobiasahlin/animated-menu  
**Pattern**: Three-bar hamburger morphs to X: top bar translates to center + rotates 45°, middle bar fades, bottom bar translates to center + rotates -45°. All CSS transitions.  
**Note**: Code is from 2016; concept is evergreen. Modern implementation: inline SVG `path` morphing.

### Text Scramble Effect
**Source**: MaximeHeckel/blog.maximeheckel.com — `core/components/ScrambledText.tsx`  
**Pattern**: On mount or value change, cycle through random characters at each position before settling on final value. Creates a decrypting/revealing feeling.  
**UX value**: Adds perceived complexity to text reveals; good for hero sections or loading states.

### Cursor Magnetic Dock
**Source**: MaximeHeckel/blog.maximeheckel.com — `core/components/Dock.tsx`  
**Pattern**: macOS-style dock where items enlarge as cursor approaches. Uses `mousemove` event to calculate cursor proximity to each dock item, then maps distance to a scale transform.  
**Key calculation**: Scale is a Gaussian function of cursor distance — falls off naturally.

### GSAP-Style Letter Animation
**Source**: tobiasahlin/moving-letters  
**Pattern**: Splits text into individual `<span>` letters. Applies staggered CSS animations to each letter (translate, opacity, scale). Uses `animejs` library.  
**Note**: animejs is the library; the pattern (text split → staggered animation) is the value.

### CSS Spinner/Loader Variety
**Source**: jh3y/whirl — `src/`  
**Pattern**: 100+ CSS-only loading spinner animations in SCSS. Each spinner is self-contained. Reference for choosing the right loading animation aesthetic.

### SVG Path Meanderer
**Source**: jh3y/meanderer — `src/index.js`  
**Pattern**: JavaScript library that takes an SVG path and "meanders" it — adds random organic curves. Creates natural-looking wavy paths from straight lines.  
**Use in**: Decorative SVG backgrounds, path animation effects.

### Vaporwave Terrain Displacement
**Source**: MaximeHeckel/linear-vaporwave-react-three-fiber  
**Pattern**: Three.js PlaneGeometry + displacement map texture + metalness map. Creates the retro vaporwave grid terrain effect in WebGL.  
**Note**: Visual inspiration; implementation needs updating for current Three.js APIs.

---

## Form and Input Patterns

### Self-Adjusting Textarea
**Source**: LeaVerou/stretchy — `stretchy.js`  
**Pattern**: Textarea that grows in height as content grows. Works on any `<textarea>` via a `MutationObserver`. No fixed `min-height` needed.  
**UX value**: Users don't scroll inside a tiny textarea.

---

## macOS Interaction Patterns

### Multi-Pane Settings Window
**Source**: sindresorhus/Settings  
**Pattern**: NSToolbar with tab-switching. Each pane auto-sizes the window to its content with animation. Centered position maintained when height changes.  
**UX detail**: Settings windows should resize to pane content (not scroll).

### Dock Progress Indicator
**Source**: sindresorhus/DockProgress  
**Pattern**: Progress bar drawn in the macOS Dock icon. Uses NSApp's `dockTile` to overlay a progress bar on the app's icon.  
**UX value**: Users can see file conversion/download progress without switching to the app.

### Drop Zone Validation
**Source**: sindresorhus/Gifski — `Gifski/VideoValidator.swift`  
**Pattern**: File dropped on a drop zone is immediately validated for type, codec, and minimum size. Invalid files show an error tooltip instantly. Valid files proceed.  
**UX value**: Prevents confused "why isn't this working?" states.

### App Intents / Shortcuts Integration
**Source**: sindresorhus/Gifski — `Gifski/Intents.swift`  
**Pattern**: `AppIntent` conformance exposes GIF conversion as a macOS Shortcuts action. Users can automate the app.  
**UX value**: Automation-friendly macOS apps are more professional.

---

## Accessibility Patterns

### Visually Hidden Element
**Pattern**: `position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap;` — visible to screen readers, invisible to sighted users.  
**Source**: MaximeHeckel/design-system — `src/components/VisuallyHidden/`

### Keyboard Navigation in Command Menu
**Source**: dip/cmdk  
**Pattern**: Arrow keys navigate list items. `Enter` selects. `Escape` closes. All items are `role="option"` or `role="menuitem"` with `aria-selected`. Container has `role="listbox"` or `role="menu"`.

---

## Interface Philosophy References

### Interfaces Principles
**Source**: raunofreiberg/interfaces  
**Key principles documented**:
- Hover states should be subtle, not jarring
- Destructive actions should require confirmation
- Disabled states should explain why
- Form validation should not interrupt typing
- Lists of more than 5 items should be sortable
- Numbers should right-align in tables
- Error messages should be human-readable

These are behavioral design constraints, not visual guidelines. Applicable to any product interface regardless of stack.
