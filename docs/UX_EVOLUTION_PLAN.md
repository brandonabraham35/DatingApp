# Product Evolution Architecture

## Current architecture

- **Backend:** Laravel 13 controllers, Eloquent models and API resources. Sanctum
  authenticates the mobile web client; Cashier protects paid messaging; Reverb
  broadcasts incoming messages.
- **Data flow:** the Blade shell is enhanced by Vite/Tailwind client code. It calls
  `/api/profile`, `/api/discover`, `/api/matches` and `/api/messages` directly,
  retaining the bearer token in local storage.
- **Domain boundaries:** `User`, `UserMatch`, and `Message` are the core data
  models. `UserResource` and `MessageResource` keep public API projections safe.
- **Navigation:** a three-tab, single-page mobile shell handles discovery, chat and
  profile editing. Discovery is already paginated; chat is realtime through Echo.

## Compatibility rules

The original routes, authentication token flow, Cashier middleware, Reverb channel,
database columns and response shapes remain in place. Additive fields are allowed
only where they make an existing endpoint more useful. UI enhancements always use
the existing API operations rather than inventing client-only business state.

## Delivered evolution work

1. **Design system and motion:** shared spacing, motion, skeleton, focus and
   reduced-motion primitives.
2. **Discovery:** loading skeletons, image prefetching, keyboard and swipe support,
   haptics, onboarding, retry/offline behavior, richer ranking and pagination.
3. **Connections and chat:** safe counterpart projections, restored known mutuals,
   debounced search/history, realtime feedback, drafts, optimistic sending and read
   receipts.
4. **Profile:** richer header, completion feedback, photo treatment, and editing of
   every profile field already accepted by the API.

## Deferred data-model capabilities

Cross-device mutual-match restoration, server-side preference learning, distance
ranking and multi-photo galleries require durable domain fields or upstream services
that do not exist in the current schema. They should be introduced through explicit
migrations and API contracts rather than inferred from a one-sided match record.
