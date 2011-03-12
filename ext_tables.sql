#
# Table structure for table 'tx_myevents_locations'
#
CREATE TABLE tx_myevents_locations (
   
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumtext,
    sorting int(10) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    
    street tinytext,
    city tinytext,
    zip tinytext,
    building tinytext,
    room tinytext,
    title tinytext,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

CREATE TABLE tx_myevents_categories (
   
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumtext,
    sorting int(10) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden BOOL DEFAULT '0' NOT NULL,
    
    name tinytext,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

INSERT IGNORE INTO tx_myevents_categories SET uid='1', pid='1', name='Workshops';
INSERT IGNORE INTO tx_myevents_categories SET uid='2', pid='1', name='Vorträge';
INSERT IGNORE INTO tx_myevents_categories SET uid='3', pid='1', name='Ausstellungen';
INSERT IGNORE INTO tx_myevents_categories SET uid='4', pid='1', name='Events';
INSERT IGNORE INTO tx_myevents_categories SET uid='5', pid='1', name='Parties';
