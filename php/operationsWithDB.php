<?php // Работа с БД
require_once 'user.php';

function PDOPgSQL()
{
    static $dbconn;
    if (is_null($dbconn)) {

        try {
            $configArray = parse_ini_file('config.ini');
            $dsn = "pgsql:host={$configArray['host']};port={$configArray['port']};dbname={$configArray['dbname']}";
            $dbconn = new PDO($dsn, $configArray['username'], $configArray['passwd']);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br />";
        }
    }

    return $dbconn;
}
function checkRating($pictureId, $userEmail) { // Проверка поставил ли пользователь оценку на данный пост
    try {
        $db = PDOPgSQL();
        $query = 'SELECT "ratingId" FROM public."pictureRatings" WHERE ("pictureId" = :pictureId AND "userEmail" = :userEmail);';
        $params = [
            ':pictureId' => $pictureId,
            ':userEmail' => $userEmail,
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $rating = $stmt->fetch();
        $db = null;
        return isset($rating['ratingId']);
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
}


function addRating($userEmail, $pictureId, $value) { // Оценка поста пользователем
    try {
        $db = PDOPgSQL();
        $db->beginTransaction();
        $query = 'INSERT INTO "pictureRatings" ("pictureId", "userEmail", "valueRating" ) VALUES(:pictureId, :userEmail, :value) RETURNING "ratingId";';
        $params = [
            ':pictureId' => $pictureId,
            ':userEmail' => $userEmail,
            ':value' => $value,
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $ratingId = $stmt->fetch();
        $query = 'UPDATE public."picture" SET ratings= array_append(ratings, :ratingId) WHERE "picture"."pictureId" = :pictureId;';
        $params = [
            ':pictureId' => $pictureId,
            ':ratingId' => $ratingId['ratingId'],
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $query = 'UPDATE public."picture" SET "pictureRaiting"= (SELECT avg("pictureRatings"."valueRating") FROM public."pictureRatings" WHERE public."pictureRatings"."pictureId" = :pictureId) WHERE public."picture"."pictureId" = :pictureId;';
        $params = [
            ':pictureId' => $pictureId,
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $db->commit();
        $db = null;
    } catch (PDOException $th) {
        $db->rollBack();
        print 'Ошибка '. $th->getMessage();
        die();
    }
}


function addPost($userEmail, $url, $description) { // Добаление нового поста
    try {
        $db = PDOPgSQL();
        $query = 'INSERT INTO "picture" ("description", "pictureDate", "urlPhoto", "userEmail") VALUES(:description, :pictureDate, :urlPhoto, :userEmail)';
        $params = [
            ':description' => $description,
            ':pictureDate' => date( "Y-m-d" ),
            ':urlPhoto' => $url,
            ':userEmail' => $userEmail
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $db = null;
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
}


function getLastPhotos(){ // Получение последник 20 публикаций
    try {
        $db = PDOPgSQL();
        $query = 'SELECT "pictureId", description, "pictureDate", "pictureRaiting", ratings, "urlPhoto", "userEmail" FROM public."picture" ORDER BY "pictureDate" DESC LIMIT 20;';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $photoArray = [];
        while ($row = $stmt->fetch()) {
            $tmpPost = new photoPost();
            $tmpPost->postId = $row["pictureId"];
            $tmpPost->authorEmail = $row["userEmail"];
            $tmpPost->urlPhoto = $row["urlPhoto"];
            $tmpPost->description = $row["description"];
            $tmpPost->pictureRaiting = $row["pictureRaiting"];
            $tmpPost->pictureDate = $row["pictureDate"];
            //$tmpPost->countRatings = @count($row["ratings"]);
            $photoArray[] = $tmpPost;
        }
        $db = null;
        return $photoArray;
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
    return 'ERROR';
}


function getUserPhotos($userEmail) // Получение постов пользователя
{
    try {
        $db = PDOPgSQL();
        $query = 'SELECT "pictureId", description, "pictureDate", "pictureRaiting", ratings, "urlPhoto", "userEmail" FROM public."picture" WHERE public."picture"."userEmail" = ? ORDER BY "pictureDate" DESC';
        $stmt = $db->prepare($query);
        $stmt->execute([$userEmail]);
        $photoArray = [];
        while ($row = $stmt->fetch()) {
            $tmpPost = new photoPost();
            $tmpPost->postId = $row["pictureId"];
            $tmpPost->urlPhoto = $row["urlPhoto"];
            $tmpPost->description = $row["description"];
            $tmpPost->pictureRaiting = $row["pictureRaiting"];
            $tmpPost->pictureDate = $row["pictureDate"];
            $tmpPost->countRatings = $row["ratings"];
            $photoArray[] = $tmpPost;
        }
        $db = null;
        return $photoArray;
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
    return 'ERROR';
}


function getUser($userValue, $type = 'email') // Получение данных о пользователе
{
    try {
        $db = PDOPgSQL();
        if ($type == 'email') {
            $query = 'SELECT pUsers.id, pUsers.firstname, pUsers.secondname, pUsers.fathername, pUsers.email FROM "users" as pUsers WHERE pUsers.email = ?';
        }
        else {
            $query = 'SELECT pUsers.id, pUsers.firstname, pUsers.secondname, pUsers.fathername, pUsers.email FROM "users" as pUsers WHERE pUsers.id = ?';
        }
        $stmt = $db->prepare($query);
        $stmt->execute([$userValue]);
        $userData = $stmt->fetch();
        $db = null;
        if (empty($userData)) {
            return "NO_SUCH_USER_IN_DB";
        }
        else {
            $user = new user();
            $user->id = $userData['id'];
            $user->name = $userData['firstname'];
            $user->secondName = $userData['secondname'];
            $user->fatherName = $userData['fathername'];
            $user->email = $userData['email'];
            return $user;
        }
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
    return 'ERROR';
}

function addUserToDB($userFirstName, $userSecondName, $userFatherName, $userEmail, $userHashPassword) // Добавление нового пользователя в БД
{
    try {
        $db = PDOPgSQL();
        if (!checkUserInDB($userEmail)) {
            $db = NULL;
            return 'REGISTRATION_FALL_USER_ALREADY_REGISTRED';
        }
        $query = 'INSERT INTO "users" ("firstname", "secondname", "fathername", "email", "hashpassword") VALUES(:firstname, :secondname, :fathername, :email, :hashpassword)';
        $params = [
            ':firstname' => $userFirstName,
            ':secondname' => $userSecondName,
            ':fathername' => $userFatherName,
            ':email' => $userEmail,
            ':hashpassword' => $userHashPassword
        ];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $db = NULL;
        return true;
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }

}

function checkUserInDB($userEmail) // Проверка существует ли пользователь с такой элю почтой
{
    try {
        $db = PDOPgSQL();
        $query = 'SELECT pUsers.email FROM "users" as pUsers WHERE pUsers.email = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$userEmail]);
        $user = $stmt->fetch();
        $db = null;
        return empty($user);
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
}

function findUserInDB($userEmail, $userPassword) // Проверка эл почты и пароля при входе
{
    try {
        $db = PDOPgSQL();
        $query = 'SELECT pUsers.email, pUsers.hashpassword FROM "users" as pUsers WHERE pUsers.email = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$userEmail]);
        $user = $stmt->fetch();
        $db = null;
        if ($user == false) {
            return false;
        }
        else {
            if (!password_verify($userPassword, $user['hashpassword'])) {
                return false;
            }
            else {
                return true;
            }
        }
    } catch (PDOException $th) {
        print 'Ошибка '. $th->getMessage();
        die();
    }
}