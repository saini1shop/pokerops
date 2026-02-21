# PokerOps.in – Vertical Poker CRM + Engagement OS (Updated 2026-02-21)

## Context
PokerOps.in is a vertical CRM + marketing + WhatsApp engagement system built for physical poker clubs. We own the domain, the stack, and the automation—in partnership with agencies that keep running Meta/Google ads. The mission is to convert paid/organic attention into real-world poker activity:

Instagram/Ads → Landing Page → WhatsApp → Geo Community → Club visit.

We own the CRM, dashboards, WhatsApp automation, attribution, player lifecycle tracking, communities logic, compliance, and infra. Agencies own media buying. WhatsApp delivery is via API providers (AiSensy / Interakt / Twilio / Gupshup) or a future in-house sender.

### Stack
- PHP backend (plain PHP + light framework utilities)
- MySQL 8.0
- Tailwind-based frontend (mobile-first)
- WhatsApp API provider (flexible)
- OTP-only admin auth (no passwords)

---

## Core Modules

### 1. Landing Pages & Campaign Attribution
- Promotions-based landing pages (reusable across states).
- Templates drive structure; admins can add/reorder blocks (hero, offers, FAQs); JSON content per block.
- Forms capture: name, phone (primary), email (optional), state, WhatsApp consent (+ optional marketing consent).
- UTMs captured: source, medium, campaign, content, term.
- Per-page and per-campaign tracking snippets (Meta pixel, GTM, etc.).
- Landing page submissions write into `igp_player_signups` and then upsert canonical players.

### 2. Campaign Model
- Campaigns mapped manually inside admin for attribution/reporting.
- Each campaign references a landing page + default WhatsApp template + community.
- Campaigns may target multiple states (via `igp_campaign_states`).
- Campaigns are attribution objects, not communities.

### 3. Player Lifecycle
- Players keyed by phone; can have multiple signups, campaigns, communities, check-ins, tournaments.
- Fields: name, phone, email, state, city, whatsapp consent, marketing consent, notes, assigned location.
- Player CRM includes attributes, notes, audit trails, consent logs, opt-outs.
- Players can register in any location and play at any venue, regardless of signup state.

### 4. WhatsApp Strategy
- **WhatsApp API:** for automated 1:1 sends (lead nurturing, tournaments, promos, community invites). Logged in admin.
- **Communities (manual):** geo-based groups managed by humans inside WhatsApp. System only tracks invites + join status.
- Community flow: player signup → consent/state check → send invite link → player joins → admin records joined.

Primary community model: geo (Punjab, Haryana, etc.), with optional campaign-specific groups for events.

### 5. Admin Authentication
- OTP-only login (phone or email). No passwords.
- Tables: `igp_users`, `igp_user_otps`, `igp_audit_logs`.
- Roles: `super_admin`, `hq_admin`, `branch_admin`, `staff` with location visibility controls.

### 6. Player Operations
- Manual check-in / checkout at venues (capture session duration, status, table optional, notes, staff user).
- Table interest / waitlist tracking via `igp_tables`.
- Tournament registrations (no separate check-in needed; statuses cover registered → seated → eliminated/won/etc.).

### 7. Dashboards
- Mobile-first admin dashboard showing:
  - Signups by state/campaign
  - WhatsApp messages sent
  - Community invite stats
  - Venue check-ins
  - Tournament activity
  - Daily metrics rollup
- No WhatsApp community message analytics (communities are manual-only).

### 8. Compliance & Consent
- Explicit WhatsApp + marketing consent (boolean flags + `igp_consent_logs`).
- Consent evidence stored as JSON; timestamped, with source and IP.
- Opt-out handling per channel (`igp_opt_outs`).
- Audit logs for admin actions (table, record, payload snapshot).

### 9. Database (All tables prefixed `igp_`)
- Geo reference: `igp_states`, `igp_locations` (marketing areas), `igp_venues` (physical clubs).
- Auth/identity: `igp_users`, `igp_user_otps`, `igp_audit_logs`.
- Landing pages/campaigns: `igp_lp_templates`, `igp_lp_template_blocks`, `igp_landing_pages`, `igp_landing_page_blocks`, `igp_landing_page_tracking`, `igp_campaigns`, `igp_campaign_states`, `igp_campaign_templates`.
- Acquisition/logs: `igp_player_signups`, `igp_utm_logs`, `igp_whatsapp_logs`.
- Player CRM: `igp_players`, `igp_player_attributes`, `igp_player_notes`.
- Communities: `igp_communities`, `igp_community_invites`.
- On-ground ops: `igp_tables`, `igp_player_checkins`, `igp_tournaments`, `igp_tournament_registrations`.
- Compliance/metrics: `igp_consent_logs`, `igp_opt_outs`, `igp_settings`, `igp_daily_metrics`.
- Leaderboards intentionally excluded (future luxury).

Seed scripts and schema are stored under `docs/schema/` and mirrored in S3.

---

## MVP Scope (✅) vs Future (❌)

**Included in MVP:**
- Campaign tracking & attribution
- Landing page templates + UTMs
- Player CRM & consent logging
- WhatsApp automation logging
- Community invite workflow
- Geo communities + venue tracking
- Admin dashboards (mobile-first)
- Player check-in/checkout workflows
- Tournament registrations
- OTP admin auth + audit trail

**Deferred / Luxury:**
- Leaderboards
- Loyalty programs / tiering
- Advanced segmentation / investor dashboards
- AI scoring, predictive analytics
- In-depth WhatsApp community message analytics

---

## Design Tenets
- **Mobile-first:** admins and players live on their phones. All interfaces must degrade gracefully on small screens.
- **Fast & focused:** no clutter, minimal JS, lightweight charts—speed > flash.
- **Opinionated workflow:** we optimize for real-world poker club ops (check-ins, tournaments, communities) rather than generic CRM features.
- **Self-contained stack:** PHP/MySQL, clear schema, no heavy frameworks so the system remains easy to deploy/manage.

Instagram brings attention. Landing pages capture intent. WhatsApp builds connection. Communities build loyalty. Clubs monetize. PokerOps.in is the operating system for that loop.
