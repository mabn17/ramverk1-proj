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


-- -------------------------------------------------------------------------


CREATE TABLE `Users`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(23) UNIQUE NOT NULL,
    `password` CHAR(200),
    `email` VARCHAR(60) UNIQUE NOT NULL,
    `active` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    PRIMARY KEY (id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Users` (`username`, `password`, `email`)
VALUES
    ('mabn17', MD5("pass"), 'martin.d@live.se'),
    ('admin', MD5("admin"), 'admin@admin.com'),
    ('doe', MD5("doe"), 'doe@doe.com'),
    ('randomuser', MD5("randomuser"), "randomuser@randomuser.com")
;


-- -------------------------------------------------------------------------

CREATE TABLE `Posts`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `userId` INT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `title` VARCHAR(200) NOT NULL,
    `data` TEXT,
    `parent` INT DEFAULT NULL,
    `answerd` INT DEFAULT 0,

    PRIMARY KEY (id),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Posts` (`userId`, `title`, `data`, `parent`)
VALUES
    (1, "Promise.all.then Not all code paths return a value", "
I have the following function that I want to return **Promise< number[] >**

<pre>
async fetchCommentLines(commitDict: CommitDict): Promise < number[] > {
    if (GitCommentService.isLoggedIn()) {
        const commentLines = Object.values(commitDict).map(async commit => {
            // ...
            // do the job and return number[]
            // ...
            return lineNums;
        });
        Promise.all(commentLines)
            .then(commitLines => {
                return Array.prototype.concat.apply([], commitLines);
            });
    } else {
        return [] as number[];
    }
}
</pre>


Firstly I got ''**function lacks ending return statement and return type does not include 'undefined'**''

And then I added **undefined** (so return type becomes **Promise< number[] | undefined >**)

But I'm getting ''**not all code paths return a value**'' this time.

It seems I'm not considering a possible code path with below code

```
Promise.all(...)
.then(val => {return ...})
```

What I'm missing?

I also tried this

```
Promise.all(...)
.then(val => {return ...})
.catch(e => {return ...})
```

But it was not helpful

Note: My main purpose is to return **Promise< number[] >**, not **Promise< number[] | undefined >**
", NULL),
(2, "how to make a request several times without hard refresh with ajax",
"

I send a request via ajax to delete a file. The first time , ajax handles the request, but the second time not anymorte. i got a ''hard refresh''

This is my code:
<small><pre>
?php
if(isset($_POST['deletefile'])) {
    // if is directory -> remove dir
    if(is_dir($_POST['deletefile'])){
    removeDirectory($_POST['deletefile']);      
    exit;
    }
    // else (must be a file) -> unlink file
    else {
    unlink($_POST['deletefile']);
    exit;
    }
}
?>
div class='myFiles'
form class='sfmform' method='post' action=''
    input type='hidden' name='deletefile' value='<?php echo $dir.'/'.$file; ?>'
    input type='submit' class='sfmdelete'  name='delete' value=' '
/form 
script
  $('.sfmform').on('submit',(function(e) {
        e.preventDefault();
            $.ajax({
            url: '',
            type: 'POST',
            data:  new FormData(this),
            contentType: false,
            processData: false,
            success: function(response)
            {           
            $('.myFiles').load(document.URL +  ' .myFiles');
            },
       });
    }));
/script
/div !-- END myFiles --
</pre></small>

To see immediately when i file has been deleted, i use this line `$('.myFiles').load(document.URL +  ' .myFiles');`

The first delete request goes fine, but the second: he makes a hard refresh and dies in the php loop. I was hoping that after the `exit` in the php the line `$('.myFiles').load(document.URL +  ' .myFiles');` was executing but the second time it loads not the `div class='myFiles>` anymore.'

How can i make this work properly?

Even when i put the js in the ready handler, it does not work! The reason why i use an `exit` in the php: otherwise the `div class='myFiles'` appears 2 times under each other"
, NULL),

(3, "How to add an int value to a sql column from netbeans",
"

I'm trying to get my JSpinners value to add to the column in my database,
why is my variable query2 working when I put it in db.update() but not query? What do I need to change?
<pre> try {
    int points = (int) antalPoäng.getValue();
    String query = UPDATE ELEVHEM SET HUSPOANG = HUSPOANG + points + WHERE ELEVHEMSNAMN = 'Gryffindor';
    String query2 = UPDATE ELEVHEM SET HUSPOANG = HUSPOANG +1 WHERE ELEVHEMSNAMN = 'Gryffindor';
    if (namnElevhem.getSelectedItem().equals('Gryffindor')) {
        db.update(query);
        JOptionPane.showMessageDialog(null,'The points has been added')
    }
} catch(InfException e) {

}</pre>
", NULL),

(2, "Answer",
"Your branch with `Promise.all` never issues a `return (value)` statement. The `then` callback does, but not the code outside the `then` callback.

You probably want to `return` the result of `Promise.all().then()`.
<pre>
    return Promise.all(commentLines)
//  ^^^^^^
    .then(commitLines => {
        return Array.prototype.concat.apply([], commitLines);
    });
</pre>

"
-- Answers
,1),
(3, "Answer",
"you should return the `Promise`
<pre>
return Promise.all(commentLines).then(...)
//....
</pre>
or wait for the promise and return the result object
<pre>
let lines = await Promise.all(commentLines)
return [].concat(...lines)
</pre>
"
,1),
(1, "Answer",
"You changed the `html` of `.myFiles` so everything in it in the second time is dynamically generated . so you need [Event binding on dynamically created elements?](https://stackoverflow.com/questions/203198/event-binding-on-dynamically-created-elements) .. change
<pre>
$('.sfmform').on('submit',(function(e) {
</pre>
to
<pre>
$(document).on('submit' , '.sfmform' , (function(e) {
</pre>
**Note:** *for me its not a good practice to use `$.ajax` with the same file `url:` better for me to make a separate file*"
,2);


-- -------------------------------------------------------------------------


CREATE TABLE `Comments`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `userId` INT NOT NULL,
    `postId` INT NOT NULL,
    `data` TEXT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


INSERT INTO `Comments` (`userId`, `postId`, `data`)
VALUES
    (1, 4, "My purpose with it was to flatten a `number[][]` into `number[]` So `[[1,2],[3,4]]` into `[1,2,3,4]`  I'll try the other ways you mentioned"),
    (2, 4, "Ah! In that case, ignore me. :-) (I've removed that part of the answer.) You might look into `Array.prototype.flat`. It's at Stage 3 and already supported by Chrome and Firefox, easily polyfilled for others... :-) That would be `return Promise.all(commentLines).then(commitLines => commitLines.flat());`"),
    (2, 6, "Perfect solution! It works fine now! I am gonna read you link about event binding. I really did get stuck on this problem. Thnx"),
    (1, 6, "You're totally Welcome .. Have a nice day :-)"),
    (2, 3, "Off topic: matter off opinion and or taste i would make SQL keywords/functions uppercase only and identifiers like database, table and column names all lowercase makes it a bit more easy to read and maintain..");


-- -------------------------------------------------------------------------



CREATE TABLE `Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `tag` VARCHAR(25),

    PRIMARY KEY(`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Tags` (`tag`)
VALUES ("Other"), ("SQL"), ("Java"), ("JavaScript"), ("PHP");

-- -------------------------------------------------------------------------


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
VALUES (1, 4), (2, 5), (2, 4), (2, 1), (3, 1), (3, 2), (3, 3);


-- -------------------------------------------------------------------------


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

-- -------------------------------------------------------------------------


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


-- -------------------------------------------------------------------------

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

-- -------------------------------------------------------------------------

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

-- -------------------------------------------------------------------------

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

-- -------------------------------------------------------------------------

DELIMITER ;;

CREATE PROCEDURE `GetAllTheLikes`
( pDestinationId INT, pType VARCHAR(20))
BEGIN
    SELECT SUM(points) AS 'totalPoints'
        From Likes
    WHERE destinationId = pDestinationId AND type = pType;
END;;

DELIMITER ;

-- -------------------------------------------------------------------------


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

-- -------------------------------------------------------------------------

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

-- -------------------------------------------------------------------------


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

-- -------------------------------------------------------------------------

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
    GROUP BY U.id;

-- -------------------------------------------------------------------------
