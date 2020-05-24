<?php
include("db.php");
$pagename = "Process Orders"; //Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>" . $pagename . "</title>"; //display name of the page as window title
echo "<body>";
include("headfile.html"); //include header layout file
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";
if ($_SESSION["user_type"] == "Administrator") {
    if (isset($_POST['ordStatus'])) {
        $SQL = "UPDATE w1714855_0.Orders
            SET orderStatus ='" . $_POST['ordStatus'] . "' WHERE orderNo=" . $_POST['upd_ordNo'] . ";";
        mysqli_query($conn, $SQL);
    } else if (isset($_POST['ordStatus1'])) {
        $SQL = "UPDATE w1714855_0.Orders
            SET orderStatus ='" . $_POST['ordStatus1'] . "' WHERE orderNo=" . $_POST['upd_ordNo1'] . ";";
        mysqli_query($conn, $SQL);
    }

    echo "<table>";

    $SQL = "SELECT Orders.orderNo, Orders.orderDateTime,Orders.orderStatus,Product.prodName,Order_Line.quantityOrdered, Users.userId, Users.userFName, Users.userSName
            FROM Orders, Users, Product, Order_Line
            WHERE Order_Line.orderNo = Orders.orderNo AND Order_Line.prodId=Product.prodId AND Orders.userId = Users.userId";
    $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error("Error"));
    //create an array of records (2 dimensional variable) called $arrayp.
    //populate it with the records retrieved by the SQL query previously executed.
    //Iterate through the array i.e while the end of the array has not been reached, run through it

    $orderNo = 0;
    while ($arrayorders = mysqli_fetch_array($exeSQL)) {
        if ($orderNo != $arrayorders['orderNo']) {
            echo "<tr><th>OrderNo</th><th >Order Date Time</th><th>User Id</th><th>Name</th><th>Surname</th><th>Status</th><th>Product</th><th>Quantity</th></tr>";
            echo "<tr><td><b>No: " . $arrayorders['orderNo'] . "</b></td>";
            echo "<td>" . $arrayorders['orderDateTime'] . "</td>";
            echo "<td>" . $arrayorders['userId'] . "</td>";
            echo "<td>" . $arrayorders['userFName'] . "</td>";
            echo "<td>" . $arrayorders['userSName'] . "</td>";
            if ($arrayorders['orderStatus'] == "Placed") {
                echo "<td><form action=processorders.php method=post>";
                echo "<select name='ordStatus'>";
                echo "<option value='Placed' selected>Placed</option>";
                echo "<option value='Ready To Collect'>Ready To Collect</option>";
                echo "</select>";
                echo "<input type=hidden name='upd_ordNo' value=" . $arrayorders['orderNo'] . ">";
                echo "<input type=submit value='Update'></form></td>";
            } else if ($arrayorders['orderStatus'] == "Ready To Collect") {
                echo "<td><form action=processorders.php method=post>";
                echo "<select name='ordStatus1'>";
                echo "<option value='Placed' selected>Ready To Collect</option>";
                echo "<option value='Collected'>Collected</option>";
                echo "</select>";
                echo "<input type=hidden name='upd_ordNo1' value=" . $arrayorders['orderNo'] . ">";
                echo "<input type=submit value='Update'></form></td>";
            } else {
                echo "<td><b>" . $arrayorders['orderStatus'] . "</b></td>";
            }
            echo "<td>" . $arrayorders['prodName'] . "</td>";
            echo "<td>" . $arrayorders['quantityOrdered'] . "</td></tr>";
            $orderNo = $arrayorders['orderNo'];
        } else {
            echo "<tr><td colspan=6></td>";
            echo "<td>" . $arrayorders['prodName'] . "</td>";
            echo "<td>" . $arrayorders['quantityOrdered'] . "</td></tr>";
        }
    }
    echo "</table><br>";
} else {
    echo "Only Admins can process orders.";
}
include("footfile.html"); //include head layout
echo "</body>";
