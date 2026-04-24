CREATE TABLE tx_nsblogsystem_domain_model_blog (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    title varchar(255) NOT NULL DEFAULT '',
    description text,
    slug varchar(2048) DEFAULT '' NOT NULL,

    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid)
);
CREATE TABLE tx_nsblogsystem_domain_model_comment (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    blog int(11) DEFAULT '0' NOT NULL,

    name varchar(255) DEFAULT '' NOT NULL,
    email varchar(255) DEFAULT '' NOT NULL,
    comment text,

    approved tinyint(1) DEFAULT '0' NOT NULL,

    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid)
);