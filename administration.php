<?php
require_once("inc/class/config.php");
require_once("inc/class/db.php");
require_once("../inc/class/booked.php");

$sql = "SELECT * FROM ".tbBooking. " WHERE status=1;";
$res = null;
if(isset($_POST['date'])&&$_POST['date']!=null){
	$sql = "SELECT * FROM ". tbBooking ." WHERE fromdate = ? and status=1;";
	$conn = DB::getDbConnection();
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s",$_POST['date']);
	$stmt->execute();
	$res = $stmt->get_result();
}else{
	$res = DB::execute($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resource management System</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="http://localhost/ht/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="http://localhost/ht/assets/css/paper-dashboard.css" rel="stylesheet"/>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-default ">
    <div class="container-fluid bg-info">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"> Allotment </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="http://localhost/ht" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="ti-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="ti-home"></i>
                        <p>About-Us</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ti-id-badge"></i>
                        <p>Contact-Us</p>
                    </a>
                </li>
                <li class="dropdown">
                     <?php 
					if(isset($_SESSION['username'])) 
						echo('<a href="'.LOGOUT.'" class="dropdown-toggle" data-toggle="dropdown">
						<i class="ti-user"></i>
                        <p>Logout</p>
						</a>');
					else{
						echo('<a href="'.HOST.'" class="dropdown-toggle" data-toggle="dropdown">
						<i class="ti-user"></i>
                        <p>Login</p>
						</a>');
					}
					?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div>
    <form action="index.php" method="post">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <input type="date" class="btn btn-info" placeholder="Date" name="date">
            </div>
            <div class="col-md-2">
                <button name="" class="btn btn-info">Submit</button>
            </div>
			<div class="col-md-2">
                <a href="index.php?q=pd" class="btn btn-info">Pending Request</a>
         </div>
        </div>
    </form>
    <br>
    <br>
        <table class="table table-bordered">
            <thead class="">
            <tr>
                <th scope="col">Sr.No.</th>
                <th scope="col">Resource Name</th>
                <th scope="col">Allotment</th>
                <th scope="col">Name</th>
                <th scope="col">Purpose</th>
                <th scope="col">Description</th>
                <th scope="col">department</th>
            </tr>
            </thead>
            <tbody>
            <?php
				$i=0;
				echo($res->num_rows);
				while($row = $res->fetch_assoc()){
					
					$book = new Booked($row);
					$i++;
					$sql = "SELECT * FROM ". tbUser ." WHERE uname = '".$row['userid']."';";
					$resuser = DB::execute($sql);
					$rowuser = $resuser->fetch_assoc();
					
					$sql = "SELECT * FROM ". tbResource ." WHERE resid = '".$row['resid']."'";
					$resresource = DB::execute($sql);
					while($rowresource = $resresource->fetch_assoc()){
						echo('<tr><th scope="row">'.$i.'</th>');
						echo('<td>'.$rowresource['resname'].'</td>');
						echo('<td>'.$rowuser['fname'].'</td>');
						echo('<td>'.$row['fromdate'].'</td>');
						echo('<td>'.$row['purpose'].'</td>');
						echo('<td>'.$row['description'].'</td>');
						echo('<td>'.$rowuser['deptname'].'</td></tr>');
					}
				}
				?>
            
            </tbody>
        </table>

</div>


</body>
</html>