<?php
    
    include '../config/config.php';

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

    if(count($_POST)> 0) {
        $errors = User::check_instance()->create_user($_POST);
        if(empty($errors['email_error']) && empty($errors['password_error']) && empty($errors['chamber_id_error']) && empty($errors['role_error'])) {
            header('Location: index.php');
            die;
        }
    }
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
                                        <a href="create_user.php" class="nav-link" href="#">Felhasználók</a>
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
                            Felhasználó létrehozása
                        </div>

                        <div class="card-body">
                            <form method="POST">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?=isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                        <?php if(isset($errors) && $errors['email_error'] != '') {
                                            foreach($errors as $key => $value) {
                                                if ($key === 'email_error') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">jelszó</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="<?=isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                                        <?php if(isset($errors) && $errors['password_error'] != '') {
                                            foreach($errors as $key => $value) {
                                                if ($key === 'password_error') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="chamber" class="form-label">Kamra azonosító</label>
                                        <input type="text" class="form-control" id="chamber" name="chamber"
                                            value="<?=isset($_POST['chamber']) ? $_POST['chamber'] : ''; ?>">
                                        <?php if(isset($errors) && $errors['chamber_id_error'] != '') {
                                            foreach($errors as $key => $value) {
                                                if ($key === 'chamber_id_error') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Szerepkör</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="choose" selected>
                                                <?=isset($_POST['role']) ? $_POST['role'] : 'Válasszon szerepkört'; ?>
                                            </option>
                                            <option value="admin">admin</option>
                                            <option value="user">user</option>
                                        </select>
                                        <?php if(isset($errors) && $errors['role_error'] != '') {
                                            foreach($errors as $key => $value) {
                                                if ($key === 'role_error') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Hozzáadás</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    </BODY>
</HTML>