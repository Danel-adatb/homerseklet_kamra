<?php
    
    include '../classes/requires/autoload.php';

    $logged_in = User::check_instance()->is_user_logged_in();
    $is_admin = User::check_instance()->is_user_admin();
    

    if(!$logged_in) {
        header('Location: login.php');
        die;
    }

    if(!$is_admin) {
        header('Location: index.php');
        die;
    }

    $user_datas = User::check_instance()->get_user_all();
    $chamber_datas = Chamber::check_instance()->get_chamber_data();
    $headers_user = ['Azonosító', 'E-mail', 'Kamra Azonosító', 'Jogosultságok', 'Művelet'];
    $headers_chamber = ['Azonosító','Név','Város','Utca', 'Működés', 'Művelet'];
    foreach($user_datas as $data) {
        $users[] = $data;
    }

    foreach($chamber_datas as $data) {
        $chambers[] = $data;
    }

    if(isset($_POST['action']) && $_POST['action'] == 'delete' && $is_admin) {
        $id = $_POST['id'];
        $delete = User::check_instance()->delete_user_by_id($id);

        if($delete) {
            echo 'Sikeresen törölte a '. $id .' azonosítójú felhasználót!';
            die;
        } else {
            echo 'Valami hiba történt!';
            die;
        }
    }

    /*if(isset($_POST['action']) && $_POST['action'] == 'update' && $is_admin) {
        //TODO
    }*/
?>

<HTML>
    <HEAD>
        <TITLE>Hömérséklet nyilvántartó</TITLE>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link href="style/style.css?v=1" rel="stylesheet" type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </HEAD>
    <BODY>
        <div class="container-fluid">
            <div class="row">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <?php if($is_admin) { ?>
                            <a class="nav-link link-dark" href="index.php">Diagramm</a>
                        <?php } ?>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <?php if($is_admin) { ?>
                                    <li class="nav-item">
                                        <a href="create_user.php" class="nav-link" href="#">Felhasználó létrehozása</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="modify_user.php" class="nav-link" href="#">Felhasználók</a>
                                    </li>
                                <?php } else { ?>
                                    <li class="nav-item">
                                        <a class="nav-link link-dark" href="index.php"></a>Diagramm</a>
                                    </li>
                                <?php } ?>
                                
                            </ul>
                            <a href ="logout.php" class="nav-link link-dark">
                                Kijelentkezés
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="row mt-5 justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Felhasználók
                        </div>

                        <div class="card-body">
                            <div id="modify_user_form" class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <?php foreach($headers_user as $header) { ?>
                                                <th scope="col"><?php echo $header; ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($users as $user) { ?>
                                                <tr>
                                                    <td><?php echo $user->id; ?></td>
                                                    <td><?php echo $user->email; ?></td>
                                                    <td><?php echo $user->chamber_id; ?></td>
                                                    <td><?php echo $user->role; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger p-1 delete-button" id="<?php echo $user->id; ?>">Del</button>
                                                        <!--<button type="button" class="btn btn-warning p-1 update-button" id="<?php echo $user->id; ?>">Update</button>-->
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                </table>
                            </div>
                        </div> <!-- /card-body -->
                    </div> <!-- /card -->
                </div> <!-- /col -->
            </div>

            <div class="row mt-5 justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Kamrák
                        </div>

                        <div class="card-body">
                            <div id="modify_user_form" class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <?php foreach($headers_chamber as $header) { ?>
                                                <th scope="col"><?php echo $header; ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($chambers as $chamber) { ?>
                                                <tr>
                                                    <td><?php echo $chamber->id; ?></td>
                                                    <td><?php echo $chamber->name; ?></td>
                                                    <td><?php echo $chamber->city; ?></td>
                                                    <td><?php echo $chamber->street; ?></td>
                                                    <td><?php echo $chamber->available; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger p-1 delete-button" id="<?php echo $chamber->id; ?>">Del</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                </table>
                            </div>
                        </div> <!-- /card-body -->
                    </div> <!-- /card -->
                </div> <!-- /col -->
            </div>
        </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="js/user_modification.js"></script>

    </BODY>
</HTML>