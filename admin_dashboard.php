<?php
require_once('config.php');

if(!isset($_SESSION['admin_id'])){
    header("location: index.php");
    exit();
}
?>

<html>

    <head>
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
    </head>



    <body>

<?php if(isset($_SESSION['success_message'])) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

        <div class="container">


            <div class="row">
                <div class="col-md-12">

                    <h2>Members List</h2>

                    <a href="export.php?what=members" class="btn btn-success btn-sm">Export</a>
                     <table class="table table-striped table-dark">
                        <thead class="thead-dark">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Trainer</th>
                                <th>Photo</th>
                                <th>Training Plan</th>
                                <th>Access Card</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $sql = 
                                "SELECT members.*,
                                training_plans.name AS training_plan_name,
                                trainers.first_name AS trainer_first_name,
                                trainers.last_name AS trainer_last_name
                                FROM members
                                LEFT JOIN training_plans ON members.training_plan_id = training_plans.plan_id
                                LEFT JOIN trainers ON members.trainer_id = trainers.trainer_id;";

                            $run = $conn->query($sql);
                            $results = $run->fetch_all(MYSQLI_ASSOC);
                            $select_members = $results; 
                            foreach($results as $result) : ?>

                                <tr>
                                    <td class="table-success"><?php echo $result['first_name']; ?></td>
                                    <td class="table-success"><?php echo $result['last_name']; ?></td>
                                    <td><?php echo $result['email']; ?></td>
                                    <td><?php echo $result['phone_number']; ?></td>
                                    <td><?php 
                                    if($result['trainer_first_name']) {
                                        echo $result['trainer_first_name'] . " " . $result['trainer_last_name'];
                                    } 
                                    else { echo "NEMA TRENERA"; }
                                    ?></td>
                                    <td><img style="width: 60px; height: 60px;" src="
                                    <?php
                                    if($result['photo_path']){
                                        echo $result['photo_path'];
                                    } else {
                                        echo "member_photos/undefined_photo.png";
                                    }
                                    ?>"></td>
                                    <td><?php 
                                    if($result['training_plan_name']){echo $result['training_plan_name'];}
                                    else {echo "NEMA PLANA";}
                                    ?></td>
                                    <td><a target="_blank" href="<?php echo $result['access_card_pdf_path']; ?>">Access Card</a></td>
                                    <td><?php 
                                        $created_at = strtotime($result['created_at']); 
                                        $new_date = date("F, jS Y", $created_at);
                                        echo $new_date;
                                    ?></td>
                                    <td>
                                        <form action="delete_member.php" method="POST">
                                            <input type="hidden" name="member_id" value="<?php echo $result['member_id']; ?>">
                                            <button class="btn btn-danger">DELETE</button>
                                        </form>
                                    </td>

                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                     </table>

                </div>

                <div class="col-md-12">
                    <h2>Trainers List</h2>

                    <a href="export.php?what=trainers" class="btn btn-success btn-sm">Export</a>

                    <table class="table table-striped table-dark">
                    <thead class="thead-dark">
                        <tr>
                            <th>Trainer ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Photo</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $sql = 
                            "SELECT *
                            FROM trainers";
                            
                        $run = $conn->query($sql);
                        $results = $run->fetch_all(MYSQLI_ASSOC);
                        $select_trainers = $results;

                        foreach($results as $result) : ?>

                            <tr>
                                <td><?php echo $result['trainer_id']; ?></td>
                                <td class="table-success"><?php echo $result['first_name']; ?></td>
                                <td class="table-success"><?php echo $result['last_name']; ?></td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td><img style="width: 60px; height: 60px;" src="
                                    <?php
                                    if($result['photo_path']){
                                        echo $result['photo_path'];
                                    } else {
                                        echo "trainer_photos/undefined_photo.png";
                                    }
                                    ?>">
                                </td>
                                <td><?php 
                                    $created_at = strtotime($result['created_at']); 
                                    $new_date = date("F, jS Y", $created_at);
                                    echo $new_date;
                                ?></td>
                                <td>
                                    <form action="delete_trainer.php" method="POST">
                                        <input type="hidden" name="trainer_id" value="<?php echo $result['trainer_id']; ?>">
                                        <button class="btn btn-danger">DELETE</button>
                                    </form>
                                </td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-6">
                    <h2>Register Member</h2>
                    <form action="register_member.php" method="POST" enctype="multipart/form-data">
                        <p>First Name:</p> <input type="text" class="form-control" name="first_name"><br>
                        <p>Last Name:</p> <input type="text" class="form-control" name="last_name"><br>
                        <p>Email:</p> <input type="email" class="form-control" name="email"><br>
                        <p>Phone Number:</p> <input type="text" class="form-control" name="phone_number"><br>
                        <p>Training Plan:</p> 
                        <select name="training_plan_id" class="form-control">
                            <option value="" disabled selected>Training plan</option>

                            <?php
                            
                            $sql = "SELECT * FROM training_plans";
                            $run = $conn->query($sql);
                            $results = $run->fetch_all(MYSQLI_ASSOC);
                        
                            foreach($results as $result) {
                                echo "<option value='" . $result['plan_id'] . "' >" . $result['name'] .  "</option>";
                            }

                            ?>
                        </select><br>
                        <input type="hidden" name="photo_path" id="photoPathInput" >
                        <div id="dropzone-upload" class="dropzone"></div>

                        <input type="submit" class="btn btn-primary mt-3" value="Register Member">
                    </form>
                </div>
                <div class="col-md-6">
                    <h2>Register Trainer</h2>
                    <form action="register_trainer.php" method="POST">
                        <p>First Name:</p>  <input type="text" class="form-control" name="first_name"><br>
                        <p>Last Name:</p>  <input type="text" class="form-control" name="last_name"><br>
                        <p>Email:</p>  <input type="email" class="form-control" name="email"><br>
                        <p>Phone Number:</p>  <input type="text" class="form-control" name="phone_number"><br>

                        <input type="hidden" name="photo_path_trainer" id="photoPathTrainerInput" >
                        <div id="dropzone-upload" class="dropzone"></div>

                        <input type="submit" class="btn btn-primary mt-3" value="Register Trainer ">
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2>Assign trainer to member</h2>
                    <form action="assign_trainer.php" method="POST">
                        <label for="">Select Member</label>
                        <select name="member" class="form-select">
                            <?php 
                                foreach ($select_members as $member) :
                            ?>
                            <option value="<?php echo $member['member_id']; ?>">
                                <?php echo $member['first_name'] . " " . $member['last_name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="">Select Trainer</label>
                        <select name="trainer" class="form-select">
                            <?php 
                                foreach ($select_trainers as $trainer) :
                            ?>
                            <option value="<?php echo $trainer['trainer_id']; ?>">
                                <?php echo $trainer['first_name'] . " " . $trainer['last_name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Assign Trainer</button>
                    </form>
                </div>
            </div>
        </div>


<?php
    $conn->close();
?>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

        <script>

            Dropzone.options.dropzoneUpload={
                url: "upload_photo_trainer.php",
                paramName: "photo",
                maxFilesize: 20, // MB
                acceptedFiles: "image/*",
                init: function () {
                    this.on("success", function (file, response) { 
                        const jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            document.getElementById('photoPathInput').value = jsonResponse.photo_path_trainer;
                        } else { 
                            console.error(jsonResponse.error);
                        }
                    });
                }
            };

            Dropzone.options.dropzoneUpload={
                url: "upload_photo_trainer.php",
                paramName: "photo",
                maxFilesize: 20, // MB
                acceptedFiles: "image/*",
                init: function () {
                    this.on("success", function (file, response) { 
                        const jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            document.getElementById('photoPathTrainerInput').value = jsonResponse.photo_path_trainer;
                        } else { 
                            console.error(jsonResponse.error);
                        }
                    });
                }
            };
        </script> 
    </body>
</html>

