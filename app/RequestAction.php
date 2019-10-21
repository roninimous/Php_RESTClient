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
require __DIR__. '/../vendor/autoload.php';
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
        $this->client = new Client(['base_uri' => 'http://localhost:8080/RESTServer4597377/']);
//        $this->client = new Client(['base_uri' => 'http://localhost/TestSLIM/']);
        // tell Twig path to template files
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
// create a Twig object ready for use
        $this->view = new Environment($loader);
    }

    function index() {

        echo $this->view->render('index.html.twig');
    }

    function editContact() {
        // Check the id from the link if it is there or not
        if (isset($_GET['id'])) {
            // if the id is there, put it into $id variable
            $id = $_GET['id'];
        }

        if (isset($_POST['submit'])) {
// Retrieve and assign post array values to form variables
            $id = $_POST['id'];
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $mobile = $_POST["mobile"];
// how to retrieve the filename of the image 
            $photo_filename = $_FILES["image"]["name"];
// how to retrieve the temporary filename path 
            $temp_file = $_FILES["image"]["tmp_name"];
// define the upload directory destination
            $destination = './static/photos/';
            $target_file = $destination . $photo_filename;
// now move the file to the destination directory 
            move_uploaded_file($temp_file, $target_file);
            $values = ["$first_name", "$last_name", "$email", "$mobile", "$photo_filename"];
// Variable success = Call CRUD function ADD CONTACT and pass in values array as parameter
//            $success = $this->contacts->editContact($id, $values);
            if ($success) {
                $message = 'Contact has successfully updated. Loading View Contacts page in 5 seconds';
                echo $this->view->render('message.html.twig', ['message' => $message]);
            } else {
                $message = 'Contact failed to update. Loading View Contacts page in 5 seconds';
                echo $this->view->render('message.html.twig', ['message' => $message]);
            }
            // Load viewContact Page after 5 seconds
            header("Refresh:5;url=?action=viewContacts");
        } else {

            // If the submit button is not submitted, show the edit form and put the $id into the form
            echo $this->view->render('editContact.html.twig', ['id' => $id]);
        }
    }

    function addContact() {

        if (isset($_POST['submit'])) {
// Retrieve and assign post array values to form variables
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $mobile = $_POST["mobile"];
// how to retrieve the filename of the image 
            $photo_filename = $_FILES["image"]["name"];
// how to retrieve the temporary filename path 
            $temp_file = $_FILES["image"]["tmp_name"];

// define the upload directory destination
            $destination = './static/photos/';
            $target_file = $destination . $photo_filename;
// now move the file to the destination directory 
            move_uploaded_file($temp_file, $target_file);




            $values = ["$first_name", "$last_name", "$email", "$mobile", "$photo_filename"];
// Variable success = Call CRUD function ADD CONTACT and pass in values array as parameter
//            $success = $this->contacts->addContact($values);
            if ($success) {
                $message = "Contact has successfully added to Database. Loading View Contacts page in 5 seconds";
                echo $this->view->render('message.html.twig', ['message' => $message]);
            } else {
                $message = 'Contact failed to add to Database';
                echo $this->view->render('message.html.twig', ['message' => $message]);
            }// Load viewContact Page after 5 seconds
            header("Refresh:5;url=?action=viewContacts");
        } else {
            echo $this->view->render('addContact.html.twig');
        }
    }

    function viewContacts() {
        $uri = 'contacts';
        $response = $this->client->get($uri);
        $records = json_decode($response->getBody()->getContents(), true);

//        $records = $this->contacts->getContacts();
        echo $this->view->render('data.html.twig', ['records' => $records]);
    }

    function searchContact() {
        if (isset($_POST['submit'])) {
            $keyword = $_POST['keyword'];
//            $records = $this->contacts->searchContact($keyword);
            echo $this->view->render('data.html.twig', ['records' => $records]);
        } else {
            echo $this->view->render('search.html.twig');
        }
    }

    function deleteContact() {
        if (isset($_GET['photo_filename'])) {
            $photo_filename = $_GET['photo_filename'];
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
//            $success = $this->contacts->deleteContact($id);
            $destination = './static/photos/';
            unlink($destination . $photo_filename);
            if ($success) {
                $message = 'Contact has been successfully deleted. Loading View Contacts page in 5 seconds';
                echo $this->view->render('message.html.twig', ['message' => $message]);
            } else {
                $message = 'Contact failed to delete. Loading View Contacts page in 5 seconds';
                echo $this->view->render('message.html.twig', ['message' => $message]);
            }
        }
        // Load viewContact Page after 5 seconds
        header("Refresh:5;url=?action=viewContacts");
    }
    
    
    function getData(){
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
