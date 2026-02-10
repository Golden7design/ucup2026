--
-- PostgreSQL database dump
--

\restrict GKSSFziHMqDi3arA7KQ7VVP76Wi4ZQMtO97UBRVFU4v5QcD8PaBveCbhuOhz1Do

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gallery_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gallery_items (
    id bigint NOT NULL,
    title character varying(255),
    description text,
    file_path character varying(255) NOT NULL,
    media_type character varying(255) NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT gallery_items_media_type_check CHECK (((media_type)::text = ANY ((ARRAY['image'::character varying, 'video'::character varying])::text[])))
);


ALTER TABLE public.gallery_items OWNER TO postgres;

--
-- Name: gallery_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gallery_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.gallery_items_id_seq OWNER TO postgres;

--
-- Name: gallery_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gallery_items_id_seq OWNED BY public.gallery_items.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: lineups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lineups (
    id bigint NOT NULL,
    match_id bigint NOT NULL,
    team_id bigint NOT NULL,
    player_id bigint NOT NULL,
    is_starter boolean DEFAULT false NOT NULL,
    role character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    match_position character varying(5)
);


ALTER TABLE public.lineups OWNER TO postgres;

--
-- Name: lineups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lineups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lineups_id_seq OWNER TO postgres;

--
-- Name: lineups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lineups_id_seq OWNED BY public.lineups.id;


--
-- Name: match_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.match_events (
    id bigint NOT NULL,
    match_id bigint NOT NULL,
    team_id bigint NOT NULL,
    player_id bigint NOT NULL,
    assist_player_id bigint,
    event_type character varying(255) NOT NULL,
    minute integer NOT NULL,
    additional_time character varying(255),
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    out_player_id bigint,
    CONSTRAINT match_events_event_type_check CHECK (((event_type)::text = ANY ((ARRAY['goal'::character varying, 'yellow_card'::character varying, 'red_card'::character varying, 'substitution'::character varying, 'own_goal'::character varying])::text[])))
);


ALTER TABLE public.match_events OWNER TO postgres;

--
-- Name: match_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.match_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.match_events_id_seq OWNER TO postgres;

--
-- Name: match_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.match_events_id_seq OWNED BY public.match_events.id;


--
-- Name: match_lineups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.match_lineups (
    id bigint NOT NULL,
    match_id bigint NOT NULL,
    team_id bigint NOT NULL,
    player_id bigint NOT NULL,
    role character varying(255) NOT NULL,
    starting_position character varying(255),
    order_key smallint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_starter boolean DEFAULT false NOT NULL,
    "position" character varying(10),
    CONSTRAINT match_lineups_role_check CHECK (((role)::text = ANY ((ARRAY['starter'::character varying, 'substitute'::character varying])::text[])))
);


ALTER TABLE public.match_lineups OWNER TO postgres;

--
-- Name: match_lineups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.match_lineups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.match_lineups_id_seq OWNER TO postgres;

--
-- Name: match_lineups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.match_lineups_id_seq OWNED BY public.match_lineups.id;


--
-- Name: matches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matches (
    id bigint NOT NULL,
    home_team_id bigint NOT NULL,
    away_team_id bigint NOT NULL,
    match_date timestamp(0) without time zone NOT NULL,
    venue character varying(255),
    status character varying(255) DEFAULT 'scheduled'::character varying NOT NULL,
    home_score integer DEFAULT 0 NOT NULL,
    away_score integer DEFAULT 0 NOT NULL,
    round character varying(255),
    "group" character varying(255),
    attendance integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    start_time timestamp(0) without time zone,
    home_coach character varying(255),
    away_coach character varying(255),
    home_formation character varying(10),
    away_formation character varying(10),
    home_composition_ready boolean DEFAULT false NOT NULL,
    away_composition_ready boolean DEFAULT false NOT NULL,
    match_type character varying(255) DEFAULT 'tournament'::character varying,
    home_fouls smallint DEFAULT '0'::smallint NOT NULL,
    home_corners smallint DEFAULT '0'::smallint NOT NULL,
    home_offsides smallint DEFAULT '0'::smallint NOT NULL,
    away_fouls smallint DEFAULT '0'::smallint NOT NULL,
    away_corners smallint DEFAULT '0'::smallint NOT NULL,
    away_offsides smallint DEFAULT '0'::smallint NOT NULL,
    home_yellow_cards integer DEFAULT 0 NOT NULL,
    home_red_cards integer DEFAULT 0 NOT NULL,
    away_yellow_cards integer DEFAULT 0 NOT NULL,
    away_red_cards integer DEFAULT 0 NOT NULL,
    home_possession integer,
    away_possession integer,
    home_shots integer DEFAULT 0 NOT NULL,
    away_shots integer DEFAULT 0 NOT NULL,
    home_shots_on_target integer DEFAULT 0 NOT NULL,
    away_shots_on_target integer DEFAULT 0 NOT NULL,
    home_saves integer DEFAULT 0 NOT NULL,
    away_saves integer DEFAULT 0 NOT NULL,
    home_free_kicks integer DEFAULT 0 NOT NULL,
    away_free_kicks integer DEFAULT 0 NOT NULL,
    home_throw_ins integer DEFAULT 0 NOT NULL,
    away_throw_ins integer DEFAULT 0 NOT NULL,
    home_goalkicks integer DEFAULT 0 NOT NULL,
    away_goalkicks integer DEFAULT 0 NOT NULL,
    home_penalties integer DEFAULT 0 NOT NULL,
    away_penalties integer DEFAULT 0 NOT NULL,
    referee character varying(255),
    weather character varying(255),
    temperature integer,
    humidity integer,
    timer_paused_at timestamp(0) without time zone,
    admin_notes text,
    match_report text,
    elapsed_time integer DEFAULT 0,
    additional_time_first_half integer DEFAULT 0,
    additional_time_second_half integer DEFAULT 0,
    is_extra_time boolean DEFAULT false,
    is_penalty_shootout boolean DEFAULT false,
    CONSTRAINT matches_match_type_check CHECK (((match_type)::text = ANY ((ARRAY['tournament'::character varying, 'friendly'::character varying])::text[]))),
    CONSTRAINT matches_status_check CHECK (((status)::text = ANY ((ARRAY['scheduled'::character varying, 'live'::character varying, 'halftime'::character varying, 'finished'::character varying, 'postponed'::character varying])::text[])))
);


ALTER TABLE public.matches OWNER TO postgres;

--
-- Name: matches_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.matches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.matches_id_seq OWNER TO postgres;

--
-- Name: matches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.matches_id_seq OWNED BY public.matches.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: players; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.players (
    id bigint NOT NULL,
    team_id bigint NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    jersey_number integer NOT NULL,
    "position" character varying(255) NOT NULL,
    birth_date date,
    photo character varying(255),
    nationality character varying(255) DEFAULT 'DRC'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    height integer,
    photo_path character varying(255),
    goals integer DEFAULT 0 NOT NULL,
    assists integer DEFAULT 0 NOT NULL,
    yellow_cards integer DEFAULT 0 NOT NULL,
    red_cards integer DEFAULT 0 NOT NULL,
    matches_played integer DEFAULT 0 NOT NULL,
    minutes_played integer DEFAULT 0 NOT NULL,
    passes_completed integer DEFAULT 0 NOT NULL,
    pass_accuracy integer DEFAULT 0 NOT NULL,
    tackles integer DEFAULT 0 NOT NULL,
    interceptions integer DEFAULT 0 NOT NULL,
    fouls_committed integer DEFAULT 0 NOT NULL,
    fouls_suffered integer DEFAULT 0 NOT NULL,
    shots_on_target integer DEFAULT 0 NOT NULL,
    dribbles integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.players OWNER TO postgres;

--
-- Name: players_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.players_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.players_id_seq OWNER TO postgres;

--
-- Name: players_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.players_id_seq OWNED BY public.players.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: standings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.standings (
    id bigint NOT NULL,
    team_id bigint NOT NULL,
    "group" character varying(255),
    played integer DEFAULT 0 NOT NULL,
    won integer DEFAULT 0 NOT NULL,
    drawn integer DEFAULT 0 NOT NULL,
    lost integer DEFAULT 0 NOT NULL,
    goals_for integer DEFAULT 0 NOT NULL,
    goals_against integer DEFAULT 0 NOT NULL,
    goal_difference integer DEFAULT 0 NOT NULL,
    points integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    wins smallint DEFAULT '0'::smallint NOT NULL,
    losses smallint DEFAULT '0'::smallint NOT NULL,
    draws smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.standings OWNER TO postgres;

--
-- Name: standings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.standings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.standings_id_seq OWNER TO postgres;

--
-- Name: standings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.standings_id_seq OWNED BY public.standings.id;


--
-- Name: teams; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.teams (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    coach character varying(255),
    category character varying(255) DEFAULT 'senior'::character varying NOT NULL,
    year integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.teams OWNER TO postgres;

--
-- Name: teams_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.teams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.teams_id_seq OWNER TO postgres;

--
-- Name: teams_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.teams_id_seq OWNED BY public.teams.id;


--
-- Name: universities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.universities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(10) NOT NULL,
    logo character varying(255),
    colors character varying(255),
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.universities OWNER TO postgres;

--
-- Name: universities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.universities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.universities_id_seq OWNER TO postgres;

--
-- Name: universities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.universities_id_seq OWNED BY public.universities.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone,
    is_admin boolean DEFAULT false NOT NULL,
    theme_preference character varying(255) DEFAULT 'system'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gallery_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery_items ALTER COLUMN id SET DEFAULT nextval('public.gallery_items_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: lineups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups ALTER COLUMN id SET DEFAULT nextval('public.lineups_id_seq'::regclass);


--
-- Name: match_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events ALTER COLUMN id SET DEFAULT nextval('public.match_events_id_seq'::regclass);


--
-- Name: match_lineups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups ALTER COLUMN id SET DEFAULT nextval('public.match_lineups_id_seq'::regclass);


--
-- Name: matches id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matches ALTER COLUMN id SET DEFAULT nextval('public.matches_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: players id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.players ALTER COLUMN id SET DEFAULT nextval('public.players_id_seq'::regclass);


--
-- Name: standings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standings ALTER COLUMN id SET DEFAULT nextval('public.standings_id_seq'::regclass);


--
-- Name: teams id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teams ALTER COLUMN id SET DEFAULT nextval('public.teams_id_seq'::regclass);


--
-- Name: universities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.universities ALTER COLUMN id SET DEFAULT nextval('public.universities_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
ucup-cache-boost.roster.scan	YToyOntzOjY6InJvc3RlciI7TzoyMToiTGFyYXZlbFxSb3N0ZXJcUm9zdGVyIjozOntzOjEzOiIAKgBhcHByb2FjaGVzIjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToxOntpOjA7TzoyMzoiTGFyYXZlbFxSb3N0ZXJcQXBwcm9hY2giOjE6e3M6MTE6IgAqAGFwcHJvYWNoIjtFOjM4OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xBcHByb2FjaGVzOkFDVElPTiI7fX1zOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7fXM6MTE6IgAqAHBhY2thZ2VzIjtPOjMyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToyMTp7aTowO086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjE7czoxMzoiACoAY29uc3RyYWludCI7czo0OiJeMy4yIjtzOjEwOiIAKgBwYWNrYWdlIjtFOjM4OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpGSUxBTUVOVCI7czoxNDoiACoAcGFja2FnZU5hbWUiO3M6MTc6ImZpbGFtZW50L2ZpbGFtZW50IjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjY6IjMuMy40NyI7czo2OiIAKgBkZXYiO2I6MDt9aToxO086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjE7czoxMzoiACoAY29uc3RyYWludCI7czo0OiJeMi4wIjtzOjEwOiIAKgBwYWNrYWdlIjtFOjM3OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpJTkVSVElBIjtzOjE0OiIAKgBwYWNrYWdlTmFtZSI7czoyNToiaW5lcnRpYWpzL2luZXJ0aWEtbGFyYXZlbCI7czoxMDoiACoAdmVyc2lvbiI7czo2OiIyLjAuMTAiO3M6NjoiACoAZGV2IjtiOjA7fWk6MjtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjoxO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NDoiXjIuMCI7czoxMDoiACoAcGFja2FnZSI7RTo0NToiTGFyYXZlbFxSb3N0ZXJcRW51bXNcUGFja2FnZXM6SU5FUlRJQV9MQVJBVkVMIjtzOjE0OiIAKgBwYWNrYWdlTmFtZSI7czoyNToiaW5lcnRpYWpzL2luZXJ0aWEtbGFyYXZlbCI7czoxMDoiACoAdmVyc2lvbiI7czo2OiIyLjAuMTAiO3M6NjoiACoAZGV2IjtiOjA7fWk6MztPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjoxO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NToiXjEuMzAiO3M6MTA6IgAqAHBhY2thZ2UiO0U6Mzc6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOkZPUlRJRlkiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE1OiJsYXJhdmVsL2ZvcnRpZnkiO3M6MTA6IgAqAHZlcnNpb24iO3M6NjoiMS4zMi4wIjtzOjY6IgAqAGRldiI7YjowO31pOjQ7TzoyMjoiTGFyYXZlbFxSb3N0ZXJcUGFja2FnZSI6Njp7czo5OiIAKgBkaXJlY3QiO2I6MTtzOjEzOiIAKgBjb25zdHJhaW50IjtzOjU6Il4xMi4wIjtzOjEwOiIAKgBwYWNrYWdlIjtFOjM3OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpMQVJBVkVMIjtzOjE0OiIAKgBwYWNrYWdlTmFtZSI7czoxNzoibGFyYXZlbC9mcmFtZXdvcmsiO3M6MTA6IgAqAHZlcnNpb24iO3M6NzoiMTIuMzkuMCI7czo2OiIAKgBkZXYiO2I6MDt9aTo1O086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjA7czoxMzoiACoAY29uc3RyYWludCI7czo2OiJ2MC4zLjciO3M6MTA6IgAqAHBhY2thZ2UiO0U6Mzc6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOlBST01QVFMiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE1OiJsYXJhdmVsL3Byb21wdHMiO3M6MTA6IgAqAHZlcnNpb24iO3M6NToiMC4zLjciO3M6NjoiACoAZGV2IjtiOjA7fWk6NjtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjoxO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NjoiXjAuMS45IjtzOjEwOiIAKgBwYWNrYWdlIjtFOjM5OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpXQVlGSU5ERVIiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE3OiJsYXJhdmVsL3dheWZpbmRlciI7czoxMDoiACoAdmVyc2lvbiI7czo2OiIwLjEuMTIiO3M6NjoiACoAZGV2IjtiOjA7fWk6NztPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjoxO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NjoiXjAuMS45IjtzOjEwOiIAKgBwYWNrYWdlIjtFOjQ3OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpXQVlGSU5ERVJfTEFSQVZFTCI7czoxNDoiACoAcGFja2FnZU5hbWUiO3M6MTc6ImxhcmF2ZWwvd2F5ZmluZGVyIjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjY6IjAuMS4xMiI7czo2OiIAKgBkZXYiO2I6MDt9aTo4O086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjA7czoxMzoiACoAY29uc3RyYWludCI7czo2OiJ2My43LjgiO3M6MTA6IgAqAHBhY2thZ2UiO0U6Mzg6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOkxJVkVXSVJFIjtzOjE0OiIAKgBwYWNrYWdlTmFtZSI7czoxNzoibGl2ZXdpcmUvbGl2ZXdpcmUiO3M6MTA6IgAqAHZlcnNpb24iO3M6NToiMy43LjgiO3M6NjoiACoAZGV2IjtiOjA7fWk6OTtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjowO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NjoidjAuMy40IjtzOjEwOiIAKgBwYWNrYWdlIjtFOjMzOiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpNQ1AiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjExOiJsYXJhdmVsL21jcCI7czoxMDoiACoAdmVyc2lvbiI7czo1OiIwLjMuNCI7czo2OiIAKgBkZXYiO2I6MTt9aToxMDtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjoxO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6NToiXjEuMjQiO3M6MTA6IgAqAHBhY2thZ2UiO0U6MzQ6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOlBJTlQiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjEyOiJsYXJhdmVsL3BpbnQiO3M6MTA6IgAqAHZlcnNpb24iO3M6NjoiMS4yNS4xIjtzOjY6IgAqAGRldiI7YjoxO31pOjExO086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjE7czoxMzoiACoAY29uc3RyYWludCI7czo1OiJeMS40MSI7czoxMDoiACoAcGFja2FnZSI7RTozNDoiTGFyYXZlbFxSb3N0ZXJcRW51bXNcUGFja2FnZXM6U0FJTCI7czoxNDoiACoAcGFja2FnZU5hbWUiO3M6MTI6ImxhcmF2ZWwvc2FpbCI7czoxMDoiACoAdmVyc2lvbiI7czo2OiIxLjQ4LjEiO3M6NjoiACoAZGV2IjtiOjE7fWk6MTI7TzoyMjoiTGFyYXZlbFxSb3N0ZXJcUGFja2FnZSI6Njp7czo5OiIAKgBkaXJlY3QiO2I6MTtzOjEzOiIAKgBjb25zdHJhaW50IjtzOjQ6Il4zLjgiO3M6MTA6IgAqAHBhY2thZ2UiO0U6MzQ6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOlBFU1QiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjEyOiJwZXN0cGhwL3Blc3QiO3M6MTA6IgAqAHZlcnNpb24iO3M6NToiMy44LjQiO3M6NjoiACoAZGV2IjtiOjE7fWk6MTM7TzoyMjoiTGFyYXZlbFxSb3N0ZXJcUGFja2FnZSI6Njp7czo5OiIAKgBkaXJlY3QiO2I6MDtzOjEzOiIAKgBjb25zdHJhaW50IjtzOjc6IjExLjUuMzMiO3M6MTA6IgAqAHBhY2thZ2UiO0U6Mzc6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOlBIUFVOSVQiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE1OiJwaHB1bml0L3BocHVuaXQiO3M6MTA6IgAqAHZlcnNpb24iO3M6NzoiMTEuNS4zMyI7czo2OiIAKgBkZXYiO2I6MTt9aToxNDtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjowO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6MDoiIjtzOjEwOiIAKgBwYWNrYWdlIjtyOjIwO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE2OiJAaW5lcnRpYWpzL3JlYWN0IjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjU6IjIuMS40IjtzOjY6IgAqAGRldiI7YjowO31pOjE1O086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjA7czoxMzoiACoAY29uc3RyYWludCI7czowOiIiO3M6MTA6IgAqAHBhY2thZ2UiO0U6NDM6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOklORVJUSUFfUkVBQ1QiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjE2OiJAaW5lcnRpYWpzL3JlYWN0IjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjU6IjIuMS40IjtzOjY6IgAqAGRldiI7YjowO31pOjE2O086MjI6IkxhcmF2ZWxcUm9zdGVyXFBhY2thZ2UiOjY6e3M6OToiACoAZGlyZWN0IjtiOjA7czoxMzoiACoAY29uc3RyYWludCI7czowOiIiO3M6MTA6IgAqAHBhY2thZ2UiO0U6MzQ6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXFBhY2thZ2VzOkVDSE8iO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjEyOiJsYXJhdmVsLWVjaG8iO3M6MTA6IgAqAHZlcnNpb24iO3M6NToiMi4yLjYiO3M6NjoiACoAZGV2IjtiOjA7fWk6MTc7TzoyMjoiTGFyYXZlbFxSb3N0ZXJcUGFja2FnZSI6Njp7czo5OiIAKgBkaXJlY3QiO2I6MDtzOjEzOiIAKgBjb25zdHJhaW50IjtzOjA6IiI7czoxMDoiACoAcGFja2FnZSI7RTozNToiTGFyYXZlbFxSb3N0ZXJcRW51bXNcUGFja2FnZXM6UkVBQ1QiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjU6InJlYWN0IjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjY6IjE5LjIuMCI7czo2OiIAKgBkZXYiO2I6MDt9aToxODtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjowO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6MDoiIjtzOjEwOiIAKgBwYWNrYWdlIjtFOjQxOiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpUQUlMV0lORENTUyI7czoxNDoiACoAcGFja2FnZU5hbWUiO3M6MTE6InRhaWx3aW5kY3NzIjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjY6IjQuMS4xMiI7czo2OiIAKgBkZXYiO2I6MDt9aToxOTtPOjIyOiJMYXJhdmVsXFJvc3RlclxQYWNrYWdlIjo2OntzOjk6IgAqAGRpcmVjdCI7YjowO3M6MTM6IgAqAGNvbnN0cmFpbnQiO3M6MDoiIjtzOjEwOiIAKgBwYWNrYWdlIjtFOjM2OiJMYXJhdmVsXFJvc3RlclxFbnVtc1xQYWNrYWdlczpFU0xJTlQiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjY6ImVzbGludCI7czoxMDoiACoAdmVyc2lvbiI7czo2OiI5LjMzLjAiO3M6NjoiACoAZGV2IjtiOjE7fWk6MjA7TzoyMjoiTGFyYXZlbFxSb3N0ZXJcUGFja2FnZSI6Njp7czo5OiIAKgBkaXJlY3QiO2I6MDtzOjEzOiIAKgBjb25zdHJhaW50IjtzOjA6IiI7czoxMDoiACoAcGFja2FnZSI7RTozODoiTGFyYXZlbFxSb3N0ZXJcRW51bXNcUGFja2FnZXM6UFJFVFRJRVIiO3M6MTQ6IgAqAHBhY2thZ2VOYW1lIjtzOjg6InByZXR0aWVyIjtzOjEwOiIAKgB2ZXJzaW9uIjtzOjU6IjMuNi4yIjtzOjY6IgAqAGRldiI7YjoxO319czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO31zOjIxOiIAKgBub2RlUGFja2FnZU1hbmFnZXIiO0U6NDM6IkxhcmF2ZWxcUm9zdGVyXEVudW1zXE5vZGVQYWNrYWdlTWFuYWdlcjpOUE0iO31zOjk6InRpbWVzdGFtcCI7aToxNzcwMTk1NzI3O30=	1770282127
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: gallery_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gallery_items (id, title, description, file_path, media_type, sort_order, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
1	default	{"uuid":"af7204cf-5287-4db8-8508-01cb0757202e","displayName":"App\\\\Events\\\\MatchEventOccurred","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Broadcasting\\\\BroadcastEvent","command":"O:38:\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\":17:{s:5:\\"event\\";O:29:\\"App\\\\Events\\\\MatchEventOccurred\\":2:{s:7:\\"matchId\\";i:1;s:9:\\"eventData\\";a:3:{s:5:\\"event\\";O:21:\\"App\\\\Models\\\\MatchEvent\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"match_events\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:1;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"1\\";s:9:\\"player_id\\";s:1:\\"2\\";s:6:\\"minute\\";s:2:\\"45\\";s:16:\\"assist_player_id\\";N;s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 09:40:22\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 09:40:22\\";s:2:\\"id\\";i:2;}s:11:\\"\\u0000*\\u0000original\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"1\\";s:9:\\"player_id\\";s:1:\\"2\\";s:6:\\"minute\\";s:2:\\"45\\";s:16:\\"assist_player_id\\";N;s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 09:40:22\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 09:40:22\\";s:2:\\"id\\";i:2;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:2:{s:6:\\"player\\";O:17:\\"App\\\\Models\\\\Player\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:7:\\"players\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:27:{s:2:\\"id\\";i:2;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"UPN 2\\";s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";s:10:\\"midfielder\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:11:\\"\\u0000*\\u0000original\\";a:27:{s:2:\\"id\\";i:2;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"UPN 2\\";s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";s:10:\\"midfielder\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:1:{s:10:\\"birth_date\\";s:4:\\"date\\";}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:23:{i:0;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";i:2;s:9:\\"last_name\\";i:3;s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";i:5;s:10:\\"birth_date\\";i:6;s:6:\\"height\\";i:7;s:11:\\"nationality\\";i:8;s:10:\\"photo_path\\";i:9;s:5:\\"goals\\";i:10;s:7:\\"assists\\";i:11;s:12:\\"yellow_cards\\";i:12;s:9:\\"red_cards\\";i:13;s:14:\\"matches_played\\";i:14;s:14:\\"minutes_played\\";i:15;s:16:\\"passes_completed\\";i:16;s:13:\\"pass_accuracy\\";i:17;s:7:\\"tackles\\";i:18;s:13:\\"interceptions\\";i:19;s:15:\\"fouls_committed\\";i:20;s:14:\\"fouls_suffered\\";i:21;s:15:\\"shots_on_target\\";i:22;s:8:\\"dribbles\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:4:\\"team\\";O:15:\\"App\\\\Models\\\\Team\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:5:\\"teams\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:1;s:13:\\"university_id\\";i:1;s:4:\\"name\\";s:9:\\"UPN Lions\\";s:5:\\"coach\\";s:11:\\"Coach Mvuka\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:1;s:13:\\"university_id\\";i:1;s:4:\\"name\\";s:9:\\"UPN Lions\\";s:5:\\"coach\\";s:11:\\"Coach Mvuka\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:1:{s:10:\\"university\\";O:21:\\"App\\\\Models\\\\University\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"universities\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:1;s:4:\\"name\\";s:27:\\"Université de Pointe-Noire\\";s:10:\\"short_name\\";s:3:\\"UPN\\";s:4:\\"logo\\";N;s:6:\\"colors\\";s:10:\\"Blue\\/White\\";s:11:\\"description\\";s:31:\\"La grande université publique.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:1;s:4:\\"name\\";s:27:\\"Université de Pointe-Noire\\";s:10:\\"short_name\\";s:3:\\"UPN\\";s:4:\\"logo\\";N;s:6:\\"colors\\";s:10:\\"Blue\\/White\\";s:11:\\"description\\";s:31:\\"La grande université publique.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:4:\\"name\\";i:1;s:10:\\"short_name\\";i:2;s:4:\\"logo\\";i:3;s:6:\\"colors\\";i:4;s:11:\\"description\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:13:\\"university_id\\";i:1;s:4:\\"name\\";i:2;s:5:\\"coach\\";i:3;s:8:\\"category\\";i:4;s:4:\\"year\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:7:{i:0;s:8:\\"match_id\\";i:1;s:7:\\"team_id\\";i:2;s:9:\\"player_id\\";i:3;s:10:\\"event_type\\";i:4;s:6:\\"minute\\";i:5;s:16:\\"assist_player_id\\";i:6;s:13:\\"out_player_id\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:10:\\"home_score\\";i:2;s:10:\\"away_score\\";i:1;}}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:7:\\"backoff\\";N;s:13:\\"maxExceptions\\";N;s:23:\\"deleteWhenMissingModels\\";b:1;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:12:\\"messageGroup\\";N;s:12:\\"deduplicator\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;}"},"createdAt":1770198022,"delay":null}	0	\N	1770198023	1770198023
2	default	{"uuid":"b44ff319-82ce-48eb-90a4-dca475fb9aa6","displayName":"App\\\\Events\\\\MatchEventOccurred","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Broadcasting\\\\BroadcastEvent","command":"O:38:\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\":17:{s:5:\\"event\\";O:29:\\"App\\\\Events\\\\MatchEventOccurred\\":2:{s:7:\\"matchId\\";i:1;s:9:\\"eventData\\";a:3:{s:5:\\"event\\";O:21:\\"App\\\\Models\\\\MatchEvent\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"match_events\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:1;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"2\\";s:9:\\"player_id\\";s:1:\\"7\\";s:6:\\"minute\\";s:2:\\"32\\";s:16:\\"assist_player_id\\";N;s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 12:36:01\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 12:36:01\\";s:2:\\"id\\";i:3;}s:11:\\"\\u0000*\\u0000original\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"2\\";s:9:\\"player_id\\";s:1:\\"7\\";s:6:\\"minute\\";s:2:\\"32\\";s:16:\\"assist_player_id\\";N;s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 12:36:01\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 12:36:01\\";s:2:\\"id\\";i:3;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:2:{s:6:\\"player\\";O:17:\\"App\\\\Models\\\\Player\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:7:\\"players\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:27:{s:2:\\"id\\";i:7;s:7:\\"team_id\\";i:2;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"IST 2\\";s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";s:10:\\"goalkeeper\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:11:\\"\\u0000*\\u0000original\\";a:27:{s:2:\\"id\\";i:7;s:7:\\"team_id\\";i:2;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"IST 2\\";s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";s:10:\\"goalkeeper\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:1:{s:10:\\"birth_date\\";s:4:\\"date\\";}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:23:{i:0;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";i:2;s:9:\\"last_name\\";i:3;s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";i:5;s:10:\\"birth_date\\";i:6;s:6:\\"height\\";i:7;s:11:\\"nationality\\";i:8;s:10:\\"photo_path\\";i:9;s:5:\\"goals\\";i:10;s:7:\\"assists\\";i:11;s:12:\\"yellow_cards\\";i:12;s:9:\\"red_cards\\";i:13;s:14:\\"matches_played\\";i:14;s:14:\\"minutes_played\\";i:15;s:16:\\"passes_completed\\";i:16;s:13:\\"pass_accuracy\\";i:17;s:7:\\"tackles\\";i:18;s:13:\\"interceptions\\";i:19;s:15:\\"fouls_committed\\";i:20;s:14:\\"fouls_suffered\\";i:21;s:15:\\"shots_on_target\\";i:22;s:8:\\"dribbles\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:4:\\"team\\";O:15:\\"App\\\\Models\\\\Team\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:5:\\"teams\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:2;s:13:\\"university_id\\";i:2;s:4:\\"name\\";s:9:\\"IST Techs\\";s:5:\\"coach\\";s:11:\\"Coach Samba\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:2;s:13:\\"university_id\\";i:2;s:4:\\"name\\";s:9:\\"IST Techs\\";s:5:\\"coach\\";s:11:\\"Coach Samba\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:1:{s:10:\\"university\\";O:21:\\"App\\\\Models\\\\University\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"universities\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:2;s:4:\\"name\\";s:34:\\"Institut Supérieur de Technologie\\";s:10:\\"short_name\\";s:3:\\"IST\\";s:4:\\"logo\\";N;s:6:\\"colors\\";s:9:\\"Red\\/Black\\";s:11:\\"description\\";s:30:\\"Formation technique de pointe.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:2;s:4:\\"name\\";s:34:\\"Institut Supérieur de Technologie\\";s:10:\\"short_name\\";s:3:\\"IST\\";s:4:\\"logo\\";N;s:6:\\"colors\\";s:9:\\"Red\\/Black\\";s:11:\\"description\\";s:30:\\"Formation technique de pointe.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:4:\\"name\\";i:1;s:10:\\"short_name\\";i:2;s:4:\\"logo\\";i:3;s:6:\\"colors\\";i:4;s:11:\\"description\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:13:\\"university_id\\";i:1;s:4:\\"name\\";i:2;s:5:\\"coach\\";i:3;s:8:\\"category\\";i:4;s:4:\\"year\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:7:{i:0;s:8:\\"match_id\\";i:1;s:7:\\"team_id\\";i:2;s:9:\\"player_id\\";i:3;s:10:\\"event_type\\";i:4;s:6:\\"minute\\";i:5;s:16:\\"assist_player_id\\";i:6;s:13:\\"out_player_id\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:10:\\"home_score\\";i:2;s:10:\\"away_score\\";i:2;}}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:7:\\"backoff\\";N;s:13:\\"maxExceptions\\";N;s:23:\\"deleteWhenMissingModels\\";b:1;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:12:\\"messageGroup\\";N;s:12:\\"deduplicator\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;}"},"createdAt":1770208561,"delay":null}	0	\N	1770208561	1770208561
3	default	{"uuid":"d8ad47a6-8a58-4cfe-a737-eec988b4fc01","displayName":"App\\\\Events\\\\MatchEventOccurred","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Broadcasting\\\\BroadcastEvent","command":"O:38:\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\":17:{s:5:\\"event\\";O:29:\\"App\\\\Events\\\\MatchEventOccurred\\":2:{s:7:\\"matchId\\";i:1;s:9:\\"eventData\\";a:3:{s:5:\\"event\\";O:21:\\"App\\\\Models\\\\MatchEvent\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"match_events\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:1;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"1\\";s:9:\\"player_id\\";s:1:\\"1\\";s:6:\\"minute\\";s:2:\\"54\\";s:16:\\"assist_player_id\\";s:1:\\"2\\";s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 14:21:18\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 14:21:18\\";s:2:\\"id\\";i:4;}s:11:\\"\\u0000*\\u0000original\\";a:10:{s:10:\\"event_type\\";s:4:\\"goal\\";s:7:\\"team_id\\";s:1:\\"1\\";s:9:\\"player_id\\";s:1:\\"1\\";s:6:\\"minute\\";s:2:\\"54\\";s:16:\\"assist_player_id\\";s:1:\\"2\\";s:13:\\"out_player_id\\";N;s:8:\\"match_id\\";i:1;s:10:\\"updated_at\\";s:19:\\"2026-02-04 14:21:18\\";s:10:\\"created_at\\";s:19:\\"2026-02-04 14:21:18\\";s:2:\\"id\\";i:4;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:2:{s:6:\\"player\\";O:17:\\"App\\\\Models\\\\Player\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:7:\\"players\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:27:{s:2:\\"id\\";i:1;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"UPN 1\\";s:13:\\"jersey_number\\";i:2;s:8:\\"position\\";s:8:\\"defender\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:11:\\"\\u0000*\\u0000original\\";a:27:{s:2:\\"id\\";i:1;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";s:6:\\"Joueur\\";s:9:\\"last_name\\";s:5:\\"UPN 1\\";s:13:\\"jersey_number\\";i:2;s:8:\\"position\\";s:8:\\"defender\\";s:10:\\"birth_date\\";s:10:\\"2000-01-01\\";s:5:\\"photo\\";N;s:11:\\"nationality\\";s:2:\\"CG\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";s:6:\\"height\\";N;s:10:\\"photo_path\\";N;s:5:\\"goals\\";i:0;s:7:\\"assists\\";i:0;s:12:\\"yellow_cards\\";i:0;s:9:\\"red_cards\\";i:0;s:14:\\"matches_played\\";i:0;s:14:\\"minutes_played\\";i:0;s:16:\\"passes_completed\\";i:0;s:13:\\"pass_accuracy\\";i:0;s:7:\\"tackles\\";i:0;s:13:\\"interceptions\\";i:0;s:15:\\"fouls_committed\\";i:0;s:14:\\"fouls_suffered\\";i:0;s:15:\\"shots_on_target\\";i:0;s:8:\\"dribbles\\";i:0;}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:1:{s:10:\\"birth_date\\";s:4:\\"date\\";}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:23:{i:0;s:7:\\"team_id\\";i:1;s:10:\\"first_name\\";i:2;s:9:\\"last_name\\";i:3;s:13:\\"jersey_number\\";i:4;s:8:\\"position\\";i:5;s:10:\\"birth_date\\";i:6;s:6:\\"height\\";i:7;s:11:\\"nationality\\";i:8;s:10:\\"photo_path\\";i:9;s:5:\\"goals\\";i:10;s:7:\\"assists\\";i:11;s:12:\\"yellow_cards\\";i:12;s:9:\\"red_cards\\";i:13;s:14:\\"matches_played\\";i:14;s:14:\\"minutes_played\\";i:15;s:16:\\"passes_completed\\";i:16;s:13:\\"pass_accuracy\\";i:17;s:7:\\"tackles\\";i:18;s:13:\\"interceptions\\";i:19;s:15:\\"fouls_committed\\";i:20;s:14:\\"fouls_suffered\\";i:21;s:15:\\"shots_on_target\\";i:22;s:8:\\"dribbles\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:4:\\"team\\";O:15:\\"App\\\\Models\\\\Team\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:5:\\"teams\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:1;s:13:\\"university_id\\";i:1;s:4:\\"name\\";s:9:\\"UPN Lions\\";s:5:\\"coach\\";s:11:\\"Coach Mvuka\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:1;s:13:\\"university_id\\";i:1;s:4:\\"name\\";s:9:\\"UPN Lions\\";s:5:\\"coach\\";s:11:\\"Coach Mvuka\\";s:8:\\"category\\";s:6:\\"Senior\\";s:4:\\"year\\";i:2025;s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-01-20 15:35:49\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:1:{s:10:\\"university\\";O:21:\\"App\\\\Models\\\\University\\":33:{s:13:\\"\\u0000*\\u0000connection\\";s:5:\\"pgsql\\";s:8:\\"\\u0000*\\u0000table\\";s:12:\\"universities\\";s:13:\\"\\u0000*\\u0000primaryKey\\";s:2:\\"id\\";s:10:\\"\\u0000*\\u0000keyType\\";s:3:\\"int\\";s:12:\\"incrementing\\";b:1;s:7:\\"\\u0000*\\u0000with\\";a:0:{}s:12:\\"\\u0000*\\u0000withCount\\";a:0:{}s:19:\\"preventsLazyLoading\\";b:0;s:10:\\"\\u0000*\\u0000perPage\\";i:15;s:6:\\"exists\\";b:1;s:18:\\"wasRecentlyCreated\\";b:0;s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;s:13:\\"\\u0000*\\u0000attributes\\";a:8:{s:2:\\"id\\";i:1;s:4:\\"name\\";s:27:\\"Université de Pointe-Noire\\";s:10:\\"short_name\\";s:3:\\"UPN\\";s:4:\\"logo\\";s:63:\\"logos\\/universities\\/AHA8AmkATafszpSz3Gg8GvDCAWa01KYA8CciDlNb.jpg\\";s:6:\\"colors\\";s:10:\\"Blue\\/White\\";s:11:\\"description\\";s:31:\\"La grande université publique.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-02-04 13:35:41\\";}s:11:\\"\\u0000*\\u0000original\\";a:8:{s:2:\\"id\\";i:1;s:4:\\"name\\";s:27:\\"Université de Pointe-Noire\\";s:10:\\"short_name\\";s:3:\\"UPN\\";s:4:\\"logo\\";s:63:\\"logos\\/universities\\/AHA8AmkATafszpSz3Gg8GvDCAWa01KYA8CciDlNb.jpg\\";s:6:\\"colors\\";s:10:\\"Blue\\/White\\";s:11:\\"description\\";s:31:\\"La grande université publique.\\";s:10:\\"created_at\\";s:19:\\"2026-01-20 15:35:49\\";s:10:\\"updated_at\\";s:19:\\"2026-02-04 13:35:41\\";}s:10:\\"\\u0000*\\u0000changes\\";a:0:{}s:11:\\"\\u0000*\\u0000previous\\";a:0:{}s:8:\\"\\u0000*\\u0000casts\\";a:0:{}s:17:\\"\\u0000*\\u0000classCastCache\\";a:0:{}s:21:\\"\\u0000*\\u0000attributeCastCache\\";a:0:{}s:13:\\"\\u0000*\\u0000dateFormat\\";N;s:10:\\"\\u0000*\\u0000appends\\";a:0:{}s:19:\\"\\u0000*\\u0000dispatchesEvents\\";a:0:{}s:14:\\"\\u0000*\\u0000observables\\";a:0:{}s:12:\\"\\u0000*\\u0000relations\\";a:0:{}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:4:\\"name\\";i:1;s:10:\\"short_name\\";i:2;s:4:\\"logo\\";i:3;s:6:\\"colors\\";i:4;s:11:\\"description\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:5:{i:0;s:13:\\"university_id\\";i:1;s:4:\\"name\\";i:2;s:5:\\"coach\\";i:3;s:8:\\"category\\";i:4;s:4:\\"year\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}}s:10:\\"\\u0000*\\u0000touches\\";a:0:{}s:27:\\"\\u0000*\\u0000relationAutoloadCallback\\";N;s:26:\\"\\u0000*\\u0000relationAutoloadContext\\";N;s:10:\\"timestamps\\";b:1;s:13:\\"usesUniqueIds\\";b:0;s:9:\\"\\u0000*\\u0000hidden\\";a:0:{}s:10:\\"\\u0000*\\u0000visible\\";a:0:{}s:11:\\"\\u0000*\\u0000fillable\\";a:7:{i:0;s:8:\\"match_id\\";i:1;s:7:\\"team_id\\";i:2;s:9:\\"player_id\\";i:3;s:10:\\"event_type\\";i:4;s:6:\\"minute\\";i:5;s:16:\\"assist_player_id\\";i:6;s:13:\\"out_player_id\\";}s:10:\\"\\u0000*\\u0000guarded\\";a:1:{i:0;s:1:\\"*\\";}}s:10:\\"home_score\\";i:3;s:10:\\"away_score\\";i:2;}}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:7:\\"backoff\\";N;s:13:\\"maxExceptions\\";N;s:23:\\"deleteWhenMissingModels\\";b:1;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:12:\\"messageGroup\\";N;s:12:\\"deduplicator\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;}"},"createdAt":1770214878,"delay":null}	0	\N	1770214878	1770214878
\.


--
-- Data for Name: lineups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lineups (id, match_id, team_id, player_id, is_starter, role, created_at, updated_at, match_position) FROM stdin;
\.


--
-- Data for Name: match_events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.match_events (id, match_id, team_id, player_id, assist_player_id, event_type, minute, additional_time, description, created_at, updated_at, out_player_id) FROM stdin;
1	1	1	1	\N	goal	23	\N	Superbe frappe de loin	2026-01-20 15:35:50	2026-01-20 15:35:50	\N
2	1	1	2	\N	goal	45	\N	\N	2026-02-04 09:40:22	2026-02-04 09:40:22	\N
3	1	2	7	\N	goal	32	\N	\N	2026-02-04 12:36:01	2026-02-04 12:36:01	\N
4	1	1	1	2	goal	54	\N	\N	2026-02-04 14:21:18	2026-02-04 14:21:18	\N
\.


--
-- Data for Name: match_lineups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.match_lineups (id, match_id, team_id, player_id, role, starting_position, order_key, created_at, updated_at, is_starter, "position") FROM stdin;
\.


--
-- Data for Name: matches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.matches (id, home_team_id, away_team_id, match_date, venue, status, home_score, away_score, round, "group", attendance, created_at, updated_at, start_time, home_coach, away_coach, home_formation, away_formation, home_composition_ready, away_composition_ready, match_type, home_fouls, home_corners, home_offsides, away_fouls, away_corners, away_offsides, home_yellow_cards, home_red_cards, away_yellow_cards, away_red_cards, home_possession, away_possession, home_shots, away_shots, home_shots_on_target, away_shots_on_target, home_saves, away_saves, home_free_kicks, away_free_kicks, home_throw_ins, away_throw_ins, home_goalkicks, away_goalkicks, home_penalties, away_penalties, referee, weather, temperature, humidity, timer_paused_at, admin_notes, match_report, elapsed_time, additional_time_first_half, additional_time_second_half, is_extra_time, is_penalty_shootout) FROM stdin;
2	2	3	2026-01-19 00:00:00	Complexe Sportif	finished	2	0	group_stage	A	\N	2026-01-20 15:35:50	2026-01-20 15:35:50	\N	\N	\N	\N	\N	f	f	tournament	0	0	0	0	0	0	0	0	0	0	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0	\N	\N	\N	\N	\N	\N	\N	0	0	0	f	f
3	1	3	2026-01-21 14:00:00	Stade Municipal	scheduled	0	0	group_stage	A	\N	2026-01-20 15:35:50	2026-01-20 15:35:50	\N	\N	\N	\N	\N	f	f	tournament	0	0	0	0	0	0	0	0	0	0	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0	\N	\N	\N	\N	\N	\N	\N	0	0	0	f	f
1	1	2	2026-01-20 14:50:50	Stade Municipal	live	3	2	group_stage	A	\N	2026-01-20 15:35:50	2026-02-04 14:21:18	\N	\N	\N	\N	\N	f	f	tournament	0	0	0	0	0	0	0	0	0	0	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0	\N	\N	\N	\N	\N	\N	\N	0	0	0	f	f
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_08_26_100418_add_two_factor_columns_to_users_table	1
5	2025_11_19_120428_create_universities_table	1
6	2025_11_19_120430_create_teams_table	1
7	2025_11_19_120431_create_players_table	1
8	2025_11_19_120432_create_matches_table	1
9	2025_11_19_120433_create_match_events_table	1
10	2025_11_19_120434_create_standings_table	1
11	2025_11_20_142328_add_height_to_players_table	1
12	2025_11_20_144219_make_venue_nullable_in_matches_table	1
13	2025_11_21_084636_add_stats_to_standings_table	1
14	2025_11_22_195559_add_start_time_to_matches_table	1
15	2025_11_22_203342_add_coach_names_to_matches_table	1
16	2025_11_22_203459_create_match_lineups_table	1
17	2025_11_22_213716_create_lineups_table	1
18	2025_11_22_234107_add_formations_to_matches_table	1
19	2025_11_23_171345_add_photo_path_to_players_table	1
20	2025_11_24_165622_add_is_starter_and_position_to_match_lineups_table	1
21	2025_11_25_155609_update_lineups_table_for_match_position	1
22	2025_11_28_114832_add_out_player_id_to_match_events_table	1
23	2025_11_28_120445_add_composition_flags_to_matches_table	1
24	2025_12_05_171851_add_match_type_to_matches_table	1
25	2025_12_05_194901_create_gallery_items_table	1
26	2025_12_06_103355_add_is_admin_to_users_table	1
27	2025_12_07_181611_add_aggregated_stats_to_matches_table	1
28	2025_12_13_000001_add_advanced_stats_to_matches_table	1
29	2025_12_13_074756_add_timer_paused_at_to_matches_table	1
30	2025_12_17_000001_add_theme_preference_to_users_table	1
31	2025_12_17_000002_add_manual_stats_to_matches_table	1
32	2025_12_19_022152_add_elapsed_time_and_additional_fields_to_matches_table	1
33	2025_12_27_104151_add_stats_columns_to_players_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: players; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.players (id, team_id, first_name, last_name, jersey_number, "position", birth_date, photo, nationality, created_at, updated_at, height, photo_path, goals, assists, yellow_cards, red_cards, matches_played, minutes_played, passes_completed, pass_accuracy, tackles, interceptions, fouls_committed, fouls_suffered, shots_on_target, dribbles) FROM stdin;
1	1	Joueur	UPN 1	2	defender	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
2	1	Joueur	UPN 2	4	midfielder	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
3	1	Joueur	UPN 3	6	midfielder	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
4	1	Joueur	UPN 4	8	goalkeeper	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
5	1	Joueur	UPN 5	10	midfielder	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
6	2	Joueur	IST 1	2	goalkeeper	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
7	2	Joueur	IST 2	4	goalkeeper	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
8	2	Joueur	IST 3	6	forward	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
9	2	Joueur	IST 4	8	defender	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
10	2	Joueur	IST 5	10	forward	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
11	3	Joueur	ESC 1	2	goalkeeper	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
12	3	Joueur	ESC 2	4	midfielder	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
13	3	Joueur	ESC 3	6	forward	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
14	3	Joueur	ESC 4	8	forward	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
15	3	Joueur	ESC 5	10	defender	2000-01-01	\N	CG	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	0	0	0	0	0	0	0	0	0	0	0	0	0	0
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
TyD4A0vaTb6fDkX8EqGF9XVjS5tGah4lMDGO04Ii	1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0	YTo1OntzOjY6Il90b2tlbiI7czo0MDoidjNySWdRZGd1dnhJRzRNQWhJOE5ub3NIdkJ6QTlaTUl6Y3BiMEs5UiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluLWN1c3RvbS9saXZlL21hdGNoLzEiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluLWN1c3RvbS9saXZlL21hdGNoLzEiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmxpdmUuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==	1770214879
\.


--
-- Data for Name: standings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.standings (id, team_id, "group", played, won, drawn, lost, goals_for, goals_against, goal_difference, points, created_at, updated_at, wins, losses, draws) FROM stdin;
1	2	A	1	1	0	0	2	0	2	3	2026-01-20 15:35:50	2026-01-20 15:35:50	0	0	0
2	3	A	1	0	0	1	0	2	-2	0	2026-01-20 15:35:50	2026-01-20 15:35:50	0	0	0
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.teams (id, university_id, name, coach, category, year, created_at, updated_at) FROM stdin;
1	1	UPN Lions	Coach Mvuka	Senior	2025	2026-01-20 15:35:49	2026-01-20 15:35:49
2	2	IST Techs	Coach Samba	Senior	2025	2026-01-20 15:35:49	2026-01-20 15:35:49
3	3	ESC Business	Coach Ndoki	Senior	2025	2026-01-20 15:35:49	2026-01-20 15:35:49
\.


--
-- Data for Name: universities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.universities (id, name, short_name, logo, colors, description, created_at, updated_at) FROM stdin;
2	Institut Supérieur de Technologie	IST	\N	Red/Black	Formation technique de pointe.	2026-01-20 15:35:49	2026-01-20 15:35:49
3	École Supérieure de Commerce	ESC	\N	Green/Gold	Les futurs managers.	2026-01-20 15:35:49	2026-01-20 15:35:49
1	Université de Pointe-Noire	UPN	logos/universities/AHA8AmkATafszpSz3Gg8GvDCAWa01KYA8CciDlNb.jpg	Blue/White	La grande université publique.	2026-01-20 15:35:49	2026-02-04 13:35:41
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, is_admin, theme_preference) FROM stdin;
1	Admin	emoukouanga@gmail.com	2026-01-20 15:35:49	$2y$12$gHJIoTqn.NWLj5fxDtMfl.sXns0w8Q9m7UHJfcndT.rlu8DCybk8W	AWAzDGjtbBBcCUUSsoPXhzeufDMRFi0y6awdFF687qyvRH5w6Sx5oeMOfGgs	2026-01-20 15:35:49	2026-01-20 15:35:49	\N	\N	\N	t	system
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: gallery_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gallery_items_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 3, true);


--
-- Name: lineups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineups_id_seq', 1, false);


--
-- Name: match_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.match_events_id_seq', 4, true);


--
-- Name: match_lineups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.match_lineups_id_seq', 1, false);


--
-- Name: matches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.matches_id_seq', 3, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 33, true);


--
-- Name: players_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.players_id_seq', 15, true);


--
-- Name: standings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.standings_id_seq', 2, true);


--
-- Name: teams_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.teams_id_seq', 3, true);


--
-- Name: universities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.universities_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: gallery_items gallery_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery_items
    ADD CONSTRAINT gallery_items_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: lineups lineups_match_id_player_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups
    ADD CONSTRAINT lineups_match_id_player_id_unique UNIQUE (match_id, player_id);


--
-- Name: lineups lineups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups
    ADD CONSTRAINT lineups_pkey PRIMARY KEY (id);


--
-- Name: match_events match_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_pkey PRIMARY KEY (id);


--
-- Name: match_lineups match_lineups_match_id_player_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups
    ADD CONSTRAINT match_lineups_match_id_player_id_unique UNIQUE (match_id, player_id);


--
-- Name: match_lineups match_lineups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups
    ADD CONSTRAINT match_lineups_pkey PRIMARY KEY (id);


--
-- Name: matches matches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matches
    ADD CONSTRAINT matches_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: players players_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: standings standings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standings
    ADD CONSTRAINT standings_pkey PRIMARY KEY (id);


--
-- Name: standings standings_team_id_group_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standings
    ADD CONSTRAINT standings_team_id_group_unique UNIQUE (team_id, "group");


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: universities universities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.universities
    ADD CONSTRAINT universities_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: lineups lineups_match_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups
    ADD CONSTRAINT lineups_match_id_foreign FOREIGN KEY (match_id) REFERENCES public.matches(id) ON DELETE CASCADE;


--
-- Name: lineups lineups_player_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups
    ADD CONSTRAINT lineups_player_id_foreign FOREIGN KEY (player_id) REFERENCES public.players(id) ON DELETE CASCADE;


--
-- Name: lineups lineups_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineups
    ADD CONSTRAINT lineups_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: match_events match_events_assist_player_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_assist_player_id_foreign FOREIGN KEY (assist_player_id) REFERENCES public.players(id) ON DELETE SET NULL;


--
-- Name: match_events match_events_match_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_match_id_foreign FOREIGN KEY (match_id) REFERENCES public.matches(id) ON DELETE CASCADE;


--
-- Name: match_events match_events_out_player_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_out_player_id_foreign FOREIGN KEY (out_player_id) REFERENCES public.players(id);


--
-- Name: match_events match_events_player_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_player_id_foreign FOREIGN KEY (player_id) REFERENCES public.players(id) ON DELETE CASCADE;


--
-- Name: match_events match_events_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_events
    ADD CONSTRAINT match_events_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: match_lineups match_lineups_match_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups
    ADD CONSTRAINT match_lineups_match_id_foreign FOREIGN KEY (match_id) REFERENCES public.matches(id) ON DELETE CASCADE;


--
-- Name: match_lineups match_lineups_player_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups
    ADD CONSTRAINT match_lineups_player_id_foreign FOREIGN KEY (player_id) REFERENCES public.players(id) ON DELETE CASCADE;


--
-- Name: match_lineups match_lineups_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_lineups
    ADD CONSTRAINT match_lineups_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: matches matches_away_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matches
    ADD CONSTRAINT matches_away_team_id_foreign FOREIGN KEY (away_team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: matches matches_home_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matches
    ADD CONSTRAINT matches_home_team_id_foreign FOREIGN KEY (home_team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: players players_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: standings standings_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standings
    ADD CONSTRAINT standings_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: teams teams_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict GKSSFziHMqDi3arA7KQ7VVP76Wi4ZQMtO97UBRVFU4v5QcD8PaBveCbhuOhz1Do

