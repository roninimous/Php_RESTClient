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
        $this->client = new Client(['base_uri' => 'http://localhost/RESTServer4597377/']);
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
            $uri = "bookings/$id";
            $response = $this->client->get($uri);
            $record = json_decode($response->getBody()->getContents(), true);
            // If the submit button is not submitted, show the edit form and put the $id into the form
            echo $this->view->render('editBooking.html.twig', ['record' => $record]);
        }
    }

    function addBooking() {
        if (isset($_POST['submit'])) {
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
            } else {
                $uri = "bookings";
                $response = $this->client->get($uri);
                $records = json_decode($response->getBody()->getContents(), true);
            }
            echo $this->view->render('data.html.twig', ['records' => $records]);
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
