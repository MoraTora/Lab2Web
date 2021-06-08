--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 13.2

-- Started on 2021-04-20 22:48:43

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 203 (class 1259 OID 16420)
-- Name: photoLabPosts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."picture" (
    "pictureId" integer NOT NULL,
    description character varying NOT NULL,
    "pictureDate" date NOT NULL,
    "pictureRaiting" real,
    ratings integer[],
    "urlPhoto" character varying NOT NULL,
    "userEmail" character varying NOT NULL
);


ALTER TABLE public."picture" OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 16430)
-- Name: picture_pictureId_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public."picture" ALTER COLUMN "pictureId" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."picture_pictureId_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 202 (class 1259 OID 16412)
-- Name: photoLabUsers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."users" (
    usersId integer NOT NULL,
    firstname character varying NOT NULL,
    secondname character varying NOT NULL,
    fathername character varying,
    email character varying NOT NULL,
    hashpassword character varying NOT NULL
);


ALTER TABLE public."users" OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16428)
-- Name: users_usersId_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public."users" ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."users_usersId_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 206 (class 1259 OID 16432)
-- Name: postsRatings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."pictureRatings" (
    "ratingId" integer NOT NULL,
    "pictureId" integer NOT NULL,
    "userEmail" character varying NOT NULL,
    "valueRating" integer NOT NULL
);


ALTER TABLE public."pictureRatings" OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 16440)
-- Name: postsRating_ratingId_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public."pictureRatings" ALTER COLUMN "ratingId" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."postsRating_ratingId_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 2835 (class 0 OID 16420)
-- Dependencies: 203
-- Data for Name: picture; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."picture" ("pictureId", description, "pictureDate", "pictureRaiting", ratings, "urlPhoto", "userEmail") FROM stdin;
11	Тестовое описание 2	2021-04-15	5	{32}	../userPhotos/2.jpg	Des1337481@gmail.com
10	Тестовое описание	2021-04-12	5	{31}	../userPhotos/1.jpg	Des1337481@gmail.com
15	Пост 3	2021-04-20	5	{34}	../userPhotos/c43f2e062ac3dc14f09a5220b71eea81.jpeg	Des1337481@gmail.com
\.


--
-- TOC entry 2834 (class 0 OID 16412)
-- Dependencies: 202
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."users" (id, firstname, secondname, fathername, email, hashpassword) FROM stdin;
2	Artem	Sukhorukikh	Olegovich	Des1337481@gmail.com	$2y$10$NNd/.8hGQltsfnzpEfL9t.IpaOaWLAPAl./PhbCCuxwujCyqlzL5O
3	test	test	test	test1@test.ru	$2y$10$PS/b6idwW8tJrzdCVlInr.U8ovlIY./c5B6MgOPL5Q2EuI6Gr/zUq
6	Artem	Osipov	Aleksandrovich	arti_man1997@mail.ru	$2y$10$rmpcQPWE9gBKDMhJKlPiQO0nVMwftNDA5rNmg2/qlmNipDU1MVXxO
\.


--
-- TOC entry 2838 (class 0 OID 16432)
-- Dependencies: 206
-- Data for Name: pictureRatings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."pictureRatings" ("ratingId", "pictureId", "userEmail", "valueRating") FROM stdin;
31	10	arti_man1997@mail.ru	5
32	11	arti_man1997@mail.ru	5
34	15	arti_man1997@mail.ru	5
\.


--
-- TOC entry 2849 (class 0 OID 0)
-- Dependencies: 205
-- Name: picture_pictureId_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."picture_pictureId_seq"', 15, true);


--
-- TOC entry 2850 (class 0 OID 0)
-- Dependencies: 204
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."users_id_seq"', 6, true);


--
-- TOC entry 2851 (class 0 OID 0)
-- Dependencies: 207
-- Name: postsRating_ratingId_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."postsRating_ratingId_seq"', 34, true);


--
-- TOC entry 2705 (class 2606 OID 16427)
-- Name: picture picture_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."picture"
    ADD CONSTRAINT "picture_pkey" PRIMARY KEY ("pictureId");


--
-- TOC entry 2703 (class 2606 OID 16419)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."users"
    ADD CONSTRAINT "users_pkey" PRIMARY KEY (id);


--
-- TOC entry 2707 (class 2606 OID 16439)
-- Name: pictureRatings postsRating_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."pictureRatings"
    ADD CONSTRAINT "postsRating_pkey" PRIMARY KEY ("ratingId");


--
-- TOC entry 2845 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE "picture"; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,UPDATE ON TABLE public."picture" TO "user";


--
-- TOC entry 2846 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE "users"; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,UPDATE ON TABLE public."users" TO "user";


--
-- TOC entry 2847 (class 0 OID 0)
-- Dependencies: 206
-- Name: TABLE "pictureRatings"; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT ON TABLE public."pictureRatings" TO "user";


--
-- TOC entry 2848 (class 0 OID 0)
-- Dependencies: 206 2847
-- Name: COLUMN "pictureRatings"."ratingId"; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT("ratingId") ON TABLE public."pictureRatings" TO "user";


-- Completed on 2021-04-20 22:48:43

--
-- PostgreSQL database dump complete
--

