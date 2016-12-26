CREATE TABLE IF NOT EXISTS VERIFY(
    ID INT NOT NULL AUTO_INCREMENT,
    PHONE VARCHAR(11) NOT NULL,
    IDCARD VARCHAR(11) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS COMPANY(
    ID INT NOT NULL AUTO_INCREMENT,
    NAME VARCHAR(20) NOT NULL,
    ICON VARCHAR(25) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS TRANSCODE(
    ID INT NOT NULL AUTO_INCREMENT,
    RANDCODE VARCHAR(10) NOT NULL,
    CREATETIME TIMESTAMP NOT NULL,
    STEP INT(2) NOT NULL,
    PRIMARY KEY(ID),
    UNIQUE(RANDCODE)
);

CREATE TABLE IF NOT EXISTS ADMIN(
    ID INT NOT NULL AUTO_INCREMENT,
    ADMINID VARCHAR(20) NOT NULL,
    PWD VARCHAR(30) NOT NULL,
    COMPANYID INT NOT NULL,
    NAME VARCHAR(30),
    PRIMARY KEY(ID),
    FOREIGN KEY(COMPANYID) REFERENCES COMPANY(ID),
    UNIQUE(ADMINID)
);

CREATE TABLE IF NOT EXISTS USERINFO(
    ID INT NOT NULL AUTO_INCREMENT,
    EMAIL VARCHAR(40),
    ISAUTHORIZEEMAIL BOOLEAN DEFAULT FALSE,
    FNAME VARCHAR(30),
    SNAME VARCHAR(30),
    NICKNAME VARCHAR(30),
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS ACCOUNT(
    ID INT NOT NULL AUTO_INCREMENT,
    ACCID VARCHAR(20) NOT NULL,
    PWD VARCHAR(30) NOT NULL,
    USERINFOID INT NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(USERINFOID) REFERENCES USERINFO(ID),
    UNIQUE(ACCID)
);

CREATE TABLE IF NOT EXISTS CARD(
    ID INT NOT NULL AUTO_INCREMENT,
    NUM VARCHAR(15) NOT NULL,
    COMPANYID INT NOT NULL,
    TYPE INT NOT NULL,
    LEVEL INT NOT NULL,
    EXPIRE VARCHAR(12) NOT NULL,
    VERIFYID INT NOT NULL,
    ACCOUNTID INT NOT NULL,
    TRANSCODEID INT,
    PRIMARY KEY(ID),
    FOREIGN KEY(COMPANYID) REFERENCES COMPANY(ID),
    FOREIGN KEY(VERIFYID) REFERENCES VERIFY(ID),
    FOREIGN KEY(ACCOUNTID) REFERENCES ACCOUNT(ID),
    FOREIGN KEY(TRANSCODEID) REFERENCES TRANSCODE(ID)
);

CREATE TABLE IF NOT EXISTS TRANSACTION(
    ID INT NOT NULL AUTO_INCREMENT,
    CARDID INT NOT NULL,
    ACCOUNTID INT NOT NULL,
    ADMINID INT NOT NULL,
    CONTENT VARCHAR(10) NOT NULL,
    CREATETIME TIMESTAMP NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(CARDID) REFERENCES CARD(ID),
    FOREIGN KEY(ACCOUNTID) REFERENCES ACCOUNT(ID),
    FOREIGN KEY(ADMINID) REFERENCES ADMIN(ID)
);