--
-- Create a database for test
--

-- DROP DATABASE IF EXISTS forum;
-- CREATE DATABASE IF NOT EXISTS forum;
-- USE forum;

--
-- Create a database user for the test database
--
-- GRANT ALL ON forum.* TO user@localhost IDENTIFIED BY 'pass';

-- Ensure UTF8 on the database connection
SET NAMES utf8;

DROP VIEW IF EXISTS `userLikesForComments`;
DROP VIEW IF EXISTS `userLikesForPosts`;
DROP PROCEDURE IF EXISTS `getAllUserPoints`;
DROP VIEW IF EXISTS `CalcUserPoints`;
DROP PROCEDURE IF EXISTS `GetMostActiveUsers`;
DROP PROCEDURE IF EXISTS `GetAllTheLikes`;
DROP VIEW IF EXISTS `GetSubPosts`;
DROP PROCEDURE IF EXISTS `GetMostActiveUsers`;
DROP PROCEDURE IF EXISTS `GetPopularTags`;
DROP VIEW IF EXISTS `HeadCommentAndTags`;
DROP TABLE IF EXISTS `Likes`;
DROP TABLE IF EXISTS `Post2Tags`;
DROP TABLE IF EXISTS `Tags`;
DROP TABLE IF EXISTS `Comments`;
DROP TABLE IF EXISTS `Posts`;
DROP TABLE IF EXISTS `Users`;


CREATE TABLE `Users`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(23) UNIQUE NOT NULL,
    `password` CHAR(200),
    `email` VARCHAR(60) UNIQUE NOT NULL,
    `points` INT DEFAULT 0 NOT NULL,

    PRIMARY KEY (id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Users` (`username`, `password`, `email`)
VALUES
    ('mabn17', MD5("pass"), 'martin.d@live.se'),
    ('admin', MD5("admin"), 'admin@admin.com'),
    ('doe', MD5("doe"), 'doe@doe.com'),
    ('ha', MD5("ha"), "ha@ha.com")
;




CREATE TABLE `Posts`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `userId` INT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- `likes` INT DEFAULT 0 NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `data` TEXT,
    `parent` INT DEFAULT NULL,
    `answerd` INT DEFAULT 0,

    PRIMARY KEY (id),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Posts` (`userId`, `title`, `data`, `parent`)
VALUES
    (1, "First Post", "1 - *question* user1", NULL),
    (2, "Second Post", "2 - *question* user2", NULL),
    (3, "Third Post", "3 - *question* user3", NULL),
    (1, "Answer", "4 - *Ans* post 3 user1 parent 3", 3);






CREATE TABLE `Comments`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `userId` INT NOT NULL,
    `postId` INT NOT NULL,
    `data` TEXT,
   -- `likes` INT DEFAULT 0 NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


INSERT INTO `Comments` (`userId`, `postId`, `data`)
VALUES
    (2, 1, "User 2 *comments* on Post 1"),
    (2, 4, "User two *comments* on Post4 (Ans post 3 user1 parent 3)");





CREATE TABLE `Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `tag` VARCHAR(25),

    PRIMARY KEY(`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Tags` (`tag`)
VALUES ("Other"), ("First"), ("Second"), ("Third");



CREATE TABLE `Post2Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `postId` INT,
    `tagId` INT DEFAULT 1,

    PRIMARY KEY(`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`),
    FOREIGN KEY (`tagId`) REFERENCES `Tags` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Post2Tags` (`postId`, `tagId`)
VALUES (1, 2), (2, 3), (3, 4), (1, 1), (2, 1); -- Add a postId with tagId 1 when you see this work



CREATE TABLE `Likes`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `type` VARCHAR(20),
    `userId` INT,
    `destinationId` INT,
    `points` INT DEFAULT 0,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
);

INSERT INTO `Likes` (`type`, `userId`, `destinationId`, `points`)
VALUES
    ("comment", 1, 1, 1), ("comment", 3, 1, 1),
    ("post", 2, 1, -1), ("post", 2, 3, 1), ("post", 1, 4, 1), ("post", 1, 4, 1), ("post", 1, 4, 1),
    ("comment", 1, 2, -1);


CREATE VIEW `HeadCommentAndTags`
AS

    SELECT
        P.id AS 'id',
        P.userId AS 'userId',
        P.created AS 'created',
        P.title AS 'title',
        P.data AS 'data',
        P.parent AS 'parent',
        P.answerd AS 'answerd',
        GROUP_CONCAT(T.tag) AS 'tagss'
    FROM `Posts` AS P
        INNER JOIN `Post2Tags` AS PT
            ON PT.postId = P.id
        LEFT JOIN `Tags` AS T
            ON PT.tagId = T.id
    GROUP BY P.id;

DELIMITER ;;
CREATE PROCEDURE `GetPopularTags`
(  )
BEGIN
    SELECT
        tag,
        T.id AS 'id',
        COUNT(PT.tagId) AS 'cnt'
    FROM
        `Tags` AS T
    INNER JOIN
        `Post2Tags` AS PT
            ON PT.tagId = T.id
    GROUP BY
        PT.tagId
    ORDER BY
        cnt DESC;
END
;;

DELIMITER ;

DELIMITER ;;

CREATE PROCEDURE `GetMostActiveUsers`
()
BEGIN

    SELECT 
        U.id AS 'id', U.username AS 'username', U.email AS 'email', COUNT(P.userId) AS CP,
        COUNT(C.userId) AS CC, (COUNT(P.userId) + COUNT(C.userId)) AS activity
    FROM Users AS U
        LEFT JOIN Posts AS P ON P.userId = U.id
        LEFT JOIN Comments AS C ON C.userId = U.id
    GROUP BY U.username
        ORDER BY activity DESC;

END;;
DELIMITER ;



CREATE VIEW `GetSubPosts`
AS
    SELECT
        P.id AS 'id',
        P.userId AS 'userId',
        P.created AS 'created',
        P.title AS 'title',
        P.data AS 'data',
        P.parent AS 'parent',
        P.answerd AS 'answerd',
        COALESCE(SUM(L.points), 0) AS 'totalPoints',
        U.email AS 'email',
        U.username AS 'username',
        C.data AS 'cdata',
        C.userId AS 'cuser',
        C.postId AS 'cpost',
        C.id AS 'cid'
    FROM
        Posts AS P
    INNER JOIN
        Users AS U ON U.id = P.userId
    LEFT JOIN
        Comments AS C ON C.postId = P.id
    LEFT OUTER JOIN Likes AS L ON L.destinationId = P.id 
                                AND L.type = "post"
    GROUP BY P.id;

DELIMITER ;;

CREATE PROCEDURE `GetAllTheLikes`
( pDestinationId INT, pType VARCHAR(20))
BEGIN
    SELECT SUM(points) AS 'totalPoints'
        From Likes
    WHERE destinationId = pDestinationId AND type = pType;
END;;

DELIMITER ;


CREATE VIEW `CalcUserPoints`
AS
    SELECT
        U.id AS 'id',
        U.username AS "username",
        U.email AS 'email',
        COALESCE(COUNT(P.userId), 0) AS 'postsMade',
        COALESCE(COUNT(C.userId), 0) AS 'commentsMade',
        COALESCE(SUM(L.points), 0) AS 'totalPoints'
    FROM Users AS U
    INNER JOIN Posts AS P ON P.userId = U.id
    INNER JOIN Comments AS C ON C.userId = U.id
    INNER JOIN Likes AS L ON L.userId = U.id
    GROUP BY U.id;


DELIMITER ;;

CREATE PROCEDURE `getAllUserPoints`
( pUid INT )
BEGIN

    DECLARE questionsMade INT;
    DECLARE answersMade INT;
    DECLARE commentsMade INT;

    SET questionsMade = (SELECT COALESCE(COUNT(*), 0) FROM Posts WHERE userId = pUid AND parent IS NULL);
    SET answersMade = (SELECT COALESCE(COUNT(*), 0) FROM Posts WHERE userId = pUid AND parent IS NOT NULL);
    SET commentsMade = (SELECT COALESCE(COUNT(*), 0) FROM Comments WHERE userId = pUid);

    SELECT
        questionsMade,
        answersMade,
        commentsMade;
END;;

DELIMITER ;


CREATE VIEW `userLikesForPosts`
AS
    SELECT
        U.id AS 'userId',
        COALESCE(SUM(L.points), 0) AS 'totalPoints'
    FROM
        Users AS U
    LEFT JOIN
        Posts AS P ON U.id = P.userId
    LEFT OUTER JOIN Likes AS L ON L.destinationId = P.id 
                                AND L.type = "post"
    GROUP BY P.userId
;

CREATE VIEW `userLikesForComments`
AS
    SELECT
        U.id AS 'userId',
        COALESCE(SUM(L.points),0) AS 'totalPoints',
        COALESCE(GROUP_CONCAT(C.id), 0) AS 'valda posts för kommentarer',
        COALESCE(GROUP_CONCAT(L.points),0) AS 'poäng för kommentarer'
    FROM
        Users AS U
    LEFT OUTER JOIN
        Comments AS C ON U.id = C.userId
    LEFT OUTER JOIN Likes AS L ON L.destinationId = C.id
                                AND L.type = "comment"
    GROUP BY U.id
-- --------------------------------------------------
    /* SELECT destinationId as 'destinationId',
            SUM(points) as 'totalPoints', 
            type as 'type'
    FROM Likes AS L */
-- ------------------------------------------------- 
;

SELECT * FROM userLikesForComments;
