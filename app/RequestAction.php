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
        $this->client = new Client(['base_uri' => 'http://localhost/RESTServerUserMod/']);
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
            $errors =[];
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
        } else{
//          echo $this->view->render('editBooking.html.twig', ['errors' => $errors]);
            $errortitle = "*Please do not leave empty field and make sure your data is valid*";
            $uri = "bookings/$id";
            $response = $this->client->get($uri);
            $record = json_decode($response->getBody()->getContents(), true);
            // If the submit button is not submitted, show the edit form and put the $id into the form
            echo $this->view->render('editBooking.html.twig', ['errors' => $errors,'record' => $record, 'errortitle' =>$errortitle]);
        }
        
    }else{
            $uri = "bookings/$id";
            $response = $this->client->get($uri);
            $record = json_decode($response->getBody()->getContents(), true);
            // If the submit button is not submitted, show the edit form and put the $id into the form
            echo $this->view->render('editBooking.html.twig', ['record' => $record]);
        }
    }

    function addBooking() {
        if (isset($_POST['submit'])) {
 
//            Retrieve form data safely
//            validate the form data
            $errors =[];
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
            }else{
            echo $this->view->render('addBooking.html.twig',['errors'=>$errors]);
        }
        }else {
                // redisplay the form with validation error
                echo $this->view->render('addBooking.html.twig');
            }
    }

    function viewBookings() {
        $uri = 'bookings';
        $response = $this->client->get($uri);
        $records = json_decode($response->getBody()->getContents(), true);
        echo $this->view->render('data.html.twig', ['records' => $records]);
    }

    function searchBookings() {
        if (isset($_POST['submit'])) {
            $keyword = $_POST['keyword'];
            if (strlen($keyword)>0) {
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
                echo $this->view->render('search.html.twig',['errors'=>$errors]);
            }
            
        } else {
            echo $this->view->render('search.html.twig');
        }
    }

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
            $errors =[];
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



            if (isset($_POST["create_date"])) {
                $create_date = trim($_POST["create_date"]);
                $message = validateDate($create_date);
                if (strlen($message) > 0) {
                    $errors['create_date'] = $message;
                }
            }
            
            
         
            
            
            if (isset($_POST["street"])) {
                $street = $_POST["street"];
                $message = 'Missing input';
                if (strlen($street) == 0) {
                    $errors['street'] = $message;
                }
            }
            
            if (isset($_POST["city"])) {
                $city = $_POST["city"];
                $message = 'Missing input';
                if (strlen($city) == 0) {
                    $errors['city'] = $message;
                }
            }
            
            
            if (isset($_POST["state"])) {
                $state = $_POST["state"];
                $message = 'Missing input';
                if (strlen($state) == 0) {
                    $errors['state'] = $message;
                }
            }
            
            
            if (isset($_POST["zip"])) {
                $zip = $_POST["zip"];
                $message = 'Missing input';
                if (strlen($zip) == 0) {
                    $errors['zip'] = $message;
                }
            }
            
//            if (isset($_POST["phone_type"])) {
//                $phone_type = $_POST["phone_type"];
//                $message = 'Missing input';
//                if (strlen($phone_type) == 0) {
//                    $errors['phone_type'] = $message;
//                }
//            }
            
        
          



            if (count($errors) == 0) {
                 // Retrieve and assign post array values to form variables
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $booking_date = $_POST['booking_date'];          
            $venue = $_POST['venue'];


            $uri = 'bookings';
            $response = $this->client->request('POST', $uri, ['form_params' => $_POST]);
            $data = json_decode($response->getBody()->getContents(), true);
            $message = $data['message'];
            

            echo $this->view->render('message.html.twig', ['message' => $message]);
            // Load viewContact Page after 5 seconds
            header("Refresh:5;url=?action=viewBookings");
            }else{
            echo $this->view->render('signUp.html.twig',['errors'=>$errors]);
        }
        }else {
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
