<?php
    
    include '../classes/requires/autoload.php';

    $session = new Session();
    $USER = $session->get('USER'); //$USER['email'];
    $logged_in = User::check_instance()->is_user_logged_in();
    $is_admin = User::check_instance()->is_user_admin();

    if(!$logged_in) {
        header('Location: login.php');
        die;
    }

    $datas = Chart::check_instance()->get_chart_data();

    foreach($datas as $data) {
        $chartArray[] = $data;
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
                            <span>Üdvözlet <?php echo htmlspecialchars($USER['email']); ?></span>
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
                                        <a href="create_user.php" class="nav-link" href="#">Adminisztrátor</a>
                                    </li>
                                <?php } else { ?>
                                    <li class="nav-item">
                                        <a>Diagramm</a>
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
            
            <div class="row mt-5">
                <div class="chartCard">
                    <div class="chartBox">
                        <input type="date" class="form-control" id="input_date" onchange="DateFilter(this)">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        // Get the current date
        var today = new Date();
        var day = today.getDate();
        var month = today.getMonth() + 1;
        var year = today.getFullYear();
        var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
        document.getElementById('input_date').value = formattedDate;

        const chartArray = <?php echo json_encode($chartArray); ?>;
        var currentDate = '';
        var temperatures = [];
        var times = [];
        
        for(var i = 0; i < chartArray.length; i++) {
            if(chartArray[i].date == document.getElementById('input_date').value) {
                currentDate = chartArray[i].date;
                temperatures.push(chartArray[i].temp);
                times.push(new Date(currentDate + 'T' + chartArray[i].time));
            }
        }

        // setup 
        const data = {
            labels: times,
            datasets: [{
                label: 'Napi Hőmérsékletek: '+ formattedDate,
                data: temperatures,
                backgroundColor:'rgba(255, 26, 104, 0.2)',
                borderColor: 'rgba(255, 26, 104, 1)',
                tension: 0.4,
                
            }]
        };

        // config 
        const config = {
            type: 'line',
            data,
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'minute',
                            stepSize: 10,
                            tooltipFormat: 'HH:mm:ss',
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // render init block
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

        function DateFilter(date) {
            const inputDate = new Date(date.value);
            var currentDate = '';
            var temperatures = [];
            var times = [];

            for(var i = 0; i < chartArray.length; i++) {
                if(chartArray[i].date == document.getElementById('input_date').value) {
                    currentDate = chartArray[i].date;
                    temperatures.push(chartArray[i].temp);
                    times.push(new Date(currentDate + 'T' + chartArray[i].time));
                }
            }

            myChart.data.labels = times;
            myChart.data.datasets[0].label = 'Napi Hőmérséklet: ' + date.value;
            myChart.data.datasets[0].data = temperatures;

            myChart.update();            
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    </BODY>
</HTML>