# PokerOps Schema Draft – v0.3 (2026-02-21)

**Status:** Updated draft for review (authored 2026-02-21 09:10–09:55 UTC)

This document summarizes the relational model for the PokerOps MVP per the initial spec plus today’s clarifications. See `pokerops-schema-v0.3.sql` in the same folder for the latest DDL.

---

## Entity Map

| Domain | Tables |
| --- | --- |
| Geo Reference | `igp_states`, `igp_locations` (geo marketing areas), `igp_venues` (physical clubs) |
| Identity & Auth | `igp_users`, `igp_user_otps`, `igp_audit_logs` |
| Landing Pages & Campaigns | `igp_landing_pages`, `igp_campaigns`, `igp_campaign_states`, `igp_campaign_templates` |
| Acquisition Tracking | `igp_player_signups`, `igp_utm_logs`, `igp_whatsapp_logs` |
| Player CRM | `igp_players`, `igp_player_attributes`, `igp_player_notes` |
| Community Ops | `igp_communities`, `igp_community_invites` |
| On-ground Ops | `igp_player_checkins`, `igp_tables`, `igp_tournaments`, `igp_tournament_registrations` |
| Compliance & Consent | `igp_consent_logs`, `igp_opt_outs`, `igp_settings` |
| Reporting | `igp_daily_metrics` |

Notes:
- Canonical India states/UTs live in `igp_states` (seed script: `state_seed.sql`). Locations reference a state; venues reference both a state and an optional location (for campaign visibility/reporting). Players, campaigns, communities, signups, and metrics now store `state_id` FKs instead of free text.
- Landing pages now split into templates (`igp_lp_templates`) and page instances (`igp_landing_pages`). Block-level content lives in `igp_lp_template_blocks` + `igp_landing_page_blocks`, and per-campaign tracking snippets sit in `igp_landing_page_tracking`. Admins edit structured content JSON rather than raw HTML.
- `igp_player_signups` captures raw landing-page submissions (with UTMs + landing page context). Application code upserts into `igp_players` (unique by phone) and logs consent deltas.
- `igp_campaign_states` enumerates state eligibility, while `igp_campaign_templates` ties the campaign to WhatsApp template + default community.
- `igp_venues` represent physical poker clubs. Player check-ins and tournaments attach to venues (tables are optional children of a venue for seat management/waitlists).
- `igp_daily_metrics` can roll up globally, per state, or per location; metadata JSON stores aggregation hints.

---

## Key Workflows (v0.2 highlights)

### OTP Login
Unchanged: OTP rows in `igp_user_otps`, audit trail in `igp_audit_logs`.

### Landing Page → Player
1. Submission stored in `igp_player_signups` (with UTMs + `state_id`).
2. Upsert into `igp_players` (phone = unique). Players can belong to any assigned marketing location but are free to play at any venue later.
3. Consent flags recorded + `igp_consent_logs` (JSON evidence + timestamps).

### WhatsApp Automation
Campaign config selects default template/community; every message logged in `igp_whatsapp_logs` with provider metadata. Works whether we use AiSensy/Interakt/etc. or an in-house sender.

### Community Invite Flow
`igp_community_invites` tracks lifecycle (`pending`→`sent`→`joined`→`left/expired`). Re-invites reference the superseded row so we retain history.

### Venue & Check-in Flow
- `igp_player_checkins` now references `venue_id` (required) and optional `table_id`. The record captures check-in/checkout timestamps, duration, creator/updater, and session notes.
- `igp_tables` are scoped to a single venue for table interest/waitlist workflows but are not required for check-ins.
- Players can register in any location, then play or check in at any venue regardless of signup state.

### Tournaments
- `igp_tournaments` attach to a venue (not a location). Player registrations live in `igp_tournament_registrations` with statuses (`registered`, `seated`, `eliminated`, `won`, `refunded`, `no_show`). No separate check-in dependency for tournaments.

### Compliance & Consent
- `igp_consent_logs` keep JSON evidence + timestamps; `igp_opt_outs` ensures outbound channels respect opt-outs. `igp_audit_logs` tracks admin operations.

### Reporting
`igp_daily_metrics` supports `scope = global/state/location` with FK-backed state/location columns so dashboards can roll up consistently.

---

## Open Questions / Next Inputs
1. **State seed data:** I’ll preload the table with all India states/UTs ordered by ISO. Let me know if you prefer a custom list or additional columns (e.g., short labels for WhatsApp templates).
2. **Venue metadata:** Do we need extra fields now (GST number, licence IDs, capacity) or keep it lean until after MVP?
3. **WhatsApp provider fields:** Still provider-agnostic; if we standardize on AiSensy/Interakt/etc., I’ll add their specific IDs/indexes for faster query patterns.
4. **Future geo structures:** Locations currently represent marketing catchments (e.g., “Chandigarh Area”). If we later need nested hierarchies (city → micro-market), we can extend `igp_locations` with parent IDs.

Everything else from yesterday’s open list is now addressed in this revision. Annotate this doc or ping me with tweaks before we lock the schema and start scaffolding the app. 