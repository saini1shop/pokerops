-- pokerops-schema-v0.3.sql
-- Draft DDL for PokerOps MVP (2026-02-21)
-- NOTE: Uses utf8mb4 + InnoDB by default. Run `SET sql_mode='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';`

CREATE DATABASE IF NOT EXISTS pokerops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pokerops;

-- ------------------------------------------------------------
-- Lookup tables
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_states (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    iso_code        VARCHAR(10) NOT NULL,
    status          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    sort_order      INT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_states_name (name),
    UNIQUE KEY uq_states_iso (iso_code)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_locations (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    state_id        INT UNSIGNED NOT NULL,
    city            VARCHAR(120) DEFAULT NULL,
    timezone        VARCHAR(64) DEFAULT 'Asia/Kolkata',
    status          ENUM('active','inactive') DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_locations_name_state (name, state_id),
    CONSTRAINT fk_locations_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_venues (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(180) NOT NULL,
    state_id        INT UNSIGNED NOT NULL,
    location_id     BIGINT UNSIGNED DEFAULT NULL,
    address_line1   VARCHAR(200) DEFAULT NULL,
    address_line2   VARCHAR(200) DEFAULT NULL,
    city            VARCHAR(120) DEFAULT NULL,
    pin_code        VARCHAR(20) DEFAULT NULL,
    status          ENUM('active','inactive') DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_venues_name_state (name, state_id),
    CONSTRAINT fk_venues_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_venues_location FOREIGN KEY (location_id) REFERENCES igp_locations(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_settings (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key`           VARCHAR(190) NOT NULL,
    value           JSON NOT NULL,
    description     VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_settings_key (`key`)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Admin users & OTP login
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(120) NOT NULL,
    email           VARCHAR(190) DEFAULT NULL,
    phone           VARCHAR(20) NOT NULL,
    role            ENUM('super_admin','hq_admin','branch_admin','staff') NOT NULL DEFAULT 'staff',
    location_id     BIGINT UNSIGNED DEFAULT NULL,
    status          ENUM('active','suspended') DEFAULT 'active',
    last_login_at   DATETIME DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_phone (phone),
    UNIQUE KEY uq_users_email (email),
    CONSTRAINT fk_users_location FOREIGN KEY (location_id) REFERENCES igp_locations(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_user_otps (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED DEFAULT NULL,
    channel         ENUM('sms','whatsapp','email') NOT NULL,
    otp_hash        CHAR(64) NOT NULL,
    expires_at      DATETIME NOT NULL,
    consumed_at     DATETIME DEFAULT NULL,
    attempt_count   TINYINT UNSIGNED NOT NULL DEFAULT 0,
    metadata        JSON DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_otps_user (user_id),
    INDEX idx_user_otps_expires (expires_at),
    CONSTRAINT fk_user_otps_user FOREIGN KEY (user_id) REFERENCES igp_users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Audit log for admin actions
CREATE TABLE IF NOT EXISTS igp_audit_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED DEFAULT NULL,
    action          VARCHAR(120) NOT NULL,
    entity_type     VARCHAR(120) NOT NULL,
    entity_id       BIGINT UNSIGNED DEFAULT NULL,
    description     VARCHAR(255) DEFAULT NULL,
    payload         JSON DEFAULT NULL,
    ip_address      VARBINARY(16) DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_user (user_id),
    INDEX idx_audit_entity (entity_type, entity_id),
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES igp_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_lp_templates (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(160) NOT NULL,
    slug            VARCHAR(160) NOT NULL,
    description     TEXT,
    preview_url     VARCHAR(255) DEFAULT NULL,
    default_fields  JSON NOT NULL,
    content_schema  JSON NOT NULL,
    status          ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
    version         INT UNSIGNED NOT NULL DEFAULT 1,
    created_by      BIGINT UNSIGNED DEFAULT NULL,
    updated_by      BIGINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_lp_templates_slug (slug),
    CONSTRAINT fk_lp_templates_created FOREIGN KEY (created_by) REFERENCES igp_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_lp_templates_updated FOREIGN KEY (updated_by) REFERENCES igp_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_lp_template_blocks (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_id     BIGINT UNSIGNED NOT NULL,
    block_key       VARCHAR(120) NOT NULL,
    block_type      VARCHAR(80) NOT NULL,
    schema_def      JSON NOT NULL,
    sort_order      INT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_template_block (template_id, block_key),
    CONSTRAINT fk_template_blocks_template FOREIGN KEY (template_id) REFERENCES igp_lp_templates(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Landing pages, campaigns & attribution
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_landing_pages (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(160) NOT NULL,
    template_id     BIGINT UNSIGNED NOT NULL,
    title           VARCHAR(200) NOT NULL,
    description     TEXT,
    content         JSON NOT NULL,
    fields_config   JSON NOT NULL,
    tracking_code   TEXT,
    status          ENUM('draft','review','published','sunset') DEFAULT 'draft',
    published_at    DATETIME DEFAULT NULL,
    last_published_by BIGINT UNSIGNED DEFAULT NULL,
    created_by      BIGINT UNSIGNED DEFAULT NULL,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_landing_slug (slug),
    CONSTRAINT fk_landing_template FOREIGN KEY (template_id) REFERENCES igp_lp_templates(id)
        ON DELETE RESTRICT,
    CONSTRAINT fk_landing_created_by FOREIGN KEY (created_by) REFERENCES igp_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_landing_published_by FOREIGN KEY (last_published_by) REFERENCES igp_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_landing_page_blocks (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    landing_page_id BIGINT UNSIGNED NOT NULL,
    block_key       VARCHAR(120) NOT NULL,
    block_type      VARCHAR(80) NOT NULL,
    content         JSON NOT NULL,
    sort_order      INT UNSIGNED NOT NULL DEFAULT 0,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_landing_block (landing_page_id, block_key),
    INDEX idx_landing_block_sort (landing_page_id, sort_order),
    CONSTRAINT fk_lp_blocks_landing FOREIGN KEY (landing_page_id) REFERENCES igp_landing_pages(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_campaigns (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(180) NOT NULL,
    platform        ENUM('meta','google','other') DEFAULT 'meta',
    landing_page_id BIGINT UNSIGNED NOT NULL,
    budget          DECIMAL(12,2) DEFAULT NULL,
    start_date      DATE DEFAULT NULL,
    end_date        DATE DEFAULT NULL,
    default_state_id INT UNSIGNED DEFAULT NULL,
    status          ENUM('draft','active','paused','completed') DEFAULT 'draft',
    created_by      BIGINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_campaign_landing FOREIGN KEY (landing_page_id) REFERENCES igp_landing_pages(id)
        ON UPDATE CASCADE,
    CONSTRAINT fk_campaign_creator FOREIGN KEY (created_by) REFERENCES igp_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_campaign_default_state FOREIGN KEY (default_state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_campaign_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_campaign_states (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign_id     BIGINT UNSIGNED NOT NULL,
    state_id        INT UNSIGNED NOT NULL,
    UNIQUE KEY uq_campaign_state (campaign_id, state_id),
    CONSTRAINT fk_campaign_states_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_campaign_states_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_communities (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    state_id        INT UNSIGNED DEFAULT NULL,
    invite_link     VARCHAR(255) DEFAULT NULL,
    community_type  ENUM('geo','campaign','special') DEFAULT 'geo',
    is_active       TINYINT(1) DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_community_name (name),
    CONSTRAINT fk_communities_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_campaign_templates (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign_id         BIGINT UNSIGNED NOT NULL,
    whatsapp_template   VARCHAR(150) NOT NULL,
    community_id        BIGINT UNSIGNED DEFAULT NULL,
    followup_delay_min  INT UNSIGNED DEFAULT NULL,
    metadata            JSON DEFAULT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_campaign_templates_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_campaign_templates_comm FOREIGN KEY (community_id) REFERENCES igp_communities(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_landing_page_tracking (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    landing_page_id BIGINT UNSIGNED NOT NULL,
    campaign_id     BIGINT UNSIGNED DEFAULT NULL,
    tracking_code   TEXT NOT NULL,
    description     VARCHAR(255) DEFAULT NULL,
    created_by      BIGINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_landing_campaign_track (landing_page_id, campaign_id),
    CONSTRAINT fk_lptracking_landing FOREIGN KEY (landing_page_id) REFERENCES igp_landing_pages(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_lptracking_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_lptracking_user FOREIGN KEY (created_by) REFERENCES igp_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Player acquisition & CRM
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_players (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(150) NOT NULL,
    phone               VARCHAR(20) NOT NULL,
    email               VARCHAR(190) DEFAULT NULL,
    state_id            INT UNSIGNED DEFAULT NULL,
    city                VARCHAR(120) DEFAULT NULL,
    whatsapp_consent    TINYINT(1) DEFAULT 0,
    marketing_consent   TINYINT(1) DEFAULT 0,
    last_consent_at     DATETIME DEFAULT NULL,
    assigned_location_id BIGINT UNSIGNED DEFAULT NULL,
    notes               TEXT,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_players_phone (phone),
    INDEX idx_players_state (state_id),
    CONSTRAINT fk_players_location FOREIGN KEY (assigned_location_id) REFERENCES igp_locations(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_players_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_player_signups (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id           BIGINT UNSIGNED DEFAULT NULL,
    landing_page_id     BIGINT UNSIGNED NOT NULL,
    campaign_id         BIGINT UNSIGNED DEFAULT NULL,
    name                VARCHAR(150) NOT NULL,
    phone               VARCHAR(20) NOT NULL,
    email               VARCHAR(190) DEFAULT NULL,
    state_id            INT UNSIGNED DEFAULT NULL,
    whatsapp_consent    TINYINT(1) DEFAULT 0,
    marketing_consent   TINYINT(1) DEFAULT 0,
    utm_source          VARCHAR(120) DEFAULT NULL,
    utm_medium          VARCHAR(120) DEFAULT NULL,
    utm_campaign        VARCHAR(120) DEFAULT NULL,
    utm_content         VARCHAR(120) DEFAULT NULL,
    utm_term            VARCHAR(120) DEFAULT NULL,
    ip_address          VARBINARY(16) DEFAULT NULL,
    user_agent          VARCHAR(255) DEFAULT NULL,
    submitted_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_player_signups_player FOREIGN KEY (player_id) REFERENCES igp_players(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_player_signups_landing FOREIGN KEY (landing_page_id) REFERENCES igp_landing_pages(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_player_signups_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_player_signups_state FOREIGN KEY (state_id) REFERENCES igp_states(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_signups_campaign (campaign_id),
    INDEX idx_signups_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_utm_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    signup_id       BIGINT UNSIGNED DEFAULT NULL,
    player_id       BIGINT UNSIGNED DEFAULT NULL,
    utm_source      VARCHAR(120) DEFAULT NULL,
    utm_medium      VARCHAR(120) DEFAULT NULL,
    utm_campaign    VARCHAR(120) DEFAULT NULL,
    utm_content     VARCHAR(120) DEFAULT NULL,
    utm_term        VARCHAR(120) DEFAULT NULL,
    raw_query       VARCHAR(500) DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_utm_signup FOREIGN KEY (signup_id) REFERENCES igp_player_signups(id) ON DELETE SET NULL,
    CONSTRAINT fk_utm_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE SET NULL,
    INDEX idx_utm_player (player_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_player_notes (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id       BIGINT UNSIGNED NOT NULL,
    user_id         BIGINT UNSIGNED DEFAULT NULL,
    note            TEXT NOT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_player_notes_player FOREIGN KEY (player_id) REFERENCES igp_players(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_player_notes_user FOREIGN KEY (user_id) REFERENCES igp_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_player_attributes (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id       BIGINT UNSIGNED NOT NULL,
    attr_key        VARCHAR(120) NOT NULL,
    attr_value      VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_player_attr (player_id, attr_key),
    CONSTRAINT fk_player_attr_player FOREIGN KEY (player_id) REFERENCES igp_players(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- WhatsApp & community
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_whatsapp_logs (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id           BIGINT UNSIGNED NOT NULL,
    campaign_id         BIGINT UNSIGNED DEFAULT NULL,
    template_name       VARCHAR(150) DEFAULT NULL,
    provider            VARCHAR(80) NOT NULL,
    provider_message_id VARCHAR(150) DEFAULT NULL,
    direction           ENUM('outbound','inbound') DEFAULT 'outbound',
    status              ENUM('queued','sent','delivered','failed','read') DEFAULT 'queued',
    payload             JSON DEFAULT NULL,
    error_code          VARCHAR(64) DEFAULT NULL,
    error_message       VARCHAR(255) DEFAULT NULL,
    sent_at             DATETIME DEFAULT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_whatsapp_logs_player FOREIGN KEY (player_id) REFERENCES igp_players(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_whatsapp_logs_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id)
        ON DELETE SET NULL,
    INDEX idx_whatsapp_status (status),
    INDEX idx_whatsapp_template (template_name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_community_invites (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id           BIGINT UNSIGNED NOT NULL,
    community_id        BIGINT UNSIGNED NOT NULL,
    campaign_id         BIGINT UNSIGNED DEFAULT NULL,
    status              ENUM('pending','sent','joined','left','expired') DEFAULT 'pending',
    invite_link_sent_at DATETIME DEFAULT NULL,
    joined_at           DATETIME DEFAULT NULL,
    left_at             DATETIME DEFAULT NULL,
    supersedes_invite_id BIGINT UNSIGNED DEFAULT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_invites_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE CASCADE,
    CONSTRAINT fk_invites_community FOREIGN KEY (community_id) REFERENCES igp_communities(id) ON DELETE CASCADE,
    CONSTRAINT fk_invites_campaign FOREIGN KEY (campaign_id) REFERENCES igp_campaigns(id) ON DELETE SET NULL,
    CONSTRAINT fk_invites_supersedes FOREIGN KEY (supersedes_invite_id) REFERENCES igp_community_invites(id) ON DELETE SET NULL,
    UNIQUE KEY uq_invite_player_comm_active (player_id, community_id, status)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Check-ins & tournaments
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_tables (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id        BIGINT UNSIGNED NOT NULL,
    name            VARCHAR(120) NOT NULL,
    max_players     TINYINT UNSIGNED DEFAULT 9,
    status          ENUM('active','inactive') DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_table_venue_name (venue_id, name),
    CONSTRAINT fk_tables_venue FOREIGN KEY (venue_id) REFERENCES igp_venues(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_player_checkins (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id       BIGINT UNSIGNED NOT NULL,
    venue_id        BIGINT UNSIGNED NOT NULL,
    table_id        BIGINT UNSIGNED DEFAULT NULL,
    checkin_time    DATETIME NOT NULL,
    checkout_time   DATETIME DEFAULT NULL,
    session_minutes INT UNSIGNED DEFAULT NULL,
    status          ENUM('checked_in','checked_out','no_show') DEFAULT 'checked_in',
    notes           TEXT,
    created_by      BIGINT UNSIGNED DEFAULT NULL,
    updated_by      BIGINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_checkins_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE CASCADE,
    CONSTRAINT fk_checkins_venue FOREIGN KEY (venue_id) REFERENCES igp_venues(id) ON DELETE CASCADE,
    CONSTRAINT fk_checkins_table FOREIGN KEY (table_id) REFERENCES igp_tables(id) ON DELETE SET NULL,
    CONSTRAINT fk_checkins_created_by FOREIGN KEY (created_by) REFERENCES igp_users(id) ON DELETE SET NULL,
    CONSTRAINT fk_checkins_updated_by FOREIGN KEY (updated_by) REFERENCES igp_users(id) ON DELETE SET NULL,
    INDEX idx_checkins_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_tournaments (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id        BIGINT UNSIGNED NOT NULL,
    name            VARCHAR(160) NOT NULL,
    start_time      DATETIME NOT NULL,
    buyin_amount    DECIMAL(10,2) DEFAULT NULL,
    status          ENUM('scheduled','running','completed','cancelled') DEFAULT 'scheduled',
    metadata        JSON DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_tournaments_venue FOREIGN KEY (venue_id) REFERENCES igp_venues(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_tournament_registrations (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tournament_id   BIGINT UNSIGNED NOT NULL,
    player_id       BIGINT UNSIGNED NOT NULL,
    status          ENUM('registered','seated','eliminated','won','refunded','no_show') DEFAULT 'registered',
    buyin_amount    DECIMAL(10,2) DEFAULT NULL,
    registered_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_treg_tournament FOREIGN KEY (tournament_id) REFERENCES igp_tournaments(id) ON DELETE CASCADE,
    CONSTRAINT fk_treg_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE CASCADE,
    UNIQUE KEY uq_treg_tournament_player (tournament_id, player_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Consent & compliance
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_consent_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id       BIGINT UNSIGNED NOT NULL,
    consent_type    ENUM('whatsapp','marketing') NOT NULL,
    previous_value  TINYINT(1) DEFAULT NULL,
    new_value       TINYINT(1) NOT NULL,
    source          ENUM('landing_page','admin','import','community') DEFAULT 'landing_page',
    evidence        JSON DEFAULT NULL,
    ip_address      VARBINARY(16) DEFAULT NULL,
    performed_by    BIGINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_consent_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE CASCADE,
    CONSTRAINT fk_consent_user FOREIGN KEY (performed_by) REFERENCES igp_users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS igp_opt_outs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    player_id       BIGINT UNSIGNED NOT NULL,
    channel         ENUM('whatsapp','email','sms') NOT NULL,
    reason          VARCHAR(255) DEFAULT NULL,
    source          ENUM('system','manual','provider') DEFAULT 'system',
    opted_out_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cleared_at      DATETIME DEFAULT NULL,
    CONSTRAINT fk_optouts_player FOREIGN KEY (player_id) REFERENCES igp_players(id) ON DELETE CASCADE,
    UNIQUE KEY uq_optouts_player_channel (player_id, channel)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Daily metrics / reporting
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS igp_daily_metrics (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    metric_date     DATE NOT NULL,
    scope           ENUM('global','state','location') NOT NULL DEFAULT 'global',
    state_id        INT UNSIGNED DEFAULT NULL,
    location_id     BIGINT UNSIGNED DEFAULT NULL,
    state_key       INT UNSIGNED GENERATED ALWAYS AS (IFNULL(state_id,0)) STORED,
    location_key    BIGINT UNSIGNED GENERATED ALWAYS AS (IFNULL(location_id,0)) STORED,
    metric_key      VARCHAR(80) NOT NULL,
    metric_value    BIGINT NOT NULL DEFAULT 0,
    metadata        JSON DEFAULT NULL,
    UNIQUE KEY uq_metrics (metric_date, metric_key, scope, state_key, location_key)
) ENGINE=InnoDB;
