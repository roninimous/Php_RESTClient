<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RequestAction
 *
 * @author s4597377
 */
// Access to twig view object
// this line reveals the library to PHP
require __DIR__ . '/../vendor/autoload.php';
include_once "myfunctions.php";

// Reference to Twig environment
use Twig\Environment;
// Reference to File system
use Twig\Loader\FilesystemLoader;
use GuzzleHttp\Client;

// include_once 'ContactsDB.php';

class RequestAction {

    var $client;
    var $view;

    function __construct() {
        $this->client = new Client(['base_uri' => 'http://localhost/RESTServerUserMod1/']);
//        $this->client = new Client(['base_uri' => 'http://localhost/TestSLIM/']);
        // tell Twig path to template files
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
// create a Twig object ready for use
        $this->view = new Environment($loader);
    }

    function index() {
        echo $this->view->render('index.html.twig');
    }

    function editBooking() {
        // Check the id from the link if it is there or not
        $id = $_GET['id'];
        if (isset($_POST['submit'])) {
//            Retrieve form data safely
//            validate the form data
            $errors = [];
            if (isset($_POST["first_name"])) {
                $first_name = trim($_POST["first_name"]);
                $message = alpha_spaces($first_name);
                if (strlen($message) > 0) {
                    $errors['first_name'] = $message;
                }
            }

            if (isset($_POST["last_name"])) {
                $last_name = trim($_POST["last_name"]);
                $message = alpha_spaces($last_name);
                if (strlen($message) > 0) {
                    $errors['last_name'] = $message;
                }
            }


            if (isset($_POST["email"])) {
                $email = trim($_POST["email"]);
                $message = email($email);
                if (strlen($message) > 0) {
                    $errors['email'] = $message;
                }
            }


            if (isset($_POST["mobile"])) {
                $mobile = trim($_POST["mobile"]);
                $message = mobile($mobile);
                if (strlen($message) > 0) {
                    $errors['mobile'] = $message;
                }
            }



            if (isset($_POST["booking_date"])) {
                $booking_date = trim($_POST["booking_date"]);
                $message = validateDate($booking_date);
                if (strlen($message) > 0) {
                    $errors['booking_date'] = $message;
                }
            }


            if (isset($_POST["booking_time"])) {
                $booking_time = $_POST["booking_time"];
                $message = 'Missing input';
                if (strlen($booking_time) == 0) {
                    $errors['booking_time'] = $message;
                }
            }


            if (isset($_POST["venue"])) {
                $venue = $_POST["venue"];
                $message = 'Missing input';
                if (strlen($venue) == 0) {
                    $errors['venue'] = $message;
                }
            }



            if (isset($_POST["image"])) {
                $image = $_FILES["image"]["name"];
                $message = $message = 'Missing input';
                if (strlen($image) == 0) {
                    $errors['image'] = $message;
                }
            }




            if (count($errors) == 0) {
// Retrieve and assign post array values to form variables
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $email = $_POST["email"];
                $mobile = $_POST["mobile"];
                $booking_date = $_POST['booking_date'];
                $booking_time = $_POST['booking_time'];
                $venue = $_POST['venue'];
                $image_filename = $_POST['image_filename'];

                $uri = "bookings/$id";
                $response = $this->client->request('PUT', $uri, ['form_params' => $_POST]);
                $data = json_decode($response->getBody()->getContents(), true);
                $message = $data['message'];
                echo $this->view->render('message.html.twig', ['message' => $message]);
                // Load viewContact Page after 5 seconds
                header("Refresh:5;url=?action=viewBookings");
            } else {
//          echo $this->view->render('editBooking.html.twig', ['errors' => $errors]);
                $errortitle = "*Please do not leave empty field and make sure your data is valid*";
                $uri = "bookings/$id";
                $response = $this->client->get($uri);
                $record = json_decode($response->getBody()->getContents(), true);
                // If the submit button is not submitted, show the edit form and put the $id into the form
                echo $this->view->render('editBooking.html.twig', ['errors' => $errors, 'record' => $record, 'errortitle' => $errortitle]);
            }
        } else {
            $uri = "bookings/$id";
            $response = $this->client->get($uri);
            $record = json_decode($response->getBody()->getContents(), true);
            // If the submit button is not submitted, show the edit form and put the $id into the form
            echo $this->view->render('editBooking.html.twig', ['record' => $record]);
        }
    }

    function addBooking() {
		session_start();
		if (isset($_SESSION['loggedIn']) &&$_SESSION['loggedIn']){
        if (isset($_POST['submit'])) {

//            Retrieve form data safely
//            validate the form data
            $errors = [];
            if (isset($_POST["first_name"])) {
                $first_name = trim($_POST["first_name"]);
                $message = alpha_spaces($first_name);
                if (strlen($message) > 0) {
                    $errors['first_name'] = $message;
                }
            }

            if (isset($_POST["last_name"])) {
                $last_name = trim($_POST["last_name"]);
                $message = alpha_spaces($last_name);
                if (strlen($message) > 0) {
                    $errors['last_name'] = $message;
                }
            }


            if (isset($_POST["email"])) {
                $email = trim($_POST["email"]);
                $message = email($email);
                if (strlen($message) > 0) {
                    $errors['email'] = $message;
                }
            }


            if (isset($_POST["mobile"])) {
                $mobile = trim($_POST["mobile"]);
                $message = mobile($mobile);
                if (strlen($message) > 0) {
                    $errors['mobile'] = $message;
                }
            }



            if (isset($_POST["booking_date"])) {
                $booking_date = trim($_POST["booking_date"]);
                $message = validateDate($booking_date);
                if (strlen($message) > 0) {
                    $errors['booking_date'] = $message;
                }
            }


            if (isset($_POST["booking_time"])) {
                $booking_time = $_POST["booking_time"];
                $message = 'Missing input';
                if (strlen($booking_time) == 0) {
                    $errors['booking_time'] = $message;
                }
            }


            if (isset($_POST["venue"])) {
                $venue = $_POST["venue"];
                $message = 'Missing input';
                if (strlen($venue) == 0) {
                    $errors['venue'] = $message;
                }
            }



            if (isset($_POST["image"])) {
                $image = $_FILES["image"]["name"];
                $message = $message = 'Missing input';
                if (strlen($image) == 0) {
                    $errors['image'] = $message;
                }
            }




            if (count($errors) == 0) {
                // Retrieve and assign post array values to form variables
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $email = $_POST["email"];
                $mobile = $_POST["mobile"];
                $booking_date = $_POST['booking_date'];
                $booking_time = $_POST['booking_time'];
                $venue = $_POST['venue'];
                // how to retrieve the filename of the image 
                $image_filename = $_FILES["image"]["name"];
                // how to retrieve the temporary filename path 
                $temp_file = $_FILES["image"]["tmp_name"];
                // define the upload directory destination
                $destination = './static/photos/';
                $target_file = $destination . $image_filename;
                $_POST['image_filename'] = $image_filename;

                $uri = 'bookings';
                $response = $this->client->request('POST', $uri, ['form_params' => $_POST]);
                $data = json_decode($response->getBody()->getContents(), true);
                $message = $data['message'];
                // now move the file to the destination directory 
                move_uploaded_file($temp_file, $target_file);

                echo $this->view->render('message.html.twig', ['message' => $message]);
                // Load viewContact Page after 5 seconds
                header("Refresh:5;url=?action=viewBookings");
            } else {
                echo $this->view->render('addBooking.html.twig', ['errors' => $errors]);
            }
        } else {
            // redisplay the form with validation error
            echo $this->view->render('addBooking.html.twig');
        }
    }
	else{
		echo $this->view->render('login.html.twig');
	}
	}
	

    function viewBookings() {
		session_start();
		if (isset($_SESSION['loggedIn']) &&$_SESSION['loggedIn']){
        $uri = 'bookings';
        $response = $this->client->get($uri);
        $records = json_decode($response->getBody()->getContents(), true);
        echo $this->view->render('data.html.twig', ['records' => $records]);
    }
    else{
		echo $this->view->render('login.html.twig');
	}
	}

    function profileSetting() {
        session_start();
        
        if (isset($_SESSION['loggedIn']) &&$_SESSION['loggedIn']) {
            $id = $_SESSION['userId'];
            $uri = "profileSetting/$id";
            $response = $this->client->get($uri);
                    
                        
                    $records = json_decode($response->getBody()->getContents(), true);
                    
                    
                    echo $this->view->render('userProfile.html.twig', ['records' => $records]);
        }
         else {
            echo $this->view->render('login.html.twig');
        }
    }

    function searchBookings() {
        session_start();
        
        if (isset($_SESSION['loggedIn']) &&$_SESSION['loggedIn']) {
            try {
                // error if the URL is encoded like searching in khmer language will cause error
            
            if (isset($_POST['submit'])) {
                $keyword = $_POST['keyword'];
                // $keyword = urldecode($keyword);
                if (strlen($keyword) > 0) {
                    // decode other language EX: khmer
                    // $keyword = urldecode($keyword);
                    $uri = "bookings/keyword/$keyword";
                    $response = $this->client->get($uri);
                    
                        
                    $records = json_decode($response->getBody()->getContents(), true);
                    
                    
                    echo $this->view->render('data.html.twig', ['records' => $records]);
                } else {
                    $message = 'Missing input';
                    $errors['keyword'] = $message;
//                $uri = "bookings";
//                $response = $this->client->get($uri);
//                $records = json_decode($response->getBody()->getContents(), true);
                    echo $this->view->render('search.html.twig', ['errors' => $errors]);
                }
            } else {
                echo $this->view->render('search.html.twig');
            }
        } catch (Exception $e) {
                echo $this->view->render('message.html.twig', ['message' => "Error searching keyword"]);
            }
        }
         else {
            echo $this->view->render('login.html.twig');
        }
    }

// logout
    function logOut() {
        // Initialize the session
        session_start();

// Unset all of the session variables
        $_SESSION['loggedIn'] = false;

// Destroy the session.
        session_destroy();

// Redirect to login page
//        header("Refresh:1;url=?action=logIn");
        $message = "You are now logged out!";
        echo $this->view->render('message.html.twig', ["message"=>$message]);
        exit;

//        echo $this->view->render('index.html.twig');
    }

    function userHome() {

        echo $this->view->render('UserIndex.html.twig');
    }

    // Login part

    function logIn() {
        session_start();
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            if (strlen($email) > 0) {
                
                $uri = "accounts/user";
//                $response = $this->client->get($uri);
                $response = $this->client->request('POST', $uri, ['form_params' => $_POST]);
//                $records = json_decode($response->getBody()->getContents(), true);
//                echo $this->view->render('data.html.twig', ['records' => $records]);

                $data = json_decode($response->getBody()->getContents(), true);
                $message = $data['message'];
                $loginUser = $data['loginUser'];
                $loginStatus = $data['loginStatus'];
                $userId = $data['userId'];
                $_SESSION['userId'] = $userId;
                // if ($message == "You are now logged in!") {
                    if ($loginStatus) {
                    $_SESSION['loggedIn'] = true;
//                echo $this->view->render('message.html.twig', ['message' => $message]);
                    echo $this->view->render('UserIndex.html.twig', ['message' => $message,'loginUser'=>$loginUser,'userphoto'=>$loginUser, 'userId'=> $userId] );
                } else {
					
                    echo $this->view->render('message.html.twig', ['message' => $message]);
                }
            } else {
                $message = 'Missing input';
                $errors['email'] = $message;
//                $uri = "bookings";
//                $response = $this->client->get($uri);
//                $records = json_decode($response->getBody()->getContents(), true);
                echo $this->view->render('login.html.twig', ['errors' => $errors]);
            }
        } else {
            echo $this->view->render('login.html.twig');
        }
    }

    // end of login

    function deleteBooking() {
        if (isset($_GET['image_filename'])) {
            $image_filename = $_GET['image_filename'];
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $uri = "bookings/$id";
            $response = $this->client->delete($uri);
            $data = json_decode($response->getBody()->getContents(), true);
            $message = $data['message'];
            echo $this->view->render('message.html.twig', ['message' => $message]);
            $destination = './static/photos/';
            unlink($destination . $image_filename);
//            if ($success) {
//                $message = 'Contact has been successfully deleted. Loading View Contacts page in 5 seconds';
//            } else {
//                $message = 'Contact failed to delete. Loading View Contacts page in 5 seconds';
//                echo $this->view->render('message.html.twig', ['message' => $message]);
//            }
        }
        // Load viewContact Page after 5 seconds
        header("Refresh:5;url=?action=viewBookings");
    }

    // sign up part
    function signUp() {
        if (isset($_POST['submit'])) {

//            Retrieve form data safely
//            validate the form data
            $errors = [];
            if (isset($_POST["first_name"])) {
                $first_name = trim($_POST["first_name"]);
                $message = alpha_spaces($first_name);
                if (strlen($message) > 0) {
                    $errors['first_name'] = $message;
                }
            }

            if (isset($_POST["last_name"])) {
                $last_name = trim($_POST["last_name"]);
                $message = alpha_spaces($last_name);
                if (strlen($message) > 0) {
                    $errors['last_name'] = $message;
                }
            }


            if (isset($_POST["email"])) {
                $email = trim($_POST["email"]);
                $message = email($email);
                if (strlen($message) > 0) {
                    $errors['email'] = $message;
                }
            }


            if (isset($_POST["phone"])) {
                $phone = trim($_POST["phone"]);
                $message = mobile($phone);
                if (strlen($message) > 0) {
                    $errors['phone'] = $message;
                }
            }

			if (isset($_POST["password"])) {
                $password = $_POST["password"];
                if (strlen($password) == 0) {
                    $errors['password'] = "Password is can't be empty";
                }
            }


            if (count($errors) == 0) {
                // Retrieve and assign post array values to form variables
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $phone = $_POST["phone"];

                $uri = 'accounts';
                $response = $this->client->request('POST', $uri, ['form_params' => $_POST]);
                $data = json_decode($response->getBody()->getContents(), true);
                $message = $data['message'];
				
                echo $this->view->render('message.html.twig', ['message' => $message]);
                // Load viewContact Page after 5 seconds
				//header("Refresh:5;url=?action=viewBookings");
            } else {
                echo $this->view->render('signUp.html.twig', ['errors' => $errors]);
            }
        } else {
            // redisplay the form with validation error
            echo $this->view->render('signUp.html.twig');
        }
    }

    function getData() {
        $uri = 'data';
        $response = $this->client->get($uri);
        $records = json_decode($response->getBody()->getContents(), true);
        echo "Sample Data in PHP array format: <br><br>";
        foreach ($records as $row) {
            foreach ($row as $key => $value) {
                echo "$key : $value <br>";
            }
        }
    }

}
