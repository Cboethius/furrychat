<?php
session_start();

include 'includes/dbconn.php';
if (isset($_SESSION['username'])) {


    //For Load All Data
    if ($_POST["action"] == "Load") {
        $stmt = $conn->prepare("SELECT * FROM furFriend ORDER BY id DESC");
        $stmt->execute();
        $result =  $stmt->fetchAll();
        $output = '';
        $output .= '
        <table class="table">
        <tr>
         <th>User Name</th>
         <th>Furry Name</th>
         <th>Delete</th>
        </tr>

  ';
        if ($stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $output .= '
    <tr>
        <td>' . $row["user_names"] . '</td>
        <td>' . $row["furry_name"] . '</td>
        <td><button type="button" id="' . $row["id"] . '" class="btn btn-danger btn-xs delete">Delete</button></td>
    </tr>
    ';
            }
        } else {
            $output .= '
    <tr>
     <td align="center">Data not Found</td>
    </tr>
   ';
        }
        $output .= '</table>';
        echo $output;
    }

    //Add new furry friend 
    if ($_POST["action"] == "Add") {
        $stmt = $conn->prepare("
   INSERT INTO furFriend (user_names, furry_name) 
   VALUES (:user_names, :furry_name)
  ");
        $result = $stmt->execute(
            array(
                ':user_names' => $_POST["first_Name"],
                ':furry_name' => $_POST["lastName"]
            )
        );
        if (!empty($result)) {
            echo 'Data Inserted';
        }
    }

    //This Code is for fetch single customer data for display on Modal
    if ($_POST["action"] == "Select") {
        $output = array();
        $stmt = $conn->prepare(
            "SELECT * FROM furFriend
   WHERE id = '" . $_POST["id"] . "' 
   LIMIT 1"
        );
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $output["user_names"] = $row["user_names"];
            $output["furry_name"] = $row["furry_name"];
        }
        echo json_encode($output);
    }


    if ($_POST["action"] == "Delete") {
        $stmt = $conn->prepare(
            "DELETE FROM furFriend WHERE id = :id"
        );
        $result = $stmt->execute(
            array(
                ':id' => $_POST["id"]
            )
        );
        if (!empty($result)) {
            echo 'Data Deleted';
        }
    }
}
