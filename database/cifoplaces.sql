DROP DATABASE IF EXISTS cifoplaces;
CREATE DATABASE cifoplaces DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE cifoplaces;

CREATE TABLE users(
	id INT NOT NULL PRIMARY KEY auto_increment,
	displayname VARCHAR(32) NOT NULL,
	email VARCHAR(128) NOT NULL UNIQUE KEY,
	phone VARCHAR(32) NOT NULL UNIQUE KEY,
	password VARCHAR(32) NOT NULL,
	-- roles JSON NOT NULL DEFAULT '["ROLE_USER"]',
	roles JSON NOT NULL,
	picture VARCHAR(256) DEFAULT NULL,
	blocked_at TIMESTAMP NULL DEFAULT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE places(
	id INT PRIMARY KEY auto_increment,
    name VARCHAR(128) NOT NULL,
    type VARCHAR(128) NOT NULL,
    location VARCHAR(128) NOT NULL,
    description TEXT,
    iduser INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY(iduser) REFERENCES users(id) 
		ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE photos(
	id INT PRIMARY KEY auto_increment,
    name VARCHAR(128) NOT NULL,
    file VARCHAR(256) NOT NULL,
    description TEXT,
    date DATE NULL DEFAULT NULL,
    time TIME NULL DEFAULT NULL,
    iduser INT NULL,
    idplace INT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    
	FOREIGN KEY(iduser) REFERENCES users(id) 
		ON UPDATE CASCADE ON DELETE SET NULL,
        
    FOREIGN KEY(idplace) REFERENCES places(id) 
		ON UPDATE CASCADE ON DELETE CASCADE    
);

CREATE TABLE comments(
	id INT PRIMARY KEY auto_increment,
    text TEXT,
    iduser INT NULL,
    idphoto INT NULL,
    idplace INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY(iduser) REFERENCES users(id) 
		ON UPDATE CASCADE ON DELETE SET NULL,
        
    FOREIGN KEY(idplace) REFERENCES places(id) 
		ON UPDATE CASCADE ON DELETE CASCADE, 
        
	FOREIGN KEY(idphoto) REFERENCES photos(id) 
		ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE errors(
	id INT NOT NULL PRIMARY KEY auto_increment,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    level VARCHAR(32) NOT NULL DEFAULT 'ERROR',
    url VARCHAR(256) NOT NULL,
	message VARCHAR(256) NOT NULL,
	user VARCHAR(128) DEFAULT NULL,
	ip CHAR(15) NOT NULL
);

INSERT INTO users(displayname, email, phone, password, roles) VALUES 
	('admin', 'admin@fastlight.com', '666666666', md5('1234'), '["ROLE_USER", "ROLE_ADMIN"]'),
	('moderator', 'moderator@fastlight.com', '666666665', md5('1234'), '["ROLE_USER", "ROLE_MODERATOR"]'),
	('user1', 'user1@fastlight.com', '666666664', md5('1234'), '["ROLE_USER"]'),
	('user2', 'user2@fastlight.com', '666666660', md5('1234'), '["ROLE_USER"]'),
	('user3', 'user3@fastlight.com', '666666662', md5('1234'), '["ROLE_USER"]')
;

COMMIT;
