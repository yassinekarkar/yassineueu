
 DROP type if EXISTS client_type;

CREATE TYPE client_type AS ENUM (
	'PROFESSIONAL',
	'PARTICULAR'
	);

 DROP type if EXISTS ebill_type;

CREATE TYPE ebill_type AS ENUM (
	'VAT',
	'GLN',
	'DUNS',
	'IBAN',
	'OTHER'
	);

DROP TABLE  if exists public.bl_currency CASCADE;

CREATE TABLE public.bl_currency (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(255) NOT NULL,
	"shortname" varchar(255) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_currency_pkey PRIMARY KEY (id),
	CONSTRAINT idx_uq_currency_name UNIQUE ("name"),
	CONSTRAINT idx_uq_currency_shortname UNIQUE ("shortname")
);


DROP TABLE  if exists public.bl_country CASCADE;

CREATE TABLE public.bl_country (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(255) NOT NULL,
	"shortname" varchar(255) NOT NULL,
	"flag" varchar(255) NOT NULL,
	currency_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_country_pkey PRIMARY KEY (id),
	CONSTRAINT idx_uq_country_name UNIQUE ("name"),
	CONSTRAINT idx_uq_country_shortname UNIQUE ("shortname"),
	CONSTRAINT fk_country_currency FOREIGN KEY (currency_id) REFERENCES public.bl_currency(id) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE if exists public.bl_user;

DROP TABLE  if exists public.bl_vat;
DROP table if EXISTS public.bl_company CASCADE;

CREATE TABLE public.bl_company (
	id serial4 NOT NULL,
	code uuid NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(255) NOT NULL,
	registry_number varchar(255) NOT NULL,
	vat_number varchar(255) NOT NULL,
	vat_exempt bool NOT NULL DEFAULT false,
	logo varchar(255) NULL,
	firstname varchar(70) NOT NULL,
	lastname varchar(70) NOT NULL,
	address varchar(300) NULL DEFAULT NULL::character varying,
	zipcode varchar(15) NOT NULL,
	city varchar(15) NOT NULL,
	mail varchar(200)  NULL,
	phone varchar(15) NOT NULL,
	country_id int4 NOT NULL,
	paypal varchar(255) NULL,
	website varchar(400) NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_company_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_company_country FOREIGN KEY (country_id) REFERENCES public.bl_country(id) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE public.bl_user (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	company_id int4 NOT NULL,
	parent_user_id int4 NULL ,
	firstname varchar(70) NOT NULL,
	lastname varchar(70) NOT NULL,
	mail varchar(200) NOT NULL,
	"password" varchar(300) NULL DEFAULT NULL::character varying,
	"token" varchar(300) NOT NULL DEFAULT NULL::character varying,
	phone varchar(15) NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT bl_unique_user_code UNIQUE (code),
	CONSTRAINT bl_unique_user_mail UNIQUE (mail),
	CONSTRAINT bl_user_pkey PRIMARY KEY (id),
	CONSTRAINT bl_user_fkey FOREIGN KEY (parent_user_id) REFERENCES public.bl_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_user_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE
);




CREATE TABLE public.bl_vat (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	value numeric(10, 2) NOT NULL DEFAULT 0.00,
	company_id int4 NOT NULL,
	is_default bool NOT NULL DEFAULT false,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_vat_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_vat_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE  if exists public.bl_unit;

CREATE TABLE public.bl_unit (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(255) NOT NULL,
	company_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_unit_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_unit_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE  if exists public.bl_product;

CREATE TABLE public.bl_product (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(255) NOT NULL,
	description varchar(255) NOT NULL,
	unit_price numeric(10, 2) NOT NULL DEFAULT 0.00,
	vat_id int4 NOT NULL,
	unit_id int4 NOT NULL,
	company_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_product_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_product_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_product_unit FOREIGN KEY (unit_id) REFERENCES public.bl_unit(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_product_vat FOREIGN KEY (vat_id) REFERENCES public.bl_vat(id) ON DELETE CASCADE ON UPDATE CASCADE
);



DROP TABLE  if exists public.bl_iban;

CREATE TABLE public.bl_iban (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	iban varchar(400) NOT NULL,
	bank_name varchar(100) NOT NULL,
	company_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_iban_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_iban_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE if exists public.wording_translation;
DROP TABLE if exists public.wording;
DROP TABLE if exists public.wording_domain;

CREATE TABLE public.wording_domain (
	id int4 NOT NULL,
	code varchar(100) NOT NULL,
	"label" varchar(100) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT wording_domain_pkey PRIMARY KEY (id)
);

CREATE TABLE public.wording (
	id serial4 NOT NULL,
	code varchar(100) NOT NULL,
	"label" varchar(100) NOT NULL,
	domain_id int4 NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT wording_pkey PRIMARY KEY (id),
	CONSTRAINT fk_15f91dd2115f0ee5 FOREIGN KEY (domain_id) REFERENCES public.wording_domain(id)
);



CREATE TABLE public.wording_translation (
	id serial4 NOT NULL,
	wording_id int4 NULL,
	"content" varchar(200) NOT NULL,
	"language" varchar(2) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT wording_translation_pkey PRIMARY KEY (id),
	CONSTRAINT fk_353f7543d34102df FOREIGN KEY (wording_id) REFERENCES public.wording(id)
);



DROP table if exists  public.bl_tax_value;
DROP TABLE if exists  public.bl_tax;

CREATE TABLE public.bl_tax (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	"name" varchar(45) NOT NULL,
	default_value varchar(500) NULL,
	company_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT idx_unique_tax_label UNIQUE ("name"),
	CONSTRAINT bl_tax_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_tax_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE

);

CREATE TABLE public.bl_tax_value (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	tax_id int4 NOT NULL,
	value varchar(500) NOT NULL,
	date_begin timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	date_end timestamp NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL,
	CONSTRAINT bl_tax_value_value_pkey PRIMARY KEY (id),
	CONSTRAINT fk_tax_value FOREIGN KEY (tax_id) REFERENCES public.bl_tax(id) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE  if exists public.bl_payment_condition CASCADE;
CREATE TABLE public.bl_payment_condition (
	id serial4 NOT NULL,
	code varchar(255) NOT NULL DEFAULT uuid_generate_v4(),
	value int2 NOT NULL DEFAULT 0,
	company_id int4 NOT NULL,
	"default" bool NOT NULL DEFAULT false,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_payment_condition_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_payment_condition_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE CASCADE
);






DROP TABLE if exists public.bl_client;


CREATE TABLE public.bl_client (
	id serial4 NOT NULL,
	code uuid NOT NULL DEFAULT uuid_generate_v4(),
	"type" client_type NOT null DEFAULT 'PARTICULAR'::client_type,
	"name" varchar(255) NOT NULL,firstname varchar(70) NOT NULL,
	reference varchar(70) NOT NULL,
	lastname varchar(70) NOT NULL,
	registry_number varchar(255)  NULL,
	vat_number varchar(255)  NULL,
	address varchar(300) NULL DEFAULT NULL::character varying,
	zipcode varchar(15) NOT NULL,
	city varchar(15) NOT NULL,
	mail varchar(200)  NULL,
	phone varchar(15) NOT NULL,
	country_id int4 NOT NULL,
	company_id int4 NOT NULL,
	payment_condition_id int4 NOT NULL,
	ebill_identifier varchar(400) NULL,
	"ebill_type" ebill_type null,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_client_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_client_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_client_country FOREIGN KEY (country_id) REFERENCES public.bl_country(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_client_payment_condition FOREIGN KEY (payment_condition_id) REFERENCES public.bl_payment_condition(id) ON DELETE CASCADE ON UPDATE cascade

);


CREATE TYPE ebill_type AS ENUM (
        'VAT',
	'GLN',
	'DUNS',
	'IBAN',
	'OTHER'
	);

 DROP type if EXISTS invoice_type;

CREATE TYPE invoice_type AS ENUM (
	'CREDIT',
	'CLASSIC',
	'DEPOSIT'
	);

 DROP type if EXISTS ebill_status;

CREATE TYPE ebill_status AS ENUM (
	'DRAFT',
	'SENDED',
	'APPROVED',
	'DOWNLOADED',
	'DECLINED',
	'ACCEPTED',
	'DELAYED',
	'PAIED'
	);

DROP TABLE if exists public.bl_quote cascade;


CREATE TABLE public.bl_quote (
	id serial4 NOT NULL,
	code uuid NOT NULL DEFAULT uuid_generate_v4(),
	estimate_number varchar(255) NOT null,
	status ebill_status NOT null DEFAULT 'DRAFT'::ebill_status,
	pre_note varchar(500) NULL DEFAULT NULL::character varying,
	post_note varchar(500) NULL DEFAULT NULL::character varying,
	date_begin timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	date_end timestamp NOT NULL,
	discount  numeric(10, 2)  NULL,
	creator_id int4 NOT NULL,
	updator_id int4 ,
	company_id int4 NOT NULL,
	company_mail  varchar(200) NULL,
	company_name  varchar(255) NOT NULL,
	company_address varchar(300) NULL DEFAULT NULL::character varying,
	company_zipcode varchar(15) NOT NULL,
	company_city varchar(15) NOT NULL,
	client_id int4 NOT NULL,
	client_name  varchar(255) NOT NULL,
	client_address varchar(300) NULL DEFAULT NULL::character varying,
	client_zipcode varchar(15) NOT NULL,
	client_city varchar(15) NOT NULL,
    head varchar(100) NOT null DEFAULT 'DEVIS'::character varying,
    discount bool NOT NULL DEFAULT false,
    discount_on_total bool NULL ,
    discount_fixed_value bool NULL,
    discount_base_ttc bool NULL,
    language_id  int4 NOT NULL,
    currency_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_estimate_pkey PRIMARY KEY (id),
	CONSTRAINT fk_bl_estimate_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_estimate_creator FOREIGN KEY (creator_id) REFERENCES public.bl_user(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_estimate_updator FOREIGN KEY (updator_id) REFERENCES public.bl_user(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_bl_estimate_client FOREIGN KEY (client_id) REFERENCES public.bl_client(id) ON DELETE CASCADE ON UPDATE cascade,
    CONSTRAINT fk_bl_estimate_currency FOREIGN KEY (currency_id) REFERENCES public.bl_currency(id) ON DELETE CASCADE ON UPDATE cascade,
    CONSTRAINT fk_bl_estimate_language FOREIGN KEY (language_id) REFERENCES public.bl_language(id) ON DELETE CASCADE ON UPDATE cascade
);



DROP TABLE if exists public.bl_language cascade;

CREATE TABLE public.bl_language (
	id serial4 NOT NULL,
	code varchar(2) NOT NULL,
	"name" varchar(45) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp null,
	CONSTRAINT bl_language_pkey PRIMARY KEY (id)
	);


 DROP type if EXISTS ebill_total_line;

CREATE TYPE ebill_total_line AS ENUM (
	'TTC',
	'HT',
	'HT_TTC'
	);

DROP TABLE if exists public.bl_quote_config cascade;


CREATE TABLE public.bl_quote_config (
	id serial4 NOT NULL,
	code uuid NOT NULL DEFAULT uuid_generate_v4(),
	head varchar(100) NOT null DEFAULT 'DEVIS'::character varying,
	total_line ebill_total_line NOT null DEFAULT 'TTC'::ebill_total_line,
	discount bool NOT NULL DEFAULT false,
	discount_on_total bool NULL ,
	discount_fixed_value bool NULL,
	discount_base_ttc ebill_total_line NOT null DEFAULT 'TTC'::ebill_total_line,
	quote_id int4 NOT NULL,
	language_id  int4 NOT NULL,
	currency_id int4 NOT NULL,
	CONSTRAINT bl_quote_config_pkey PRIMARY KEY (id),
	CONSTRAINT fk_quote_config_currency FOREIGN KEY (currency_id) REFERENCES public.bl_currency(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_quote_config_language FOREIGN KEY (language_id) REFERENCES public.bl_language(id) ON DELETE CASCADE ON UPDATE cascade,
	CONSTRAINT fk_quote_config_quote FOREIGN KEY (quote_id) REFERENCES public.bl_quote(id) ON DELETE CASCADE ON UPDATE cascade

);


DROP TABLE  if exists public.bl_quote_product cascade;

 CREATE TABLE public.bl_quote_product (
    id serial4 NOT NULL,
    code uuid NOT NULL DEFAULT uuid_generate_v4(),
    name varchar(255) NOT NULL,
    porder int2 NOT NULL,
    unit_price numeric(10,2) NOT NULL DEFAULT 0.00,
    amount numeric(10, 2) NOT NULL DEFAULT 0.00,
    discount  numeric(10,2)  NULL,
    discount_fixed_value bool NULL,
    vat_id int4 NOT NULL,
    vat_value varchar(25) NOT NULL,
    unity_id int4 NOT NULL,
    quote_id int4  NULL,
    unity_value varchar(25) NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp null,
    CONSTRAINT bl_quote_product_pkey PRIMARY KEY (id),
    CONSTRAINT fk_bl_quote_product_unity FOREIGN KEY (unity_id) REFERENCES public.bl_unity(id) ON DELETE CASCADE ON UPDATE cascade,
    CONSTRAINT fk_bl_quote_product_quote FOREIGN KEY (quote_id) REFERENCES public.bl_quote(id) ON DELETE CASCADE ON UPDATE cascade,
    CONSTRAINT fk_bl_quote_product_vat FOREIGN KEY (vat_id) REFERENCES public.bl_vat(id) ON DELETE CASCADE ON UPDATE CASCADE
 );

 CREATE TABLE public.bl_facture (
                                    id serial4 NOT NULL,
                                    code uuid NOT NULL DEFAULT uuid_generate_v4(),
                                    reference varchar(255) NOT null,
                                    status ebill_status NOT null DEFAULT 'DRAFT'::ebill_status,
                                    invoice_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    due_date timestamp NOT NULL,
                                    creator_id int4 NOT NULL,
                                    updator_id int4 ,
                                    company_id int4 NOT NULL,
                                    company_mail  varchar(200) NULL,
                                    company_name  varchar(255) NOT NULL,
                                    company_address varchar(300) NULL DEFAULT NULL::character varying,
                                    company_zipcode varchar(15) NOT NULL,
                                    company_city varchar(15) NOT NULL,
                                    client_id int4 NOT NULL,
                                    client_name  varchar(255) NOT NULL,
                                    client_address varchar(300) NULL DEFAULT NULL::character varying,
                                    client_zipcode varchar(15) NOT NULL,
                                    client_city varchar(15) NOT NULL,
                                    head varchar(100) NOT null DEFAULT 'DEVIS'::character varying,
                                    discount bool NOT NULL DEFAULT false,
                                    discount_on_total bool NULL ,
                                    discount_fixed_value bool NULL,
                                    discount_base_ttc bool NULL,
                                    discount_total  numeric(10, 2)  NULL,
                                    acompte bool NOT NULL DEFAULT false,
                                    language_id  int4 NOT NULL,
                                    currency_id int4 NOT NULL,
                                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    updated_at timestamp null,
                                    CONSTRAINT bl_elbill_pkey PRIMARY KEY (id),
                                    CONSTRAINT fk_bl_estimate_company FOREIGN KEY (company_id) REFERENCES public.bl_company(id) ON DELETE CASCADE ON UPDATE cascade,
                                    CONSTRAINT fk_bl_estimate_creator FOREIGN KEY (creator_id) REFERENCES public.bl_user(id) ON DELETE CASCADE ON UPDATE cascade,
                                    CONSTRAINT fk_bl_estimate_updator FOREIGN KEY (updator_id) REFERENCES public.bl_user(id) ON DELETE CASCADE ON UPDATE cascade,
                                    CONSTRAINT fk_bl_estimate_client FOREIGN KEY (client_id) REFERENCES public.bl_client(id) ON DELETE CASCADE ON UPDATE cascade,
                                    CONSTRAINT fk_bl_estimate_currency FOREIGN KEY (currency_id) REFERENCES public.bl_currency(id) ON DELETE CASCADE ON UPDATE cascade,
                                    CONSTRAINT fk_bl_estimate_language FOREIGN KEY (language_id) REFERENCES public.bl_language(id) ON DELETE CASCADE ON UPDATE cascade
 );




alter table bl_iban ADD COLUMN is_default bool NOT NULL DEFAULT false;

ALTER TABLE bl_unit
RENAME TO bl_unity;

ALTER TABLE public.bl_product DROP constraint fk_bl_product_unit

ALTER TABLE public.bl_product ADD CONSTRAINT fk_bl_product_unity FOREIGN KEY (unity_id) REFERENCES bl_unity(id) ON UPDATE CASCADE ON DELETE cascade;


ALTER TYPE ebill_status ADD VALUE 'CANCELED';
ALTER TYPE ebill_status ADD VALUE 'INVOICED';




-- DROP TRIGGER IF EXISTS  tg_tax_value_insert on bl_tax_value ;
-- DROP FUNCTION IF EXISTS fct_tax_value_insert;
-- 
-- CREATE OR REPLACE FUNCTION fct_tax_value_insert() 
-- RETURNS trigger AS $$
--     BEGIN
--           
--         NEW.pt_alias =  generateAlias('P');
--        
--     RETURN NEW;
--     END;
--  
-- $$ LANGUAGE plpgsql;
-- 
-- -- Trigger sur parameter_value_insert
-- create trigger tg_tax_value_insert before
-- insert on
--  public.bl_tax_value for each row execute function fct_tax_value_insert();



DROP TRIGGER IF EXISTS  tg_tax_value_insert on bl_tax_value ;
DROP FUNCTION IF EXISTS fct_tax_value_insert;

CREATE OR REPLACE FUNCTION fct_tax_value_insert() 
RETURNS trigger AS $$
    BEGIN
            
        UPDATE bl_tax_value set updated_at = NOW()::timestamp, date_end = date_trunc('day', NEW.date_begin)- interval '1 second'
        WHERE tax_id = NEW.tax_id 
          AND date_begin <= NEW.date_begin
          AND date_end IS NULL ;
    RETURN NEW;
    END;
 
$$ LANGUAGE plpgsql;

-- Trigger sur tg_tax_value_insert
create trigger tg_tax_value_insert before
insert on
 public.bl_tax_value for each row execute function fct_tax_value_insert();


DROP TRIGGER IF EXISTS  tg_vat_insert on bl_vat ;
DROP FUNCTION IF EXISTS fct_vat_insert;

CREATE OR REPLACE FUNCTION fct_vat_insert() 
RETURNS trigger AS $$
    BEGIN
          
        IF NEW.is_default = true THEN
            UPDATE bl_vat set is_default = false
            WHERE company_id = NEW.company_id 
              AND is_default = true ;
        END IF;
        
    RETURN NEW;
    END;
 
$$ LANGUAGE plpgsql;

-- Trigger sur tg_vat_insert
create trigger tg_vat_insert before
insert on
 public.bl_vat for each row execute function fct_vat_insert();
