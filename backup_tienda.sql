--
-- PostgreSQL database dump
--

\restrict 7xcKmIuHE0lgQggBwheGD3fa4000zQvXcQ5xYyKhxdVRTUwfGOgGs6hKZDMn2LI

-- Dumped from database version 16.10
-- Dumped by pg_dump version 16.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: gasa
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


ALTER FUNCTION public.notify_messenger_messages() OWNER TO gasa;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: categoria; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.categoria (
    id integer NOT NULL,
    codigo character varying(6) NOT NULL,
    nombre character varying(255) NOT NULL
);


ALTER TABLE public.categoria OWNER TO gasa;

--
-- Name: categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.categoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categoria_id_seq OWNER TO gasa;

--
-- Name: categoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.categoria_id_seq OWNED BY public.categoria.id;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO gasa;

--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO gasa;

--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: gasa
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: gasa
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: gasa
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.messenger_messages_id_seq OWNER TO gasa;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: pedido; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.pedido (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha date NOT NULL,
    coste numeric(10,2) NOT NULL,
    code character varying(4)
);


ALTER TABLE public.pedido OWNER TO gasa;

--
-- Name: pedido_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.pedido_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedido_id_seq OWNER TO gasa;

--
-- Name: pedido_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.pedido_id_seq OWNED BY public.pedido.id;


--
-- Name: pedido_producto; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.pedido_producto (
    id integer NOT NULL,
    pedido_id integer NOT NULL,
    producto_id integer NOT NULL,
    unidades integer NOT NULL
);


ALTER TABLE public.pedido_producto OWNER TO gasa;

--
-- Name: pedido_producto_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.pedido_producto_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedido_producto_id_seq OWNER TO gasa;

--
-- Name: pedido_producto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.pedido_producto_id_seq OWNED BY public.pedido_producto.id;


--
-- Name: producto; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.producto (
    id integer NOT NULL,
    categoria_id integer NOT NULL,
    codigo character varying(6) NOT NULL,
    precio double precision NOT NULL,
    nombre character varying(255) NOT NULL,
    nombre_corto character varying(50) DEFAULT NULL::character varying,
    descripcion text NOT NULL
);


ALTER TABLE public.producto OWNER TO gasa;

--
-- Name: producto_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.producto_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.producto_id_seq OWNER TO gasa;

--
-- Name: producto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.producto_id_seq OWNED BY public.producto.id;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: gasa
--

CREATE TABLE public.usuario (
    id integer NOT NULL,
    login character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    number integer
);


ALTER TABLE public.usuario OWNER TO gasa;

--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: gasa
--

CREATE SEQUENCE public.usuario_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuario_id_seq OWNER TO gasa;

--
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gasa
--

ALTER SEQUENCE public.usuario_id_seq OWNED BY public.usuario.id;


--
-- Name: categoria id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.categoria ALTER COLUMN id SET DEFAULT nextval('public.categoria_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: pedido id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido ALTER COLUMN id SET DEFAULT nextval('public.pedido_id_seq'::regclass);


--
-- Name: pedido_producto id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido_producto ALTER COLUMN id SET DEFAULT nextval('public.pedido_producto_id_seq'::regclass);


--
-- Name: producto id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.producto ALTER COLUMN id SET DEFAULT nextval('public.producto_id_seq'::regclass);


--
-- Name: usuario id; Type: DEFAULT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id SET DEFAULT nextval('public.usuario_id_seq'::regclass);


--
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.categoria (id, codigo, nombre) FROM stdin;
1	CAT001	Perfumes
2	CAT002	Cosmetica
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20251007190052	2025-10-07 19:04:20	47
DoctrineMigrations\\Version20251014185659	2025-10-14 18:57:27	1
DoctrineMigrations\\Version20251014190501	2025-10-14 19:05:26	4
DoctrineMigrations\\Version20251028194638	2025-10-28 19:46:59	25
DoctrineMigrations\\Version20260113200453	2026-01-13 20:05:20	32
DoctrineMigrations\\Version20260121151504	2026-01-21 15:15:19	3
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
1	O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:16:\\"correo.html.twig\\";i:1;N;i:2;a:4:{s:9:\\"pedido_id\\";i:15;s:9:\\"productos\\";a:1:{s:6:\\"PER001\\";O:19:\\"App\\\\Entity\\\\Producto\\":7:{s:23:\\"\\0App\\\\Entity\\\\Producto\\0id\\";i:1;s:27:\\"\\0App\\\\Entity\\\\Producto\\0codigo\\";s:6:\\"PER001\\";s:27:\\"\\0App\\\\Entity\\\\Producto\\0precio\\";d:59.9;s:27:\\"\\0App\\\\Entity\\\\Producto\\0nombre\\";s:40:\\"Yves Saint Laurent Y Eau de Parfum 100ml\\";s:33:\\"\\0App\\\\Entity\\\\Producto\\0nombre_corto\\";s:9:\\"YSL Y EDP\\";s:32:\\"\\0App\\\\Entity\\\\Producto\\0descripcion\\";s:120:\\"El perfume de hombre Y Eau De Parfum de Yves Saint Laurent es una creación del legendario perfumista Dominique Ropion. \\";s:30:\\"\\0App\\\\Entity\\\\Producto\\0categoria\\";O:35:\\"Proxies\\\\__CG__\\\\App\\\\Entity\\\\Categoria\\":3:{s:24:\\"\\0App\\\\Entity\\\\Categoria\\0id\\";i:1;s:28:\\"\\0App\\\\Entity\\\\Categoria\\0codigo\\";N;s:28:\\"\\0App\\\\Entity\\\\Categoria\\0nombre\\";N;}}}s:8:\\"unidades\\";a:1:{s:6:\\"PER001\\";i:1;}s:5:\\"coste\\";d:59.9;}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:21:\\"gar.asat.96@gmail.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"gasa692@email.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Confirmación de pedido15\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;s:2:\\"es\\";}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}	[]	default	2026-01-27 20:05:25	2026-01-27 20:05:25	\N
2	O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:16:\\"correo.html.twig\\";i:1;N;i:2;a:4:{s:9:\\"pedido_id\\";i:16;s:9:\\"productos\\";a:1:{s:6:\\"PER001\\";O:19:\\"App\\\\Entity\\\\Producto\\":7:{s:23:\\"\\0App\\\\Entity\\\\Producto\\0id\\";i:1;s:27:\\"\\0App\\\\Entity\\\\Producto\\0codigo\\";s:6:\\"PER001\\";s:27:\\"\\0App\\\\Entity\\\\Producto\\0precio\\";d:59.9;s:27:\\"\\0App\\\\Entity\\\\Producto\\0nombre\\";s:40:\\"Yves Saint Laurent Y Eau de Parfum 100ml\\";s:33:\\"\\0App\\\\Entity\\\\Producto\\0nombre_corto\\";s:9:\\"YSL Y EDP\\";s:32:\\"\\0App\\\\Entity\\\\Producto\\0descripcion\\";s:120:\\"El perfume de hombre Y Eau De Parfum de Yves Saint Laurent es una creación del legendario perfumista Dominique Ropion. \\";s:30:\\"\\0App\\\\Entity\\\\Producto\\0categoria\\";O:35:\\"Proxies\\\\__CG__\\\\App\\\\Entity\\\\Categoria\\":3:{s:24:\\"\\0App\\\\Entity\\\\Categoria\\0id\\";i:1;s:28:\\"\\0App\\\\Entity\\\\Categoria\\0codigo\\";N;s:28:\\"\\0App\\\\Entity\\\\Categoria\\0nombre\\";N;}}}s:8:\\"unidades\\";a:1:{s:6:\\"PER001\\";i:1;}s:5:\\"coste\\";d:59.9;}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:21:\\"gar.asat.96@gmail.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:21:\\"gasa692@g.educaand.es\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Confirmación de pedido16\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;s:2:\\"es\\";}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}	[]	default	2026-01-27 20:07:13	2026-01-27 20:07:13	\N
3	O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:16:\\"correo.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"pedido_id\\";i:17;s:9:\\"productos\\";a:1:{i:0;a:5:{s:6:\\"codigo\\";s:6:\\"COS001\\";s:6:\\"nombre\\";s:17:\\"Gillete Labs 1 Up\\";s:11:\\"descripcion\\";s:211:\\"GILLETTE LABS con barra exfoliante de Gillette es la primera maquinilla de afeitar del mundo \n  con tecnología exfoliante integrada en el mango, para que afeitarte sea tan rápido y fácil como lavarte la cara.\\";s:6:\\"precio\\";d:16.95;s:8:\\"unidades\\";i:1;}}s:5:\\"coste\\";d:16.95;}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:21:\\"gar.asat.96@gmail.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:21:\\"gasa692@g.educaand.es\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Confirmación de pedido17\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;s:2:\\"es\\";}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}	[]	default	2026-01-28 15:15:07	2026-01-28 15:15:07	\N
\.


--
-- Data for Name: pedido; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.pedido (id, usuario_id, fecha, coste, code) FROM stdin;
27	2	2026-02-03	141.59	\N
28	2	2026-02-03	141.59	\N
29	2	2026-02-10	179.70	\N
\.


--
-- Data for Name: pedido_producto; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.pedido_producto (id, pedido_id, producto_id, unidades) FROM stdin;
47	27	3	2
48	27	6	1
49	27	7	2
50	27	5	1
51	28	3	2
52	28	6	1
53	28	7	2
54	28	5	1
55	29	1	3
\.


--
-- Data for Name: producto; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.producto (id, categoria_id, codigo, precio, nombre, nombre_corto, descripcion) FROM stdin;
1	1	PER001	59.9	Yves Saint Laurent Y Eau de Parfum 100ml	YSL Y EDP	El perfume de hombre Y Eau De Parfum de Yves Saint Laurent es una creación del legendario perfumista Dominique Ropion. 
2	1	PER002	74.95	Yves Saint Laurent Myslf Eau De Parfum 100ml	YSL Myslf EDP	MYSLF, la nueva fragancia masculina recargable de Yves Saint Laurent. La expresión del hombre que eres, con todas tus facetas y emociones. \n  Una afirmación de la masculinidad moderna, abrazando todas sus matices. El primer perfume de hombre floral amaderado de YSL BEAUTY para un \n  rastro contrastado de modernidad.
7	1	PER003	44.95	DOLCE & GABBANA, Light Blue Eau Intense, Eau de Parfum para hombre	Light Blue Eau Intense	El perfume para hombre Eau Intense Light Blue Pour Homme de Dolce & Gabbana tiene un aroma fresco y veraniego. Esta fragancia está inspirada en el océano, salado y profundo. Su aroma intenso es ideal para marineros de tierra que sueñan con navegar por el profundo y vasto océano.
3	2	COS001	16.95	GILLETTE Maquina Labs 1 Up Maquina de afeitar para Hombre	Gillete Labs 1 Up	GILLETTE LABS con barra exfoliante de Gillette es la primera maquinilla de afeitar del mundo \n  con tecnología exfoliante integrada en el mango, para que afeitarte sea tan rápido y fácil como lavarte la cara.
4	2	COS002	12.89	CERAVE Gel Limpiador Espumoso Para piel normal a grasa	Cerave Gel Limpiador	Limpieza profunda sin resecar, para piel normal a grasaEl Gel Limpiador Espumoso de CeraVe ha sido desarrollado con dermatólogos para proporcionar una limpieza eficaz sin alterar la barrera natural de la piel. Su textura gel que se transforma en espuma elimina el exceso de grasa, impurezas y suciedad, dejando la piel fresca, suave y equilibrada.
5	2	COS003	6.99	ARGANOUR Men Aceite De Barba 30ML, Acondicionador para barba	Arganour Aceite de Barba	Presume de una barba suave, fuerte, hidratada y sin picores con el aceite de barba de Arganour. Este acondicionador para barba está formulado a base de aceites vegetales que hidratan y nutren la barba, previniendo su encrespamiento y aportándole un extra de flexibilidad, brillo y suavidad.
6	2	COS004	10.8	THE ORDINARY, Niacinamide 10% + Zinc 1%, Fórmula antienrojecimiento de alta potencia	THE ORDINARY Niacinamide 10%	El sérum Niacinamide 10% + Zinc 1% es una fórmula a base de agua que ofrece los múltiples beneficios de la niacinamida para mejorar el brillo de la piel, mejorar la textura y reforzar la barrera de hidratación de la piel. Este sérum con niacinamida (vitamina B3) contiene una concentración alta (10 %) y zinc PCA para combatir la opacidad, la textura irregular y el exceso de grasa. Niacinamide 10% + Zinc 1% también ayuda a reducir la apariencia de los poros y aumenta al instante la luminosidad de la piel.
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: gasa
--

COPY public.usuario (id, login, roles, password, email, number) FROM stdin;
2	admin	["ROLE_ADMIN"]	$2y$13$xDsClTKKtUDY1btgzw.nm.upEIyhPX4jR.5D.jmnl17V8dA3BaTr.	gasa692@g.educaand.es	\N
4	garik	["ROLE_USER"]	$2y$13$AEFOcPR.GJ2ZS8TdPFZ5KO1uFa5XBTG95LGVCLzqyta90aDz8macW	asa7ur@gmail.com	666666666
\.


--
-- Name: categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.categoria_id_seq', 1, false);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 3, true);


--
-- Name: pedido_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.pedido_id_seq', 29, true);


--
-- Name: pedido_producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.pedido_producto_id_seq', 55, true);


--
-- Name: producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.producto_id_seq', 7, true);


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gasa
--

SELECT pg_catalog.setval('public.usuario_id_seq', 4, true);


--
-- Name: categoria categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: pedido pedido_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido
    ADD CONSTRAINT pedido_pkey PRIMARY KEY (id);


--
-- Name: pedido_producto pedido_producto_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido_producto
    ADD CONSTRAINT pedido_producto_pkey PRIMARY KEY (id);


--
-- Name: producto producto_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_pkey PRIMARY KEY (id);


--
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_a7bb06153397707a; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_a7bb06153397707a ON public.producto USING btree (categoria_id);


--
-- Name: idx_c4ec16cedb38439e; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_c4ec16cedb38439e ON public.pedido USING btree (usuario_id);


--
-- Name: idx_dd333c24854653a; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_dd333c24854653a ON public.pedido_producto USING btree (pedido_id);


--
-- Name: idx_dd333c27645698e; Type: INDEX; Schema: public; Owner: gasa
--

CREATE INDEX idx_dd333c27645698e ON public.pedido_producto USING btree (producto_id);


--
-- Name: uniq_identifier_login; Type: INDEX; Schema: public; Owner: gasa
--

CREATE UNIQUE INDEX uniq_identifier_login ON public.usuario USING btree (login);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: gasa
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: producto fk_a7bb06153397707a; Type: FK CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT fk_a7bb06153397707a FOREIGN KEY (categoria_id) REFERENCES public.categoria(id);


--
-- Name: pedido fk_c4ec16cedb38439e; Type: FK CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido
    ADD CONSTRAINT fk_c4ec16cedb38439e FOREIGN KEY (usuario_id) REFERENCES public.usuario(id);


--
-- Name: pedido_producto fk_dd333c24854653a; Type: FK CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido_producto
    ADD CONSTRAINT fk_dd333c24854653a FOREIGN KEY (pedido_id) REFERENCES public.pedido(id);


--
-- Name: pedido_producto fk_dd333c27645698e; Type: FK CONSTRAINT; Schema: public; Owner: gasa
--

ALTER TABLE ONLY public.pedido_producto
    ADD CONSTRAINT fk_dd333c27645698e FOREIGN KEY (producto_id) REFERENCES public.producto(id);


--
-- PostgreSQL database dump complete
--

\unrestrict 7xcKmIuHE0lgQggBwheGD3fa4000zQvXcQ5xYyKhxdVRTUwfGOgGs6hKZDMn2LI

