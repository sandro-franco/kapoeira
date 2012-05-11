--
-- PostgreSQL database dump
--

-- Started on 2012-05-10 17:39:36 BRT

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 1536 (class 1262 OID 244238)
-- Name: kapoeira; Type: DATABASE; Schema: -; Owner: kapoeira
--

CREATE DATABASE kapoeira WITH TEMPLATE = template0 ENCODING = 'UTF8';


ALTER DATABASE kapoeira OWNER TO kapoeira;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 111 (class 1259 OID 244268)
-- Dependencies: 5
-- Name: seq_atividades; Type: SEQUENCE; Schema: public; Owner: kapoeira
--

CREATE SEQUENCE seq_atividades
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.seq_atividades OWNER TO kapoeira;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 113 (class 1259 OID 244332)
-- Dependencies: 1520 1521 1522 5
-- Name: atividades; Type: TABLE; Schema: public; Owner: kapoeira; Tablespace: 
--

CREATE TABLE atividades (
    codigo integer DEFAULT nextval('seq_atividades'::regclass) NOT NULL,
    descricao character varying(150) NOT NULL,
    cod_projeto integer NOT NULL,
    cod_situacao integer NOT NULL,
    cod_esforco integer,
    concluida boolean DEFAULT false,
    data_inicio date,
    data_fim date,
    esforco integer,
    ativa boolean DEFAULT true
);


ALTER TABLE public.atividades OWNER TO kapoeira;

--
-- TOC entry 114 (class 1259 OID 244458)
-- Dependencies: 5
-- Name: seq_esforco; Type: SEQUENCE; Schema: public; Owner: kapoeira
--

CREATE SEQUENCE seq_esforco
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.seq_esforco OWNER TO kapoeira;

--
-- TOC entry 112 (class 1259 OID 244327)
-- Dependencies: 1519 5
-- Name: esforco; Type: TABLE; Schema: public; Owner: kapoeira; Tablespace: 
--

CREATE TABLE esforco (
    codigo integer DEFAULT nextval('seq_esforco'::regclass) NOT NULL,
    descricao character varying(50) NOT NULL
);


ALTER TABLE public.esforco OWNER TO kapoeira;

--
-- TOC entry 109 (class 1259 OID 244261)
-- Dependencies: 5
-- Name: seq_projetos; Type: SEQUENCE; Schema: public; Owner: kapoeira
--

CREATE SEQUENCE seq_projetos
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.seq_projetos OWNER TO kapoeira;

--
-- TOC entry 110 (class 1259 OID 244263)
-- Dependencies: 1517 1518 5
-- Name: projetos; Type: TABLE; Schema: public; Owner: kapoeira; Tablespace: 
--

CREATE TABLE projetos (
    codigo integer DEFAULT nextval('seq_projetos'::regclass) NOT NULL,
    descricao character varying(150) NOT NULL,
    ativo boolean DEFAULT true
);


ALTER TABLE public.projetos OWNER TO kapoeira;

--
-- TOC entry 107 (class 1259 OID 244239)
-- Dependencies: 5
-- Name: seq_situacao; Type: SEQUENCE; Schema: public; Owner: kapoeira
--

CREATE SEQUENCE seq_situacao
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.seq_situacao OWNER TO kapoeira;

--
-- TOC entry 108 (class 1259 OID 244256)
-- Dependencies: 1516 5
-- Name: situacao; Type: TABLE; Schema: public; Owner: kapoeira; Tablespace: 
--

CREATE TABLE situacao (
    codigo integer DEFAULT nextval('seq_situacao'::regclass) NOT NULL,
    descricao character varying(50) NOT NULL,
    chave_situacao character varying(10) NOT NULL,
    ordem integer NOT NULL
);


ALTER TABLE public.situacao OWNER TO kapoeira;

--
-- TOC entry 1530 (class 2606 OID 244337)
-- Dependencies: 113 113
-- Name: atividades_pk; Type: CONSTRAINT; Schema: public; Owner: kapoeira; Tablespace: 
--

ALTER TABLE ONLY atividades
    ADD CONSTRAINT atividades_pk PRIMARY KEY (codigo);


--
-- TOC entry 1524 (class 2606 OID 244260)
-- Dependencies: 108 108
-- Name: colunas_pk; Type: CONSTRAINT; Schema: public; Owner: kapoeira; Tablespace: 
--

ALTER TABLE ONLY situacao
    ADD CONSTRAINT colunas_pk PRIMARY KEY (codigo);


--
-- TOC entry 1528 (class 2606 OID 244331)
-- Dependencies: 112 112
-- Name: esforco_pk; Type: CONSTRAINT; Schema: public; Owner: kapoeira; Tablespace: 
--

ALTER TABLE ONLY esforco
    ADD CONSTRAINT esforco_pk PRIMARY KEY (codigo);


--
-- TOC entry 1526 (class 2606 OID 244267)
-- Dependencies: 110 110
-- Name: projetos_pk; Type: CONSTRAINT; Schema: public; Owner: kapoeira; Tablespace: 
--

ALTER TABLE ONLY projetos
    ADD CONSTRAINT projetos_pk PRIMARY KEY (codigo);


--
-- TOC entry 1533 (class 2606 OID 244348)
-- Dependencies: 113 112 1527
-- Name: fk_esforco; Type: FK CONSTRAINT; Schema: public; Owner: kapoeira
--

ALTER TABLE ONLY atividades
    ADD CONSTRAINT fk_esforco FOREIGN KEY (cod_esforco) REFERENCES esforco(codigo);


--
-- TOC entry 1531 (class 2606 OID 244338)
-- Dependencies: 113 110 1525
-- Name: fk_projeto; Type: FK CONSTRAINT; Schema: public; Owner: kapoeira
--

ALTER TABLE ONLY atividades
    ADD CONSTRAINT fk_projeto FOREIGN KEY (cod_projeto) REFERENCES projetos(codigo);


--
-- TOC entry 1532 (class 2606 OID 244343)
-- Dependencies: 113 108 1523
-- Name: fk_situacao; Type: FK CONSTRAINT; Schema: public; Owner: kapoeira
--

ALTER TABLE ONLY atividades
    ADD CONSTRAINT fk_situacao FOREIGN KEY (cod_situacao) REFERENCES situacao(codigo);


--
-- TOC entry 1538 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-05-10 17:39:37 BRT

--
-- PostgreSQL database dump complete
--
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (1, 'Backlog', 'B', 1);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (2, 'Backlog Concluído', 'B_FINISH', 2);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (3, 'Requisitos', 'R', 3);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (4, 'Requisitos Concluídos', 'R_FINISH', 4);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (9, 'Implantação', 'P', 9);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (10, 'Implantação Concluída', 'P_FINISH', 10);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (5, 'Implementação', 'I', 5);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (6, 'Implementação Concluída', 'I_FINISH', 6);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (7, 'Testes', 'T', 7);
INSERT INTO situacao (codigo, descricao, chave_situacao, ordem) VALUES (8, 'Testes Concluídos', 'T_FINISH', 8);
