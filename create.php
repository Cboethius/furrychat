<?php
session_start();

if (isset($_SESSION['username'])) {

?>

    <body class="body">
        <div class="container_box">
            <div id="result"></div>
            <a href="index.php">folder</a>
        </div>
        <link rel="stylesheet" href="css/furry-friends.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </body>
    <script>
        $(document).ready(function() {
            fetchUser(); //This function will load all data on web page when page load
            function fetchUser() // This function will fetch data from table and display under <div id="result">
            {
                var action = "Load";
                $.ajax({
                    url: "action.php", //Request send to "action.php page"
                    method: "POST", //Using of Post method for send data
                    data: {
                        action: action
                    }, //action variable data has been send to server
                    success: function(data) {
                        $('#result').html(data); //It will display data under div tag with id result
                    }
                });
            }

            //This JQuery code is for Delete customer data. If we have click on any customer row delete button then this code will execute
            $(document).on('click', '.delete', function() {
                var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
                if (confirm("Are you sure you want to remove this data?")) //Confim Box if OK then
                {
                    var action = "Delete"; //Define action variable value Delete
                    $.ajax({
                        url: "action.php", //Request send to "action.php page"
                        method: "POST", //Using of Post method for send data
                        data: {
                            id: id,
                            action: action
                        }, //Data send to server from ajax method
                        success: function(data) {
                            fetchUser(); // fetchUser() function has been called and it will load data under divison tag with id result
                            alert(data); //It will pop up which data it was received from server side
                        }
                    })
                } else //Confim Box if cancel then 
                {
                    return false; //No action will perform
                }
            });
        });
    </script>

    </html>
<?php
} else {
    header("Location: index.php");
}
exit;
?>