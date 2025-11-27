--
-- PostgreSQL database dump
--

\restrict jmbm3DShDWcqQyXnwyIbN4oZhiQS21JaO8obtGgeoUl4X9vFraIvjYLgJNJlUCr

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.6

-- Started on 2025-11-26 00:41:10

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

--
-- TOC entry 5118 (class 1262 OID 24576)
-- Name: laravel_db; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE laravel_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_United States.1252';


\unrestrict jmbm3DShDWcqQyXnwyIbN4oZhiQS21JaO8obtGgeoUl4X9vFraIvjYLgJNJlUCr
\connect laravel_db
\restrict jmbm3DShDWcqQyXnwyIbN4oZhiQS21JaO8obtGgeoUl4X9vFraIvjYLgJNJlUCr

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
-- TOC entry 223 (class 1259 OID 49447)
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- TOC entry 224 (class 1259 OID 49454)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- TOC entry 249 (class 1259 OID 49682)
-- Name: conductor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.conductor (
    conductor_id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    apellido character varying(255) NOT NULL,
    fecha_nacimiento date NOT NULL,
    ci character varying(50) NOT NULL,
    celular character varying(20) NOT NULL,
    id_licencia integer NOT NULL
);


--
-- TOC entry 248 (class 1259 OID 49681)
-- Name: conductor_conductor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.conductor_conductor_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 248
-- Name: conductor_conductor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.conductor_conductor_id_seq OWNED BY public.conductor.conductor_id;


--
-- TOC entry 237 (class 1259 OID 49531)
-- Name: destino; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.destino (
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    id_destino integer NOT NULL,
    comunidad character varying(255),
    direccion character varying(255),
    latitud double precision,
    longitud double precision,
    provincia character varying(255)
);


--
-- TOC entry 236 (class 1259 OID 49530)
-- Name: destino_id_destino_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.destino_id_destino_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 236
-- Name: destino_id_destino_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.destino_id_destino_seq OWNED BY public.destino.id_destino;


--
-- TOC entry 233 (class 1259 OID 49501)
-- Name: paquete; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.paquete (
    id_paquete bigint NOT NULL,
    id_solicitud bigint,
    ubicacion_actual character varying(255),
    fecha_creacion date DEFAULT '2025-11-10'::date NOT NULL,
    fecha_entrega date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    estado_id integer,
    codigo character varying(16),
    fecha_aprobacion date,
    imagen character varying(255),
    id_encargado character varying(255),
    user_id bigint,
    id_ubicacion integer,
    id_conductor integer,
    id_vehiculo integer
);


--
-- TOC entry 232 (class 1259 OID 49500)
-- Name: donacion_id_donacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.donacion_id_donacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 232
-- Name: donacion_id_donacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.donacion_id_donacion_seq OWNED BY public.paquete.id_paquete;


--
-- TOC entry 235 (class 1259 OID 49517)
-- Name: estado; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.estado (
    id_estado bigint NOT NULL,
    nombre_estado character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 234 (class 1259 OID 49516)
-- Name: estado_id_estado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.estado_id_estado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 234
-- Name: estado_id_estado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.estado_id_estado_seq OWNED BY public.estado.id_estado;


--
-- TOC entry 229 (class 1259 OID 49479)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 228 (class 1259 OID 49478)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 228
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 245 (class 1259 OID 49580)
-- Name: historial_seguimiento_donaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.historial_seguimiento_donaciones (
    id_historial integer NOT NULL,
    ci_usuario character varying(255),
    fecha_actualizacion timestamp(0) without time zone,
    imagen_evidencia text,
    id_paquete integer,
    id_ubicacion integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    estado character varying(255),
    conductor_nombre character varying(255),
    conductor_ci character varying(50),
    vehiculo_placa character varying(50)
);


--
-- TOC entry 244 (class 1259 OID 49579)
-- Name: historial_seguimiento_donaciones_id_historial_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.historial_seguimiento_donaciones_id_historial_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 244
-- Name: historial_seguimiento_donaciones_id_historial_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.historial_seguimiento_donaciones_id_historial_seq OWNED BY public.historial_seguimiento_donaciones.id_historial;


--
-- TOC entry 227 (class 1259 OID 49471)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 226 (class 1259 OID 49462)
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 225 (class 1259 OID 49461)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 225
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 259 (class 1259 OID 57891)
-- Name: marca; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.marca (
    id_marca bigint NOT NULL,
    nombre_marca character varying(255) NOT NULL
);


--
-- TOC entry 258 (class 1259 OID 57890)
-- Name: marca_id_marca_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.marca_id_marca_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 258
-- Name: marca_id_marca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.marca_id_marca_seq OWNED BY public.marca.id_marca;


--
-- TOC entry 218 (class 1259 OID 49414)
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- TOC entry 217 (class 1259 OID 49413)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 221 (class 1259 OID 49431)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- TOC entry 261 (class 1259 OID 66066)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 260 (class 1259 OID 66065)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 260
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 243 (class 1259 OID 49571)
-- Name: reporte; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.reporte (
    id_reporte integer NOT NULL,
    direccion_archivo character varying(255),
    fecha_reporte date,
    gestion character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    id_paquete integer
);


--
-- TOC entry 242 (class 1259 OID 49570)
-- Name: reporte_id_reporte_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.reporte_id_reporte_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 242
-- Name: reporte_id_reporte_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.reporte_id_reporte_seq OWNED BY public.reporte.id_reporte;


--
-- TOC entry 257 (class 1259 OID 57884)
-- Name: rol; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rol (
    id_rol bigint NOT NULL,
    titulo_rol character varying(255) NOT NULL
);


--
-- TOC entry 256 (class 1259 OID 57883)
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rol_id_rol_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 256
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- TOC entry 222 (class 1259 OID 49438)
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- TOC entry 239 (class 1259 OID 49540)
-- Name: solicitante; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.solicitante (
    id_solicitante integer NOT NULL,
    apellido character varying(255),
    ci character varying(255),
    email character varying(255),
    nombre character varying(255),
    telefono character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 238 (class 1259 OID 49539)
-- Name: solicitante_id_solicitante_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.solicitante_id_solicitante_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 238
-- Name: solicitante_id_solicitante_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.solicitante_id_solicitante_seq OWNED BY public.solicitante.id_solicitante;


--
-- TOC entry 231 (class 1259 OID 49491)
-- Name: solicitud; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.solicitud (
    id_solicitud bigint NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    codigo_seguimiento character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cantidad_personas integer,
    fecha_inicio date,
    tipo_emergencia character varying(255),
    insumos_necesarios text,
    id_solicitante integer,
    id_destino integer,
    fecha_solicitud date,
    aprobada boolean DEFAULT false NOT NULL,
    apoyoaceptado boolean DEFAULT false NOT NULL,
    justificacion character varying(255),
    id_tipoemergencia bigint
);


--
-- TOC entry 230 (class 1259 OID 49490)
-- Name: solicitud_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.solicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 230
-- Name: solicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.solicitud_id_seq OWNED BY public.solicitud.id_solicitud;


--
-- TOC entry 255 (class 1259 OID 57867)
-- Name: tipo_emergencia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_emergencia (
    id_emergencia bigint NOT NULL,
    emergencia character varying(255) NOT NULL,
    prioridad integer NOT NULL
);


--
-- TOC entry 254 (class 1259 OID 57866)
-- Name: tipo_emergencia_id_emergencia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipo_emergencia_id_emergencia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 254
-- Name: tipo_emergencia_id_emergencia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipo_emergencia_id_emergencia_seq OWNED BY public.tipo_emergencia.id_emergencia;


--
-- TOC entry 247 (class 1259 OID 49675)
-- Name: tipo_licencia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_licencia (
    id_licencia integer NOT NULL,
    licencia character varying(100) NOT NULL
);


--
-- TOC entry 246 (class 1259 OID 49674)
-- Name: tipo_licencia_id_licencia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipo_licencia_id_licencia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 246
-- Name: tipo_licencia_id_licencia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipo_licencia_id_licencia_seq OWNED BY public.tipo_licencia.id_licencia;


--
-- TOC entry 251 (class 1259 OID 49696)
-- Name: tipo_vehiculo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_vehiculo (
    id_tipovehiculo integer NOT NULL,
    nombre_tipo_vehiculo character varying(100) NOT NULL
);


--
-- TOC entry 250 (class 1259 OID 49695)
-- Name: tipo_vehiculo_id_tipovehiculo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipo_vehiculo_id_tipovehiculo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5135 (class 0 OID 0)
-- Dependencies: 250
-- Name: tipo_vehiculo_id_tipovehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipo_vehiculo_id_tipovehiculo_seq OWNED BY public.tipo_vehiculo.id_tipovehiculo;


--
-- TOC entry 241 (class 1259 OID 49551)
-- Name: ubicacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ubicacion (
    id_ubicacion bigint NOT NULL,
    latitud double precision,
    longitud double precision,
    zona character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 240 (class 1259 OID 49550)
-- Name: ubicacion_id_ubicacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ubicacion_id_ubicacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5136 (class 0 OID 0)
-- Dependencies: 240
-- Name: ubicacion_id_ubicacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ubicacion_id_ubicacion_seq OWNED BY public.ubicacion.id_ubicacion;


--
-- TOC entry 220 (class 1259 OID 49421)
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    correo_electronico character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    nombre character varying(255),
    apellido character varying(255),
    ci character varying(255),
    telefono character varying(255),
    administrador boolean DEFAULT false NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    id_rol bigint
);


--
-- TOC entry 219 (class 1259 OID 49420)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5137 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 253 (class 1259 OID 49703)
-- Name: vehiculo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.vehiculo (
    id_vehiculo integer NOT NULL,
    placa character varying(50) NOT NULL,
    capacidad_aproximada character varying(50),
    id_tipovehiculo integer,
    modelo_anio character varying(10),
    modelo character varying(100),
    marca character varying(100),
    id_marca bigint
);


--
-- TOC entry 252 (class 1259 OID 49702)
-- Name: vehiculo_id_vehiculo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.vehiculo_id_vehiculo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5138 (class 0 OID 0)
-- Dependencies: 252
-- Name: vehiculo_id_vehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.vehiculo_id_vehiculo_seq OWNED BY public.vehiculo.id_vehiculo;


--
-- TOC entry 4830 (class 2604 OID 49685)
-- Name: conductor conductor_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.conductor ALTER COLUMN conductor_id SET DEFAULT nextval('public.conductor_conductor_id_seq'::regclass);


--
-- TOC entry 4824 (class 2604 OID 49534)
-- Name: destino id_destino; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.destino ALTER COLUMN id_destino SET DEFAULT nextval('public.destino_id_destino_seq'::regclass);


--
-- TOC entry 4823 (class 2604 OID 49520)
-- Name: estado id_estado; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.estado ALTER COLUMN id_estado SET DEFAULT nextval('public.estado_id_estado_seq'::regclass);


--
-- TOC entry 4815 (class 2604 OID 49482)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4828 (class 2604 OID 49583)
-- Name: historial_seguimiento_donaciones id_historial; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historial_seguimiento_donaciones ALTER COLUMN id_historial SET DEFAULT nextval('public.historial_seguimiento_donaciones_id_historial_seq'::regclass);


--
-- TOC entry 4814 (class 2604 OID 49465)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4835 (class 2604 OID 57894)
-- Name: marca id_marca; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.marca ALTER COLUMN id_marca SET DEFAULT nextval('public.marca_id_marca_seq'::regclass);


--
-- TOC entry 4810 (class 2604 OID 49417)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4821 (class 2604 OID 49504)
-- Name: paquete id_paquete; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete ALTER COLUMN id_paquete SET DEFAULT nextval('public.donacion_id_donacion_seq'::regclass);


--
-- TOC entry 4836 (class 2604 OID 66069)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4827 (class 2604 OID 49574)
-- Name: reporte id_reporte; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reporte ALTER COLUMN id_reporte SET DEFAULT nextval('public.reporte_id_reporte_seq'::regclass);


--
-- TOC entry 4834 (class 2604 OID 57887)
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- TOC entry 4825 (class 2604 OID 49543)
-- Name: solicitante id_solicitante; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitante ALTER COLUMN id_solicitante SET DEFAULT nextval('public.solicitante_id_solicitante_seq'::regclass);


--
-- TOC entry 4817 (class 2604 OID 49494)
-- Name: solicitud id_solicitud; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitud ALTER COLUMN id_solicitud SET DEFAULT nextval('public.solicitud_id_seq'::regclass);


--
-- TOC entry 4833 (class 2604 OID 57870)
-- Name: tipo_emergencia id_emergencia; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_emergencia ALTER COLUMN id_emergencia SET DEFAULT nextval('public.tipo_emergencia_id_emergencia_seq'::regclass);


--
-- TOC entry 4829 (class 2604 OID 49678)
-- Name: tipo_licencia id_licencia; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_licencia ALTER COLUMN id_licencia SET DEFAULT nextval('public.tipo_licencia_id_licencia_seq'::regclass);


--
-- TOC entry 4831 (class 2604 OID 49699)
-- Name: tipo_vehiculo id_tipovehiculo; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_vehiculo ALTER COLUMN id_tipovehiculo SET DEFAULT nextval('public.tipo_vehiculo_id_tipovehiculo_seq'::regclass);


--
-- TOC entry 4826 (class 2604 OID 49554)
-- Name: ubicacion id_ubicacion; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ubicacion ALTER COLUMN id_ubicacion SET DEFAULT nextval('public.ubicacion_id_ubicacion_seq'::regclass);


--
-- TOC entry 4811 (class 2604 OID 49424)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4832 (class 2604 OID 49706)
-- Name: vehiculo id_vehiculo; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vehiculo ALTER COLUMN id_vehiculo SET DEFAULT nextval('public.vehiculo_id_vehiculo_seq'::regclass);


--
-- TOC entry 5074 (class 0 OID 49447)
-- Dependencies: 223
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5075 (class 0 OID 49454)
-- Dependencies: 224
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5100 (class 0 OID 49682)
-- Dependencies: 249
-- Data for Name: conductor; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.conductor VALUES (1, 'Ariana', 'Ortiz', '2004-10-12', '89652304', '75648958', 2);
INSERT INTO public.conductor VALUES (2, 'Ana', 'Villafani', '2003-09-06', '7567717', '77312304', 1);
INSERT INTO public.conductor VALUES (4, 'Luis', 'Villafani', '1998-10-01', '7567715', '75678421', 1);
INSERT INTO public.conductor VALUES (5, 'Mia', 'Villafani', '2000-06-16', '75674852', '77312548', 1);


--
-- TOC entry 5088 (class 0 OID 49531)
-- Dependencies: 237
-- Data for Name: destino; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.destino VALUES ('2025-11-10 18:20:57', '2025-11-10 18:20:57', 1, 'Univalle', 'Septimo Anillo, Santa Cruz de la Sierra', -17.721995775101004, -63.17454938465861, 'Santa Cruz de la Sierra');
INSERT INTO public.destino VALUES ('2025-11-10 19:21:35', '2025-11-10 19:21:35', 2, 'Comunidad Eco Patitas', 'Parque Urbano Central', -17.7523132, -63.1651308, 'Santa Cruz de la Sierra');
INSERT INTO public.destino VALUES ('2025-11-10 19:23:53', '2025-11-10 19:23:53', 3, 'Mineros', 'VQHF+HR6, Mineros', -17.120602703020957, -63.22567938149554, 'Mineros');
INSERT INTO public.destino VALUES ('2025-11-10 19:25:16', '2025-11-10 19:25:16', 4, 'Sayco Org', '7R4R+WQ7, C. 2 Este, Santa Cruz de la Sierra', -17.74251803886879, -63.158004198152575, 'Santa Cruz de la Sierra');
INSERT INTO public.destino VALUES ('2025-11-15 22:22:38', '2025-11-15 22:22:38', 5, 'Sayco Org', '5to anillo Av. Beni', -17.7523132, -63.1651308, 'Santa Cruz de la Sierra');
INSERT INTO public.destino VALUES ('2025-11-23 05:24:01', '2025-11-23 05:24:01', 6, 'Comunidad X', 'Calle Falsa 123', -17.77, -63.18, 'Provincia Andrés Ibáñez');
INSERT INTO public.destino VALUES ('2025-11-23 05:25:46', '2025-11-23 05:25:46', 7, 'Comunidad X', 'Calle Falsa 123', -17.77, -63.18, 'Provincia Andrés Ibáñez');
INSERT INTO public.destino VALUES ('2025-11-25 14:37:57', '2025-11-25 14:37:57', 8, 'Comunidad X', 'Calle Falsa 123', -17.77, -63.18, 'Provincia Andrés Ibáñez');
INSERT INTO public.destino VALUES ('2025-11-25 22:32:32', '2025-11-25 22:32:32', 9, 'Univalle', 'Séptimo Anillo, 24 de Septiembre, Santa Cruz de la Sierra', -17.721609, -63.176172, 'Santa Cruz');


--
-- TOC entry 5086 (class 0 OID 49517)
-- Dependencies: 235
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.estado VALUES (1, 'Pendiente', '2025-11-10 18:21:28', '2025-11-10 18:21:28');
INSERT INTO public.estado VALUES (2, 'En Camino', '2025-11-10 18:21:39', '2025-11-10 18:21:39');
INSERT INTO public.estado VALUES (3, 'Entregado', '2025-11-10 18:21:49', '2025-11-10 18:21:49');


--
-- TOC entry 5080 (class 0 OID 49479)
-- Dependencies: 229
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5096 (class 0 OID 49580)
-- Dependencies: 245
-- Data for Name: historial_seguimiento_donaciones; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.historial_seguimiento_donaciones VALUES (1, '7567717', '2025-11-10 18:22:37', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGTKnJ5v9gkP5r9RpWwSddP_6cx8FMoSRZEQ&s', 2, 1, '2025-11-10 18:22:37', '2025-11-10 18:22:37', 'Pendiente', NULL, NULL, NULL);
INSERT INTO public.historial_seguimiento_donaciones VALUES (2, '7567717', '2025-11-17 19:17:45', NULL, 4, 3, '2025-11-17 19:17:45', '2025-11-17 19:17:45', 'En Camino', NULL, NULL, NULL);
INSERT INTO public.historial_seguimiento_donaciones VALUES (3, '7567717', '2025-11-24 19:10:15', NULL, 5, 4, '2025-11-24 19:10:15', '2025-11-24 19:10:15', 'En Camino', NULL, NULL, NULL);
INSERT INTO public.historial_seguimiento_donaciones VALUES (4, NULL, '2025-11-25 04:13:34', NULL, 5, 5, '2025-11-25 04:13:34', '2025-11-25 04:13:34', 'En Camino', 'Luis Villafani', '7567715', '8547DFG');
INSERT INTO public.historial_seguimiento_donaciones VALUES (5, NULL, '2025-11-25 04:19:46', NULL, 2, 6, '2025-11-25 04:19:46', '2025-11-25 04:19:46', 'Pendiente', 'Ana Villafani', '7567717', '3659KNY');
INSERT INTO public.historial_seguimiento_donaciones VALUES (6, NULL, '2025-11-25 16:48:08', NULL, 4, 7, '2025-11-25 16:48:08', '2025-11-25 16:48:08', 'En Camino', 'Ariana Ortiz', '89652304', '8547DFG');
INSERT INTO public.historial_seguimiento_donaciones VALUES (7, NULL, '2025-11-25 17:12:05', NULL, 5, 8, '2025-11-25 17:12:05', '2025-11-25 17:12:05', 'En Camino', 'Ana Villafani', '7567717', '3659KNY');
INSERT INTO public.historial_seguimiento_donaciones VALUES (8, NULL, '2025-11-25 18:44:00', NULL, 2, 9, '2025-11-25 18:44:00', '2025-11-25 18:44:00', 'En Camino', 'Ana Villafani', '7567717', '3659KNY');
INSERT INTO public.historial_seguimiento_donaciones VALUES (9, NULL, '2025-11-25 22:52:08', NULL, 10, 10, '2025-11-25 22:52:08', '2025-11-25 22:52:08', 'Pendiente', 'Ana Villafani', '7567717', '3659KNY');
INSERT INTO public.historial_seguimiento_donaciones VALUES (10, NULL, '2025-11-25 22:59:14', NULL, 11, 11, '2025-11-25 22:59:14', '2025-11-25 22:59:14', 'En Camino', 'Luis Villafani', '7567715', '8547DFG');
INSERT INTO public.historial_seguimiento_donaciones VALUES (11, NULL, '2025-11-26 02:55:06', NULL, 4, 12, '2025-11-26 02:55:06', '2025-11-26 02:55:06', 'En Camino', 'Ariana Ortiz', '89652304', '8547DFG');
INSERT INTO public.historial_seguimiento_donaciones VALUES (12, NULL, '2025-11-26 03:03:41', NULL, 5, 13, '2025-11-26 03:03:41', '2025-11-26 03:03:41', 'En Camino', 'Ana Villafani', '7567717', '3659KNY');
INSERT INTO public.historial_seguimiento_donaciones VALUES (13, NULL, '2025-11-26 03:11:31', NULL, 5, 14, '2025-11-26 03:11:31', '2025-11-26 03:11:31', 'En Camino', 'Mia Villafani', '75674852', '4696SSN');


--
-- TOC entry 5078 (class 0 OID 49471)
-- Dependencies: 227
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5077 (class 0 OID 49462)
-- Dependencies: 226
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5110 (class 0 OID 57891)
-- Dependencies: 259
-- Data for Name: marca; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.marca VALUES (1, 'Ford');


--
-- TOC entry 5069 (class 0 OID 49414)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.migrations VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO public.migrations VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO public.migrations VALUES (4, '2025_10_29_070859_create_solicitud_table', 1);
INSERT INTO public.migrations VALUES (5, '2025_10_29_074822_create_donacion_table', 1);
INSERT INTO public.migrations VALUES (6, '2025_10_29_150504_create_estado_table', 1);
INSERT INTO public.migrations VALUES (7, '2025_10_29_163508_add_campos_to_solicitud_table', 1);
INSERT INTO public.migrations VALUES (8, '2025_11_01_000001_fix_solicitud_pk_and_donacion_fk', 1);
INSERT INTO public.migrations VALUES (9, '2025_11_01_185505_create_destino_table', 1);
INSERT INTO public.migrations VALUES (10, '2025_11_01_185820_create_solicitante_table', 1);
INSERT INTO public.migrations VALUES (11, '2025_11_01_190916_create_ubicacion_table', 1);
INSERT INTO public.migrations VALUES (12, '2025_11_02_151322_create_usuario_table', 1);
INSERT INTO public.migrations VALUES (13, '2025_11_02_160217_create_historial_seguimiento_donaciones_table', 1);
INSERT INTO public.migrations VALUES (14, '2025_11_03_020445_create_historial_seguimiento_donaciones_table', 1);
INSERT INTO public.migrations VALUES (15, '2025_11_03_022838_rename_donacion_to_paquete', 1);
INSERT INTO public.migrations VALUES (16, '2025_11_03_023014_rename_donacion_pk_and_historial_fk', 1);
INSERT INTO public.migrations VALUES (17, '2025_11_09_135606_renombrado_columnas_tabla_user', 1);
INSERT INTO public.migrations VALUES (18, '2025_11_09_140151_corregir_split_nombres', 1);
INSERT INTO public.migrations VALUES (19, '2025_11_09_155104_prune_estado_columns', 1);
INSERT INTO public.migrations VALUES (20, '2025_11_09_155137_normalize_solicitud_relations', 1);
INSERT INTO public.migrations VALUES (21, '2025_11_09_200446_add_user_fk_to_historial', 1);
INSERT INTO public.migrations VALUES (22, '2025_11_09_201552_add_estado_id_to_paquete_and_historial', 1);
INSERT INTO public.migrations VALUES (23, '2025_11_09_201700_add_paquete_fk_to_reporte', 1);
INSERT INTO public.migrations VALUES (24, '2025_11_09_204743_revert_historial_estado_to_text', 1);
INSERT INTO public.migrations VALUES (25, '2025_11_10_023905_reshape_paquete_historial_solicitud', 1);
INSERT INTO public.migrations VALUES (26, '2025_11_10_043049_add_id_encargado_to_paquete', 1);
INSERT INTO public.migrations VALUES (27, '2025_11_10_053137_fix_id_encargado_type_on_paquete', 1);
INSERT INTO public.migrations VALUES (28, '2025_11_10_064754_remove_campos_from_paquete_table', 1);
INSERT INTO public.migrations VALUES (29, '2025_11_10_152240_cleanup_old_foreign_keys_and_usuario_table', 2);
INSERT INTO public.migrations VALUES (30, '2025_11_10_154549_remove_name_column_from_users_table', 3);
INSERT INTO public.migrations VALUES (31, '2025_11_10_185243_update_imagen_evidencia_length_in_historial_table', 4);
INSERT INTO public.migrations VALUES (32, '2025_11_15_202647_add_transporte_tables', 5);
INSERT INTO public.migrations VALUES (33, '2025_11_15_204749_add_marca_to_vehiculo_table', 6);
INSERT INTO public.migrations VALUES (34, '2025_11_16_183032_add_tipo_emergencia_table', 7);
INSERT INTO public.migrations VALUES (35, '2025_11_18_010947_modify_paquete_seguimiento', 8);
INSERT INTO public.migrations VALUES (36, '2025_11_18_013413_create_rol_and_marca_tables', 9);
INSERT INTO public.migrations VALUES (37, '2025_11_18_015934_add_foreign_keys_to_solicitud_vehiculo_users', 10);
INSERT INTO public.migrations VALUES (38, '2025_11_24_213102_create_personal_access_tokens_table', 11);


--
-- TOC entry 5084 (class 0 OID 49501)
-- Dependencies: 233
-- Data for Name: paquete; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.paquete VALUES (2, 1, 'Univalle - (-17.723306, -63.174361)', '2025-11-10', NULL, '2025-11-10 18:22:37', '2025-11-25 18:44:00', 2, NULL, NULL, 'paquetes/Cnm9vSHfdGMeg2ZjjcjAwEWDQmT7VZoYPAuqVmIg.jpg', NULL, NULL, NULL, 2, 1);
INSERT INTO public.paquete VALUES (9, 3, NULL, '2025-11-10', NULL, '2025-11-25 21:41:56', '2025-11-25 21:41:56', 1, NULL, NULL, 'SIN_IMAGEN', NULL, NULL, NULL, NULL, NULL);
INSERT INTO public.paquete VALUES (10, 9, 'Sausalito - (-17.717658, -63.173427)', '2025-11-10', NULL, '2025-11-25 22:33:44', '2025-11-25 22:52:08', 1, NULL, NULL, 'paquetes/XlvEHgHcxfhKMVATgbTJBWG1zBjitFopUeDx8Fi2.jpg', NULL, NULL, NULL, 2, 1);
INSERT INTO public.paquete VALUES (11, 5, 'Remanso - (-17.717658, -63.173427)', '2025-11-10', NULL, '2025-11-25 22:58:25', '2025-11-25 22:59:14', 2, NULL, NULL, 'paquetes/SJ12mNM9kkgKJvjhqkePZkFTLQ8M7MaycakUYywi.jpg', NULL, NULL, NULL, 4, 2);
INSERT INTO public.paquete VALUES (4, 1, '(-17.724211, -63.170150)', '2025-11-10', NULL, '2025-11-17 19:17:45', '2025-11-26 02:55:06', 2, NULL, NULL, 'paquetes/BnV71dbjD39nZgyctwx5e0olmJ4ZPJ5iADukZf2t.png', NULL, NULL, NULL, 1, 2);
INSERT INTO public.paquete VALUES (5, 7, 'Genesis - (-17.724211, -63.170150)', '2025-11-10', NULL, '2025-11-23 15:01:45', '2025-11-26 03:11:31', 2, NULL, NULL, 'paquetes/pve7eEtI0JeJO8Tyys6TXC6e5EvIonjAUZTxo4Z7.jpg', NULL, NULL, NULL, 5, 3);


--
-- TOC entry 5072 (class 0 OID 49431)
-- Dependencies: 221
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5112 (class 0 OID 66066)
-- Dependencies: 261
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.personal_access_tokens VALUES (1, 'App\Models\User', 2, 'mobile_token', '4a918e03ed5b5a88c09618cc382c6828ea9c9ec145d2374b01f74255bb21b551', '["*"]', NULL, NULL, '2025-11-25 02:28:28', '2025-11-25 02:28:28');
INSERT INTO public.personal_access_tokens VALUES (2, 'App\Models\User', 2, 'mobile_token', 'cf604718c112d59c8c6be49eb5625afa72b40ef2ae93b1e3aae7d3bd94ceaa3a', '["*"]', NULL, NULL, '2025-11-25 17:51:04', '2025-11-25 17:51:04');
INSERT INTO public.personal_access_tokens VALUES (3, 'App\Models\User', 2, 'mobile_token', 'a566ee8a5e1ef1f8fd1ce618350af78f504d821a163c14b36e794c457b81ffc0', '["*"]', NULL, NULL, '2025-11-25 17:52:16', '2025-11-25 17:52:16');
INSERT INTO public.personal_access_tokens VALUES (4, 'App\Models\User', 2, 'mobile_token', 'af801c3df970f617438ecea5edb41a9b16fc63585490964bf9513e61d24585e2', '["*"]', NULL, NULL, '2025-11-25 19:53:20', '2025-11-25 19:53:20');
INSERT INTO public.personal_access_tokens VALUES (5, 'App\Models\User', 2, 'mobile_token', 'abc49acf3df1945bf82bf939980a4746036df54c7187a091b9e30237cce0673c', '["*"]', NULL, NULL, '2025-11-25 19:54:06', '2025-11-25 19:54:06');
INSERT INTO public.personal_access_tokens VALUES (6, 'App\Models\User', 2, 'mobile_token', 'db95392c0811801d2b097e1b0e2e9c585ea7ad3bbe6aa53e7a00e29f1e8f3c88', '["*"]', NULL, NULL, '2025-11-25 20:30:06', '2025-11-25 20:30:06');
INSERT INTO public.personal_access_tokens VALUES (7, 'App\Models\User', 2, 'mobile_token', 'e149be568422c07fbbc2ecb74be01f36c510e132e496248d1dbd1435e7e12304', '["*"]', NULL, NULL, '2025-11-25 20:31:33', '2025-11-25 20:31:33');
INSERT INTO public.personal_access_tokens VALUES (8, 'App\Models\User', 2, 'mobile_token', 'af635b8e47c4791c57878ee54362e9e3d20630d99b5da31b0beeb555f93437c8', '["*"]', NULL, NULL, '2025-11-25 20:31:49', '2025-11-25 20:31:49');
INSERT INTO public.personal_access_tokens VALUES (9, 'App\Models\User', 2, 'mobile_token', 'b997666a27ccc92c00af1dc18969c5bb70ac46492bc1d41e6d51dc662ad1e0be', '["*"]', NULL, NULL, '2025-11-26 03:34:28', '2025-11-26 03:34:28');


--
-- TOC entry 5094 (class 0 OID 49571)
-- Dependencies: 243
-- Data for Name: reporte; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.reporte VALUES (1, '/Downloads/DAS_REPORTES', '2025-11-10', '2025', '2025-11-10 18:36:50', '2025-11-10 18:36:50', 2);


--
-- TOC entry 5108 (class 0 OID 57884)
-- Dependencies: 257
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.rol VALUES (1, 'Administrador');
INSERT INTO public.rol VALUES (2, 'Voluntario');
INSERT INTO public.rol VALUES (3, 'Voluntario - Conductor');


--
-- TOC entry 5073 (class 0 OID 49438)
-- Dependencies: 222
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.sessions VALUES ('vVMZTsH6Lr4Q8IuMo9VBUlf5U3NcauKbFAYG4TT1', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidWljaFZNcXA4Uml6TTN5TDR6RWtQUlo1RkVielNnRkZPT1VOYVV6cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXF1ZXRlLzUvZWRpdCI7czo1OiJyb3V0ZSI7czoxMjoicGFxdWV0ZS5lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjQxMjQyMDU7fX0=', 1764126617);


--
-- TOC entry 5090 (class 0 OID 49540)
-- Dependencies: 239
-- Data for Name: solicitante; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.solicitante VALUES (1, 'Nobre', '13014416', 'heldernobre@gmail.com', 'Helder', '77012120', '2025-11-10 18:20:57', '2025-11-10 18:20:57');
INSERT INTO public.solicitante VALUES (3, 'Parada', '7691607', 'isabellaparada16@gmail.com', 'Isabella', '78458813', '2025-11-10 19:23:53', '2025-11-10 19:23:53');
INSERT INTO public.solicitante VALUES (4, 'Villafani', '7567714', 'luisvillafani@gmail.com', 'Luis', '75678744', '2025-11-15 22:22:38', '2025-11-15 22:22:38');
INSERT INTO public.solicitante VALUES (5, 'Pérez', '12345678', 'juan@example.com', 'Juan', '70000000', '2025-11-23 05:24:01', '2025-11-23 05:24:01');
INSERT INTO public.solicitante VALUES (6, 'Crespo', '11820564', 'sofiacrespor@gmail.com', 'Sofia', '77322548', '2025-11-25 22:32:32', '2025-11-25 22:32:32');


--
-- TOC entry 5082 (class 0 OID 49491)
-- Dependencies: 231
-- Data for Name: solicitud; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.solicitud VALUES (6, 'negada', 'ABC123', '2025-11-23 05:24:01', '2025-11-23 14:53:51', 5, '2025-11-23', 'Incendio', 'Agua, comida, medicamentos', 5, 6, '2025-11-23', false, false, 'Solicitud duplicada', 2);
INSERT INTO public.solicitud VALUES (7, 'aprobada', 'ABC123', '2025-11-23 05:25:46', '2025-11-23 15:01:45', 5, '2025-11-23', 'Incendio', 'Agua, comida, medicamentos', 5, 7, '2025-11-23', true, false, NULL, 2);
INSERT INTO public.solicitud VALUES (1, 'aprobada', 'U-297', '2025-11-10 18:20:57', '2025-11-25 13:49:02', 750, '2025-11-01', 'Incendio', 'Enlatados varios, agua en botella, bebidas isotónicas, pantalones antifugos, guantes antifugos', 1, 1, '2025-11-10', true, false, NULL, 2);
INSERT INTO public.solicitud VALUES (8, 'pendiente', 'ABC123', '2025-11-25 14:37:57', '2025-11-25 14:37:57', 5, '2025-11-23', 'Incendio', 'Agua, comida, medicamentos', 5, 8, '2025-11-23', false, false, NULL, 2);
INSERT INTO public.solicitud VALUES (2, 'aprobada', 'CEP-128', '2025-11-10 19:21:35', '2025-11-25 20:32:56', 1554, '2025-11-01', 'Recaudacion', 'Enlatados, jugo en caja, frazadas', 1, 2, '2025-11-10', true, false, NULL, NULL);
INSERT INTO public.solicitud VALUES (4, 'aprobada', 'SO-835', '2025-11-10 19:25:16', '2025-11-25 21:40:38', 50, '2025-11-06', 'Recaudacion', 'Frazadas, ropa de invierno, mochilas, cascos', 3, 4, '2025-11-10', true, false, NULL, NULL);
INSERT INTO public.solicitud VALUES (3, 'aprobada', 'M-981', '2025-11-10 19:23:53', '2025-11-25 21:41:56', 250, '2025-11-09', 'Incendio', 'Insumos antifugos, Agua en botella, bebidas isotonicas, mangueras', 3, 3, '2025-11-10', true, false, NULL, NULL);
INSERT INTO public.solicitud VALUES (9, 'aprobada', 'UUX-9863', '2025-11-25 22:32:32', '2025-11-25 22:33:44', 525, '2025-11-13', 'Incendio', 'Comida, agua, frazadas', 6, 9, '2025-11-25', true, false, NULL, 2);
INSERT INTO public.solicitud VALUES (5, 'aprobada', 'SO-291', '2025-11-15 22:22:38', '2025-11-25 22:58:25', 200, '2025-11-07', 'Inundacion', 'Agua en botella, frazadas, colchones', 4, 5, '2025-11-15', true, false, NULL, NULL);


--
-- TOC entry 5106 (class 0 OID 57867)
-- Dependencies: 255
-- Data for Name: tipo_emergencia; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tipo_emergencia VALUES (2, 'Incendio', 3);


--
-- TOC entry 5098 (class 0 OID 49675)
-- Dependencies: 247
-- Data for Name: tipo_licencia; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tipo_licencia VALUES (1, 'B');
INSERT INTO public.tipo_licencia VALUES (2, 'C');


--
-- TOC entry 5102 (class 0 OID 49696)
-- Dependencies: 251
-- Data for Name: tipo_vehiculo; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tipo_vehiculo VALUES (1, 'Camioneta Doble Cabina');


--
-- TOC entry 5092 (class 0 OID 49551)
-- Dependencies: 241
-- Data for Name: ubicacion; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.ubicacion VALUES (1, -17.721995775101004, -63.17454938465861, 'Santa Cruz de la Sierra', '2025-11-10 18:22:37', '2025-11-10 18:22:37');
INSERT INTO public.ubicacion VALUES (3, -10, -10, 'Campus UCB', '2025-11-17 19:17:45', '2025-11-17 19:17:45');
INSERT INTO public.ubicacion VALUES (4, -17.730768, -63.171379, 'UCEBOL', '2025-11-24 19:10:15', '2025-11-24 19:10:15');
INSERT INTO public.ubicacion VALUES (5, -17.720934, -63.17015, 'Valle Azul zona de Condominios', '2025-11-25 04:13:34', '2025-11-25 04:13:34');
INSERT INTO public.ubicacion VALUES (6, -17.720934, -63.17015, 'Valle Azul 2', '2025-11-25 04:19:46', '2025-11-25 04:19:46');
INSERT INTO public.ubicacion VALUES (7, -17.719478, -63.171808, 'Palladio', '2025-11-25 16:48:08', '2025-11-25 16:48:08');
INSERT INTO public.ubicacion VALUES (8, -17.719478, -63.171808, 'Norte', '2025-11-25 17:12:05', '2025-11-25 17:12:05');
INSERT INTO public.ubicacion VALUES (9, -17.723306, -63.174361, 'Univalle', '2025-11-25 18:44:00', '2025-11-25 18:44:00');
INSERT INTO public.ubicacion VALUES (10, -17.717658, -63.173427, 'Sausalito', '2025-11-25 22:52:08', '2025-11-25 22:52:08');
INSERT INTO public.ubicacion VALUES (11, -17.717658, -63.173427, 'Remanso', '2025-11-25 22:59:14', '2025-11-25 22:59:14');
INSERT INTO public.ubicacion VALUES (12, -17.724211, -63.17015, NULL, '2025-11-26 02:55:06', '2025-11-26 02:55:06');
INSERT INTO public.ubicacion VALUES (13, -17.724211, -63.17015, NULL, '2025-11-26 03:03:41', '2025-11-26 03:03:41');
INSERT INTO public.ubicacion VALUES (14, -17.724211, -63.17015, 'Genesis', '2025-11-26 03:11:31', '2025-11-26 03:11:31');


--
-- TOC entry 5071 (class 0 OID 49421)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.users VALUES (2, 'anagvillafanis@gmail.com', NULL, '$2y$12$tXN3ckj8ox/hFb2M5ZtqW.hYBF2ElTqUqLTlGnkx5YfbEimBlxfE.', NULL, '2025-11-10 15:46:19', '2025-11-24 01:44:33', 'Ana', 'Villafani', '7567717', '77312304', false, true, 2);
INSERT INTO public.users VALUES (3, 'luigivillafani@gmail.com', NULL, '$2y$12$i1f7RRtCQZYD4LV.F5vJIuPvtx9BDaePG123KMpWzkU2LEorOwzaC', NULL, '2025-11-24 01:38:16', '2025-11-24 02:13:05', 'Luis', 'Villafani', '7567715', '75678421', false, true, 3);


--
-- TOC entry 5104 (class 0 OID 49703)
-- Dependencies: 253
-- Data for Name: vehiculo; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.vehiculo VALUES (1, '3659KNY', '500', 1, '2015', 'Hunter', 'Ford', 1);
INSERT INTO public.vehiculo VALUES (2, '8547DFG', '1250', 1, '2018', 'Raptor', NULL, 1);
INSERT INTO public.vehiculo VALUES (3, '4696SSN', '200', 1, '2018', 'WRV', NULL, 1);


--
-- TOC entry 5139 (class 0 OID 0)
-- Dependencies: 248
-- Name: conductor_conductor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.conductor_conductor_id_seq', 5, true);


--
-- TOC entry 5140 (class 0 OID 0)
-- Dependencies: 236
-- Name: destino_id_destino_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.destino_id_destino_seq', 9, true);


--
-- TOC entry 5141 (class 0 OID 0)
-- Dependencies: 232
-- Name: donacion_id_donacion_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.donacion_id_donacion_seq', 11, true);


--
-- TOC entry 5142 (class 0 OID 0)
-- Dependencies: 234
-- Name: estado_id_estado_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.estado_id_estado_seq', 3, true);


--
-- TOC entry 5143 (class 0 OID 0)
-- Dependencies: 228
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 244
-- Name: historial_seguimiento_donaciones_id_historial_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.historial_seguimiento_donaciones_id_historial_seq', 13, true);


--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 225
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 258
-- Name: marca_id_marca_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.marca_id_marca_seq', 1, true);


--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 38, true);


--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 260
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 9, true);


--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 242
-- Name: reporte_id_reporte_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.reporte_id_reporte_seq', 1, true);


--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 256
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 3, true);


--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 238
-- Name: solicitante_id_solicitante_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.solicitante_id_solicitante_seq', 6, true);


--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 230
-- Name: solicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.solicitud_id_seq', 9, true);


--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 254
-- Name: tipo_emergencia_id_emergencia_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipo_emergencia_id_emergencia_seq', 2, true);


--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 246
-- Name: tipo_licencia_id_licencia_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipo_licencia_id_licencia_seq', 2, true);


--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 250
-- Name: tipo_vehiculo_id_tipovehiculo_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipo_vehiculo_id_tipovehiculo_seq', 2, true);


--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 240
-- Name: ubicacion_id_ubicacion_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ubicacion_id_ubicacion_seq', 14, true);


--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 252
-- Name: vehiculo_id_vehiculo_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.vehiculo_id_vehiculo_seq', 3, true);


--
-- TOC entry 4854 (class 2606 OID 49460)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4852 (class 2606 OID 49453)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4889 (class 2606 OID 49689)
-- Name: conductor conductor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.conductor
    ADD CONSTRAINT conductor_pkey PRIMARY KEY (conductor_id);


--
-- TOC entry 4872 (class 2606 OID 49538)
-- Name: destino destino_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.destino
    ADD CONSTRAINT destino_pkey PRIMARY KEY (id_destino);


--
-- TOC entry 4867 (class 2606 OID 49510)
-- Name: paquete donacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT donacion_pkey PRIMARY KEY (id_paquete);


--
-- TOC entry 4870 (class 2606 OID 49524)
-- Name: estado estado_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.estado
    ADD CONSTRAINT estado_pkey PRIMARY KEY (id_estado);


--
-- TOC entry 4861 (class 2606 OID 49487)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4863 (class 2606 OID 49489)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4885 (class 2606 OID 49587)
-- Name: historial_seguimiento_donaciones historial_seguimiento_donaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historial_seguimiento_donaciones
    ADD CONSTRAINT historial_seguimiento_donaciones_pkey PRIMARY KEY (id_historial);


--
-- TOC entry 4859 (class 2606 OID 49477)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4856 (class 2606 OID 49469)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4901 (class 2606 OID 57896)
-- Name: marca marca_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.marca
    ADD CONSTRAINT marca_pkey PRIMARY KEY (id_marca);


--
-- TOC entry 4838 (class 2606 OID 49419)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4846 (class 2606 OID 49437)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4904 (class 2606 OID 66073)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4906 (class 2606 OID 66076)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4880 (class 2606 OID 49578)
-- Name: reporte reporte_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reporte
    ADD CONSTRAINT reporte_pkey PRIMARY KEY (id_reporte);


--
-- TOC entry 4899 (class 2606 OID 57889)
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- TOC entry 4849 (class 2606 OID 49444)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4874 (class 2606 OID 49549)
-- Name: solicitante solicitante_ci_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitante
    ADD CONSTRAINT solicitante_ci_unique UNIQUE (ci);


--
-- TOC entry 4876 (class 2606 OID 49547)
-- Name: solicitante solicitante_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitante
    ADD CONSTRAINT solicitante_pkey PRIMARY KEY (id_solicitante);


--
-- TOC entry 4865 (class 2606 OID 49499)
-- Name: solicitud solicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitud
    ADD CONSTRAINT solicitud_pkey PRIMARY KEY (id_solicitud);


--
-- TOC entry 4897 (class 2606 OID 57872)
-- Name: tipo_emergencia tipo_emergencia_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_emergencia
    ADD CONSTRAINT tipo_emergencia_pkey PRIMARY KEY (id_emergencia);


--
-- TOC entry 4887 (class 2606 OID 49680)
-- Name: tipo_licencia tipo_licencia_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_licencia
    ADD CONSTRAINT tipo_licencia_pkey PRIMARY KEY (id_licencia);


--
-- TOC entry 4891 (class 2606 OID 49701)
-- Name: tipo_vehiculo tipo_vehiculo_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_vehiculo
    ADD CONSTRAINT tipo_vehiculo_pkey PRIMARY KEY (id_tipovehiculo);


--
-- TOC entry 4878 (class 2606 OID 49556)
-- Name: ubicacion ubicacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ubicacion
    ADD CONSTRAINT ubicacion_pkey PRIMARY KEY (id_ubicacion);


--
-- TOC entry 4840 (class 2606 OID 49609)
-- Name: users users_ci_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_ci_unique UNIQUE (ci);


--
-- TOC entry 4842 (class 2606 OID 49430)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (correo_electronico);


--
-- TOC entry 4844 (class 2606 OID 49428)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4893 (class 2606 OID 49708)
-- Name: vehiculo vehiculo_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vehiculo
    ADD CONSTRAINT vehiculo_pkey PRIMARY KEY (id_vehiculo);


--
-- TOC entry 4895 (class 2606 OID 49715)
-- Name: vehiculo vehiculo_placa_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vehiculo
    ADD CONSTRAINT vehiculo_placa_unique UNIQUE (placa);


--
-- TOC entry 4881 (class 1259 OID 49598)
-- Name: historial_seguimiento_donaciones_id_donacion_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX historial_seguimiento_donaciones_id_donacion_index ON public.historial_seguimiento_donaciones USING btree (id_paquete);


--
-- TOC entry 4882 (class 1259 OID 49600)
-- Name: historial_seguimiento_donaciones_id_paquete_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX historial_seguimiento_donaciones_id_paquete_index ON public.historial_seguimiento_donaciones USING btree (id_paquete);


--
-- TOC entry 4883 (class 1259 OID 49599)
-- Name: historial_seguimiento_donaciones_id_ubicacion_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX historial_seguimiento_donaciones_id_ubicacion_index ON public.historial_seguimiento_donaciones USING btree (id_ubicacion);


--
-- TOC entry 4857 (class 1259 OID 49470)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4902 (class 1259 OID 66077)
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- TOC entry 4907 (class 1259 OID 66074)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4847 (class 1259 OID 49446)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4850 (class 1259 OID 49445)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 4868 (class 1259 OID 49655)
-- Name: uq_paquete_codigo; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uq_paquete_codigo ON public.paquete USING btree (codigo) WHERE (codigo IS NOT NULL);


--
-- TOC entry 4920 (class 2606 OID 49690)
-- Name: conductor conductor_id_licencia_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.conductor
    ADD CONSTRAINT conductor_id_licencia_foreign FOREIGN KEY (id_licencia) REFERENCES public.tipo_licencia(id_licencia) ON DELETE SET NULL;


--
-- TOC entry 4918 (class 2606 OID 49601)
-- Name: historial_seguimiento_donaciones historial_seguimiento_donaciones_id_paquete_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historial_seguimiento_donaciones
    ADD CONSTRAINT historial_seguimiento_donaciones_id_paquete_foreign FOREIGN KEY (id_paquete) REFERENCES public.paquete(id_paquete);


--
-- TOC entry 4919 (class 2606 OID 49593)
-- Name: historial_seguimiento_donaciones historial_seguimiento_donaciones_id_ubicacion_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historial_seguimiento_donaciones
    ADD CONSTRAINT historial_seguimiento_donaciones_id_ubicacion_foreign FOREIGN KEY (id_ubicacion) REFERENCES public.ubicacion(id_ubicacion);


--
-- TOC entry 4912 (class 2606 OID 49625)
-- Name: paquete paquete_estado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT paquete_estado_id_foreign FOREIGN KEY (estado_id) REFERENCES public.estado(id_estado) ON DELETE SET NULL;


--
-- TOC entry 4913 (class 2606 OID 57873)
-- Name: paquete paquete_id_conductor_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT paquete_id_conductor_foreign FOREIGN KEY (id_conductor) REFERENCES public.conductor(conductor_id) ON DELETE SET NULL;


--
-- TOC entry 4914 (class 2606 OID 49668)
-- Name: paquete paquete_id_encargado_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT paquete_id_encargado_foreign FOREIGN KEY (id_encargado) REFERENCES public.users(ci) ON DELETE SET NULL;


--
-- TOC entry 4915 (class 2606 OID 49640)
-- Name: paquete paquete_id_solicitud_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT paquete_id_solicitud_foreign FOREIGN KEY (id_solicitud) REFERENCES public.solicitud(id_solicitud) ON DELETE SET NULL;


--
-- TOC entry 4916 (class 2606 OID 57878)
-- Name: paquete paquete_id_vehiculo_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paquete
    ADD CONSTRAINT paquete_id_vehiculo_foreign FOREIGN KEY (id_vehiculo) REFERENCES public.vehiculo(id_vehiculo) ON DELETE SET NULL;


--
-- TOC entry 4917 (class 2606 OID 49635)
-- Name: reporte reporte_id_paquete_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reporte
    ADD CONSTRAINT reporte_id_paquete_foreign FOREIGN KEY (id_paquete) REFERENCES public.paquete(id_paquete) ON DELETE SET NULL;


--
-- TOC entry 4909 (class 2606 OID 49615)
-- Name: solicitud solicitud_id_destino_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitud
    ADD CONSTRAINT solicitud_id_destino_foreign FOREIGN KEY (id_destino) REFERENCES public.destino(id_destino) ON DELETE SET NULL;


--
-- TOC entry 4910 (class 2606 OID 49610)
-- Name: solicitud solicitud_id_solicitante_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitud
    ADD CONSTRAINT solicitud_id_solicitante_foreign FOREIGN KEY (id_solicitante) REFERENCES public.solicitante(id_solicitante) ON DELETE SET NULL;


--
-- TOC entry 4911 (class 2606 OID 57897)
-- Name: solicitud solicitud_id_tipoemergencia_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitud
    ADD CONSTRAINT solicitud_id_tipoemergencia_foreign FOREIGN KEY (id_tipoemergencia) REFERENCES public.tipo_emergencia(id_emergencia) ON DELETE SET NULL;


--
-- TOC entry 4908 (class 2606 OID 57907)
-- Name: users users_id_rol_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_rol_foreign FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol) ON DELETE SET NULL;


--
-- TOC entry 4921 (class 2606 OID 57902)
-- Name: vehiculo vehiculo_id_marca_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vehiculo
    ADD CONSTRAINT vehiculo_id_marca_foreign FOREIGN KEY (id_marca) REFERENCES public.marca(id_marca) ON DELETE SET NULL;


--
-- TOC entry 4922 (class 2606 OID 49709)
-- Name: vehiculo vehiculo_id_tipovehiculo_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vehiculo
    ADD CONSTRAINT vehiculo_id_tipovehiculo_foreign FOREIGN KEY (id_tipovehiculo) REFERENCES public.tipo_vehiculo(id_tipovehiculo) ON DELETE SET NULL;


-- Completed on 2025-11-26 00:41:10

--
-- PostgreSQL database dump complete
--

\unrestrict jmbm3DShDWcqQyXnwyIbN4oZhiQS21JaO8obtGgeoUl4X9vFraIvjYLgJNJlUCr

