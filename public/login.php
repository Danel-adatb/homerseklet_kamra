<?php

    include '../classes/requires/autoload.php';

    if(count($_POST) > 0) {
        $errors = User::check_instance()->login($_POST);

        if(empty($errors['email_error']) && empty($errors['password_error']) && empty($errors['invalid_data'])) {
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

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-header">
                            Login
                        </div>

                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?=isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                    <?php if(isset($errors) && ($errors['email_error'] != '' || $errors['invalid_data'] != '')) {
                                            foreach($errors as $key => $value) {
                                                if ($key == 'email_error') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <?php if(isset($errors) && ($errors['email_error'] != '' || $errors['invalid_data'] != '')) {
                                            foreach($errors as $key => $value) {
                                                if ($key == 'invalid_data') {
                                                    echo '<small class="text-danger">'. $value .'</small><br>';
                                                }
                                            }
                                        } ?>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Login</button>
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