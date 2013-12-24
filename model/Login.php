<?php

/**
 * Class login
 *
 * handles the user login/logout/session
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */
class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var string The user's name
     */
    private $user_name = "";
    /**
     * @var string The user's mail
     */
    private $user_email = "";
    /**
     * @var string The user's password hash
     */
    private $user_password_hash = "";
    /**
     * @var boolean The user's login status
     */
    private $user_is_logged_in = false;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {

        // TODO: adapt the minimum check like in 0-one-file version

        // create/read session
        session_start();

        // check the possible login actions:
        // 1. logout (happen when user clicks logout button)
        // 2. login via session data (happens each time user opens a page on your php project AFTER he has sucessfully logged in via the login form)
        // 3. login via post data, which means simply logging in via the login form. after the user has submit his login/password successfully, his
        //    logged-in-status is written into his session data on the server. this is the typical behaviour of common login scripts.

        // if user tried to log out
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // if user has an active session on the server
        elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
            $this->loginWithSessionData();
        }elseif (isset($_COOKIE['rememberme'])) {
            $this->loginWithCookieData();
        }
        // if user just submitted a login form
        elseif (isset($_POST["login"])) {
            $this->loginWithPostData($_POST['user_rememberme']);

        // if user just submitted a login form
        }
    }

    private function loginWithCookieData()
    {
        if (isset($_COOKIE['rememberme'])) {
            // extract data from the cookie
            list ($user_id, $token, $hash) = explode(':', $_COOKIE['rememberme']);

            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            // check cookie hash validity
            if ($hash == hash('sha256', $user_id . ':' . $token . COOKIE_SECRET_KEY) && !empty($token)) {
                // cookie looks good, try to select corresponding user
                if ($this->db_connection) {
                    // get real token from database (and all other data)
                    
$checkcookie = $this->db_connection->query("SELECT user_name, user_email, user_id FROM users WHERE user_id = '" . $user_id . "';");

                    // $sth = $this->db_connection->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_id = :user_id
                    //                                   AND user_rememberme_token = :user_rememberme_token AND user_rememberme_token IS NOT NULL");
                    // $sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    // $sth->bindValue(':user_rememberme_token', $token, PDO::PARAM_STR);
                    // $sth->execute();

                    if ($checkcookie->num_rows == 1) {

                        $result_row = $checkcookie->fetch_object();
                        // write user data into PHP SESSION [a file on your server]
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_logged_in'] = 1;

                        // declare user id, set the login status to true
                        $this->user_id = $result_row->user_id;
                        $this->user_name = $result_row->user_name;
                        $this->user_email = $result_row->user_email;
                        $this->user_is_logged_in = true;

                        // Cookie token usable only once
                        $this->newRememberMeCookie();
                        return true;
                    }
                }
            }
            // A cookie has been used but is not valid... we delete it
            $this->deleteRememberMeCookie();
            $this->errors[] = 'Invalid cookie';
        }
        return false;
    }

    /**
     * log in with session data
     */
    private function loginWithSessionData()
    {
        // set logged in status to true, because we just checked for this:
        // !empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)
        // when we called this method (in the constructor)
        $this->user_is_logged_in = true;
    }

    /**
     * log in with post data
     */
    private function loginWithPostData($user_rememberme)
    {
        // if POST data (from login form) contains non-empty user_name and non-empty user_password
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escape the POST stuff
                $this->user_name = $this->db_connection->real_escape_string($_POST['user_name']);
                // database query, getting all the info of the selected user
                $checklogin = $this->db_connection->query("SELECT user_name, user_email, user_password_hash, user_id FROM users WHERE user_name = '" . $this->user_name . "';");

                // if this user exists
                if ($checklogin->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $checklogin->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided passwords fits to the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {


                        // write user data into PHP SESSION [a file on your server]
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_logged_in'] = 1;

                        // set the login status to true
                        $this->user_is_logged_in = true;

                        if (isset($user_rememberme)) {
                            $this->newRememberMeCookie();
                        } else {
                            // Reset rememberme token
                            $this->deleteRememberMeCookie();
                        }
                        

                    } else {
                         $this->errors[] = "Wrong password. Try again.";
                    }
                } else {
                    $this->errors[] =  "This user does not exist.";
                }
            } else {
                $this->errors[] =  "Database connection problem.";
            }
        } elseif (empty($_POST['user_name'])) {
            $this->errors[] =  "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] =  "Password field was empty.";
        }
    }

    private function newRememberMeCookie()
    {
        // if database connection opened
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db_connection) {

            // generate 64 char random string and store it in current user data
            $random_token_string = hash('sha256', mt_rand());
            $user_id = $_SESSION['user_id'];

            $this->db_connection->query("UPDATE users SET user_rememberme_token = '" . $random_token_string. "' WHERE name = '" . $user_id . "'");
            // $sth = $this->db_connection->prepare("UPDATE users SET user_rememberme_token = :user_rememberme_token WHERE user_id = :user_id");
            // $sth->execute(array(':user_rememberme_token' => $random_token_string, ':user_id' => $_SESSION['user_id']));

            // generate cookie string that consists of userid, randomstring and combined hash of both

            $cookie_string_first_part = $_SESSION['user_id'] . ':' . $random_token_string;
            $cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
            $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

            // set cookie
            setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", NULL);
        }
    }

    /**
     * Delete all data needed for remember me cookie connection on client and server side
     */
    private function deleteRememberMeCookie()
    {
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        // if database connection opened
        if ($this->db_connection) {
            // Reset rememberme token
            $user_id = $_SESSION['user_id'];
            $this->db_connection->query("UPDATE users SET user_rememberme_token = NULL WHERE name = '" . $user_id . "'");
        }

        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obivously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', NULL);
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->user_is_logged_in = false;
        $this->messages[] = "You have been logged out.";
        

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        return $this->user_is_logged_in;
    }
}
