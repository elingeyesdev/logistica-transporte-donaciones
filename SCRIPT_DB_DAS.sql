-- DROP SCHEMA public;

CREATE SCHEMA public AUTHORIZATION pg_database_owner;

-- DROP SEQUENCE public.conductor_conductor_id_seq;

CREATE SEQUENCE public.conductor_conductor_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.destino_id_destino_seq;

CREATE SEQUENCE public.destino_id_destino_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.donacion_id_donacion_seq;

CREATE SEQUENCE public.donacion_id_donacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.estado_id_estado_seq;

CREATE SEQUENCE public.estado_id_estado_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.failed_jobs_id_seq;

CREATE SEQUENCE public.failed_jobs_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.historial_seguimiento_donaciones_id_historial_seq;

CREATE SEQUENCE public.historial_seguimiento_donaciones_id_historial_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.jobs_id_seq;

CREATE SEQUENCE public.jobs_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.marca_id_marca_seq;

CREATE SEQUENCE public.marca_id_marca_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.migrations_id_seq;

CREATE SEQUENCE public.migrations_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.permissions_id_seq;

CREATE SEQUENCE public.permissions_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.personal_access_tokens_id_seq;

CREATE SEQUENCE public.personal_access_tokens_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.reporte_id_reporte_seq;

CREATE SEQUENCE public.reporte_id_reporte_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.rol_id_rol_seq;

CREATE SEQUENCE public.rol_id_rol_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.roles_id_seq;

CREATE SEQUENCE public.roles_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.solicitante_id_solicitante_seq;

CREATE SEQUENCE public.solicitante_id_solicitante_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.solicitud_id_seq;

CREATE SEQUENCE public.solicitud_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tipo_emergencia_id_emergencia_seq;

CREATE SEQUENCE public.tipo_emergencia_id_emergencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tipo_licencia_id_licencia_seq;

CREATE SEQUENCE public.tipo_licencia_id_licencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tipo_vehiculo_id_tipovehiculo_seq;

CREATE SEQUENCE public.tipo_vehiculo_id_tipovehiculo_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.ubicacion_id_ubicacion_seq;

CREATE SEQUENCE public.ubicacion_id_ubicacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.users_id_seq;

CREATE SEQUENCE public.users_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.vehiculo_id_vehiculo_seq;

CREATE SEQUENCE public.vehiculo_id_vehiculo_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;-- public."cache" definition

-- Drop table

-- DROP TABLE public."cache";

CREATE TABLE public."cache" (
	"key" varchar(255) NOT NULL,
	value text NOT NULL,
	expiration int4 NOT NULL,
	CONSTRAINT cache_pkey PRIMARY KEY (key)
);


-- public.cache_locks definition

-- Drop table

-- DROP TABLE public.cache_locks;

CREATE TABLE public.cache_locks (
	"key" varchar(255) NOT NULL,
	"owner" varchar(255) NOT NULL,
	expiration int4 NOT NULL,
	CONSTRAINT cache_locks_pkey PRIMARY KEY (key)
);


-- public.destino definition

-- Drop table

-- DROP TABLE public.destino;

CREATE TABLE public.destino (
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	id_destino serial4 NOT NULL,
	comunidad varchar(255) NULL,
	direccion varchar(255) NULL,
	latitud float8 NULL,
	longitud float8 NULL,
	provincia varchar(255) NULL,
	CONSTRAINT destino_pkey PRIMARY KEY (id_destino)
);


-- public.estado definition

-- Drop table

-- DROP TABLE public.estado;

CREATE TABLE public.estado (
	id_estado bigserial NOT NULL,
	nombre_estado varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT estado_pkey PRIMARY KEY (id_estado)
);


-- public.failed_jobs definition

-- Drop table

-- DROP TABLE public.failed_jobs;

CREATE TABLE public.failed_jobs (
	id bigserial NOT NULL,
	"uuid" varchar(255) NOT NULL,
	"connection" text NOT NULL,
	queue text NOT NULL,
	payload text NOT NULL,
	"exception" text NOT NULL,
	failed_at timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL,
	CONSTRAINT failed_jobs_pkey PRIMARY KEY (id),
	CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid)
);


-- public.job_batches definition

-- Drop table

-- DROP TABLE public.job_batches;

CREATE TABLE public.job_batches (
	id varchar(255) NOT NULL,
	"name" varchar(255) NOT NULL,
	total_jobs int4 NOT NULL,
	pending_jobs int4 NOT NULL,
	failed_jobs int4 NOT NULL,
	failed_job_ids text NOT NULL,
	"options" text NULL,
	cancelled_at int4 NULL,
	created_at int4 NOT NULL,
	finished_at int4 NULL,
	CONSTRAINT job_batches_pkey PRIMARY KEY (id)
);


-- public.jobs definition

-- Drop table

-- DROP TABLE public.jobs;

CREATE TABLE public.jobs (
	id bigserial NOT NULL,
	queue varchar(255) NOT NULL,
	payload text NOT NULL,
	attempts int2 NOT NULL,
	reserved_at int4 NULL,
	available_at int4 NOT NULL,
	created_at int4 NOT NULL,
	CONSTRAINT jobs_pkey PRIMARY KEY (id)
);
CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


-- public.marca definition

-- Drop table

-- DROP TABLE public.marca;

CREATE TABLE public.marca (
	id_marca bigserial NOT NULL,
	nombre_marca varchar(255) NOT NULL,
	CONSTRAINT marca_pkey PRIMARY KEY (id_marca)
);


-- public.migrations definition

-- Drop table

-- DROP TABLE public.migrations;

CREATE TABLE public.migrations (
	id serial4 NOT NULL,
	migration varchar(255) NOT NULL,
	batch int4 NOT NULL,
	CONSTRAINT migrations_pkey PRIMARY KEY (id)
);


-- public.password_reset_tokens definition

-- Drop table

-- DROP TABLE public.password_reset_tokens;

CREATE TABLE public.password_reset_tokens (
	email varchar(255) NOT NULL,
	"token" varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email)
);


-- public.permissions definition

-- Drop table

-- DROP TABLE public.permissions;

CREATE TABLE public.permissions (
	id bigserial NOT NULL,
	"name" varchar(255) NOT NULL,
	guard_name varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name),
	CONSTRAINT permissions_pkey PRIMARY KEY (id)
);


-- public.personal_access_tokens definition

-- Drop table

-- DROP TABLE public.personal_access_tokens;

CREATE TABLE public.personal_access_tokens (
	id bigserial NOT NULL,
	tokenable_type varchar(255) NOT NULL,
	tokenable_id int8 NOT NULL,
	"name" text NOT NULL,
	"token" varchar(64) NOT NULL,
	abilities text NULL,
	last_used_at timestamp(0) NULL,
	expires_at timestamp(0) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id),
	CONSTRAINT personal_access_tokens_token_unique UNIQUE (token)
);
CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


-- public.rol definition

-- Drop table

-- DROP TABLE public.rol;

CREATE TABLE public.rol (
	id_rol bigserial NOT NULL,
	titulo_rol varchar(255) NOT NULL,
	CONSTRAINT rol_pkey PRIMARY KEY (id_rol)
);


-- public.roles definition

-- Drop table

-- DROP TABLE public.roles;

CREATE TABLE public.roles (
	id bigserial NOT NULL,
	"name" varchar(255) NOT NULL,
	guard_name varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name),
	CONSTRAINT roles_pkey PRIMARY KEY (id)
);


-- public.sessions definition

-- Drop table

-- DROP TABLE public.sessions;

CREATE TABLE public.sessions (
	id varchar(255) NOT NULL,
	user_id int8 NULL,
	ip_address varchar(45) NULL,
	user_agent text NULL,
	payload text NOT NULL,
	last_activity int4 NOT NULL,
	CONSTRAINT sessions_pkey PRIMARY KEY (id)
);
CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);
CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


-- public.solicitante definition

-- Drop table

-- DROP TABLE public.solicitante;

CREATE TABLE public.solicitante (
	id_solicitante serial4 NOT NULL,
	apellido varchar(255) NULL,
	ci varchar(255) NULL,
	email varchar(255) NULL,
	nombre varchar(255) NULL,
	telefono varchar(255) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT solicitante_ci_unique UNIQUE (ci),
	CONSTRAINT solicitante_pkey PRIMARY KEY (id_solicitante)
);


-- public.tipo_emergencia definition

-- Drop table

-- DROP TABLE public.tipo_emergencia;

CREATE TABLE public.tipo_emergencia (
	id_emergencia bigserial NOT NULL,
	emergencia varchar(255) NOT NULL,
	prioridad int4 NOT NULL,
	CONSTRAINT tipo_emergencia_pkey PRIMARY KEY (id_emergencia)
);


-- public.tipo_licencia definition

-- Drop table

-- DROP TABLE public.tipo_licencia;

CREATE TABLE public.tipo_licencia (
	id_licencia serial4 NOT NULL,
	licencia varchar(100) NOT NULL,
	CONSTRAINT tipo_licencia_pkey PRIMARY KEY (id_licencia)
);


-- public.tipo_vehiculo definition

-- Drop table

-- DROP TABLE public.tipo_vehiculo;

CREATE TABLE public.tipo_vehiculo (
	id_tipovehiculo serial4 NOT NULL,
	nombre_tipo_vehiculo varchar(100) NOT NULL,
	CONSTRAINT tipo_vehiculo_pkey PRIMARY KEY (id_tipovehiculo)
);


-- public.ubicacion definition

-- Drop table

-- DROP TABLE public.ubicacion;

CREATE TABLE public.ubicacion (
	id_ubicacion bigserial NOT NULL,
	latitud float8 NULL,
	longitud float8 NULL,
	zona varchar(255) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT ubicacion_pkey PRIMARY KEY (id_ubicacion)
);


-- public.conductor definition

-- Drop table

-- DROP TABLE public.conductor;

CREATE TABLE public.conductor (
	conductor_id serial4 NOT NULL,
	nombre varchar(255) NOT NULL,
	apellido varchar(255) NOT NULL,
	fecha_nacimiento date NOT NULL,
	ci varchar(50) NOT NULL,
	celular varchar(20) NOT NULL,
	id_licencia int4 NOT NULL,
	CONSTRAINT conductor_pkey PRIMARY KEY (conductor_id),
	CONSTRAINT conductor_id_licencia_foreign FOREIGN KEY (id_licencia) REFERENCES public.tipo_licencia(id_licencia) ON DELETE SET NULL
);


-- public.model_has_permissions definition

-- Drop table

-- DROP TABLE public.model_has_permissions;

CREATE TABLE public.model_has_permissions (
	permission_id int8 NOT NULL,
	model_type varchar(255) NOT NULL,
	model_id int8 NOT NULL,
	CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type),
	CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE
);
CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


-- public.model_has_roles definition

-- Drop table

-- DROP TABLE public.model_has_roles;

CREATE TABLE public.model_has_roles (
	role_id int8 NOT NULL,
	model_type varchar(255) NOT NULL,
	model_id int8 NOT NULL,
	CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type),
	CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE
);
CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


-- public.role_has_permissions definition

-- Drop table

-- DROP TABLE public.role_has_permissions;

CREATE TABLE public.role_has_permissions (
	permission_id int8 NOT NULL,
	role_id int8 NOT NULL,
	CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id),
	CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE,
	CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE
);


-- public.solicitud definition

-- Drop table

-- DROP TABLE public.solicitud;

CREATE TABLE public.solicitud (
	id_solicitud int8 DEFAULT nextval('solicitud_id_seq'::regclass) NOT NULL,
	estado varchar(255) DEFAULT 'pendiente'::character varying NOT NULL,
	codigo_seguimiento varchar(255) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	cantidad_personas int4 NULL,
	fecha_inicio date NULL,
	tipo_emergencia varchar(255) NULL,
	insumos_necesarios text NULL,
	id_solicitante int4 NULL,
	id_destino int4 NULL,
	fecha_solicitud date NULL,
	aprobada bool DEFAULT false NOT NULL,
	apoyoaceptado bool DEFAULT false NOT NULL,
	justificacion varchar(255) NULL,
	id_tipoemergencia int8 NULL,
	fecha_necesidad date NULL,
	nombre_referencia varchar(255) NULL,
	celular_referencia int4 NULL,
	ci_voluntario varchar(255) NULL,
	CONSTRAINT solicitud_pkey PRIMARY KEY (id_solicitud),
	CONSTRAINT solicitud_id_destino_foreign FOREIGN KEY (id_destino) REFERENCES public.destino(id_destino) ON DELETE SET NULL,
	CONSTRAINT solicitud_id_solicitante_foreign FOREIGN KEY (id_solicitante) REFERENCES public.solicitante(id_solicitante) ON DELETE SET NULL,
	CONSTRAINT solicitud_id_tipoemergencia_foreign FOREIGN KEY (id_tipoemergencia) REFERENCES public.tipo_emergencia(id_emergencia) ON DELETE SET NULL
);


-- public.users definition

-- Drop table

-- DROP TABLE public.users;

CREATE TABLE public.users (
	id bigserial NOT NULL,
	correo_electronico varchar(255) NOT NULL,
	email_verified_at timestamp(0) NULL,
	"password" varchar(255) NOT NULL,
	remember_token varchar(100) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	nombre varchar(255) NULL,
	apellido varchar(255) NULL,
	ci varchar(255) NULL,
	telefono varchar(255) NULL,
	administrador bool DEFAULT false NOT NULL,
	activo bool DEFAULT true NOT NULL,
	id_rol int8 NULL,
	CONSTRAINT users_ci_unique UNIQUE (ci),
	CONSTRAINT users_email_unique UNIQUE (correo_electronico),
	CONSTRAINT users_pkey PRIMARY KEY (id),
	CONSTRAINT users_id_rol_foreign FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol) ON DELETE SET NULL
);


-- public.vehiculo definition

-- Drop table

-- DROP TABLE public.vehiculo;

CREATE TABLE public.vehiculo (
	id_vehiculo serial4 NOT NULL,
	placa varchar(50) NOT NULL,
	capacidad_aproximada varchar(50) NULL,
	id_tipovehiculo int4 NULL,
	modelo_anio varchar(10) NULL,
	modelo varchar(100) NULL,
	marca varchar(100) NULL,
	id_marca int8 NULL,
	color varchar(50) DEFAULT 'Otro'::character varying NOT NULL,
	CONSTRAINT vehiculo_pkey PRIMARY KEY (id_vehiculo),
	CONSTRAINT vehiculo_placa_unique UNIQUE (placa),
	CONSTRAINT vehiculo_id_marca_foreign FOREIGN KEY (id_marca) REFERENCES public.marca(id_marca) ON DELETE SET NULL,
	CONSTRAINT vehiculo_id_tipovehiculo_foreign FOREIGN KEY (id_tipovehiculo) REFERENCES public.tipo_vehiculo(id_tipovehiculo) ON DELETE SET NULL
);


-- public.paquete definition

-- Drop table

-- DROP TABLE public.paquete;

CREATE TABLE public.paquete (
	id_paquete int8 DEFAULT nextval('donacion_id_donacion_seq'::regclass) NOT NULL,
	id_solicitud int8 NULL,
	ubicacion_actual varchar(255) NULL,
	fecha_creacion date DEFAULT '2025-11-10'::date NOT NULL,
	fecha_entrega date NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	estado_id int4 NULL,
	codigo varchar(16) NULL,
	fecha_aprobacion date NULL,
	imagen varchar(255) NULL,
	id_encargado varchar(255) NULL,
	user_id int8 NULL,
	id_ubicacion int4 NULL,
	id_conductor int4 NULL,
	id_vehiculo int4 NULL,
	CONSTRAINT donacion_pkey PRIMARY KEY (id_paquete),
	CONSTRAINT paquete_estado_id_foreign FOREIGN KEY (estado_id) REFERENCES public.estado(id_estado) ON DELETE SET NULL,
	CONSTRAINT paquete_id_conductor_foreign FOREIGN KEY (id_conductor) REFERENCES public.conductor(conductor_id) ON DELETE SET NULL,
	CONSTRAINT paquete_id_encargado_foreign FOREIGN KEY (id_encargado) REFERENCES public.users(ci) ON DELETE SET NULL,
	CONSTRAINT paquete_id_solicitud_foreign FOREIGN KEY (id_solicitud) REFERENCES public.solicitud(id_solicitud) ON DELETE SET NULL,
	CONSTRAINT paquete_id_vehiculo_foreign FOREIGN KEY (id_vehiculo) REFERENCES public.vehiculo(id_vehiculo) ON DELETE SET NULL
);
CREATE UNIQUE INDEX uq_paquete_codigo ON public.paquete USING btree (codigo) WHERE (codigo IS NOT NULL);


-- public.reporte definition

-- Drop table

-- DROP TABLE public.reporte;

CREATE TABLE public.reporte (
	id_reporte serial4 NOT NULL,
	fecha_reporte date NULL,
	gestion varchar(255) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	id_paquete int4 NULL,
	nombre_pdf varchar(255) NULL,
	ruta_pdf varchar(255) NULL,
	CONSTRAINT reporte_pkey PRIMARY KEY (id_reporte),
	CONSTRAINT reporte_id_paquete_foreign FOREIGN KEY (id_paquete) REFERENCES public.paquete(id_paquete) ON DELETE SET NULL
);


-- public.historial_seguimiento_donaciones definition

-- Drop table

-- DROP TABLE public.historial_seguimiento_donaciones;

CREATE TABLE public.historial_seguimiento_donaciones (
	id_historial serial4 NOT NULL,
	ci_usuario varchar(255) NULL,
	fecha_actualizacion timestamp(0) NULL,
	imagen_evidencia text NULL,
	id_paquete int4 NULL,
	id_ubicacion int4 NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	estado varchar(255) NULL,
	conductor_nombre varchar(255) NULL,
	conductor_ci varchar(50) NULL,
	vehiculo_placa varchar(50) NULL,
	CONSTRAINT historial_seguimiento_donaciones_pkey PRIMARY KEY (id_historial),
	CONSTRAINT historial_seguimiento_donaciones_id_paquete_foreign FOREIGN KEY (id_paquete) REFERENCES public.paquete(id_paquete),
	CONSTRAINT historial_seguimiento_donaciones_id_ubicacion_foreign FOREIGN KEY (id_ubicacion) REFERENCES public.ubicacion(id_ubicacion)
);
CREATE INDEX historial_seguimiento_donaciones_id_donacion_index ON public.historial_seguimiento_donaciones USING btree (id_paquete);
CREATE INDEX historial_seguimiento_donaciones_id_paquete_index ON public.historial_seguimiento_donaciones USING btree (id_paquete);
CREATE INDEX historial_seguimiento_donaciones_id_ubicacion_index ON public.historial_seguimiento_donaciones USING btree (id_ubicacion);
