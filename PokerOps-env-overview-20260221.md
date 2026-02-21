# PokerOps Environment & Tech Overview (2026-02-21)

## Summary
PokerOps.in is a vertical CRM + WhatsApp engagement OS for physical poker clubs. This doc gives a quick orientation for new contributors or any third-party AI/agent so they can understand the environment, languages, database, and deployment context without digging through multiple files.

---

## Infrastructure
- **Primary domain:** pokerops.in
- **Server:** Ubuntu 22.04 (EC2 in ap-south-1). Root SSH via `ubuntu@13.232.57.254` (key: `openclaw-dev.pem`, stored in S3 root).
- **Web server:** Nginx serving the coming-soon page + `/dev/` docs mirror. Admin app will run under `/var/www/pokerops-admin/` (in progress).
- **Deployment approach:** plain PHP app, git-less (manual). Future automation TBD.

## Tech stack
- **Backend:** PHP (8.x) without heavy frameworks. We’re building our own lightweight router + templating to stay fast and flexible.
- **Frontend:** Tailwind CSS + minimal Alpine/HTMX-style sprinkles. All pages are mobile-first, dark theme.
- **Database:** MySQL 8.0.
- **WhatsApp provider:** Flexible; plan to support AiSensy / Interakt / Twilio / Gupshup. Logs stored in `igp_whatsapp_logs` regardless of provider.
- **Auth:** OTP-only admin login (phone/email). No passwords.

## Database
- **Schema version:** v0.3 (21 Feb 2026). Files in `docs/schema/` + mirrors in S3 (`pokerops/schema/20260221/`).
- **Key tables:** `igp_states`, `igp_locations`, `igp_venues`, `igp_lp_templates`, `igp_landing_pages`, `igp_campaigns`, `igp_players`, `igp_player_checkins`, `igp_tournaments`, `igp_communities`, `igp_whatsapp_logs`, `igp_consent_logs`, `igp_daily_metrics`, etc. See README.md in the same folder.
- **Seed data:** `state_seed.sql` (36 states/UTs) already imported.
- **Server connection:** MySQL runs locally on the EC2 host. App user `pokerops_app`@`localhost`, password `PkrOps!2026#Admin`. Credentials stored in S3 (`mysql-pokerops-prod-20260221.txt`).

## Repos & files
- No git repo deployed yet on the server. Working directory on OpenClaw workspace: `/home/ubuntu/.openclaw/workspace/` with docs, schema files, keys, etc.
- `docs/pokerops-initial-spec.md`: product spec (updated after every discussion).
- `docs/schema/`: schema SQL + README + seeds.
- `/var/www/pokerops.in/dev/`: live mirror of schema files for quick download (README, SQL, seeds).

## Pending work (as of pause)
- Admin app (login/dashboard) scaffolding was in progress but not yet deployed. Next steps when project resumes: push PHP app to server, wire dummy OTP, build dashboard shell.

## Operational notes
- WhatsApp gateway for the existing environment is connected via OpenClaw. When coding features that touch the gateway, use the existing provider config.
- Keep new code mobile-first, fast-loading. Avoid unnecessary JS or heavy dependencies.

---

This document complements `pokerops-initial-spec.md` and `docs/schema/README.md`. Update it whenever infra/app assumptions change so new contributors can spin up quickly.