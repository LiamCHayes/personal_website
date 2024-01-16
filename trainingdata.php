<html>

<head>
  <title>Training Data</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- linking css file -->
  <link rel="stylesheet" href="style.css">
  <!-- bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- font awesome -->
  <script src="https://kit.fontawesome.com/31149d48b0.js" crossorigin="anonymous"></script>
</head>


<body>
    <h1>Data Lookup</h1>

    <ul>
    <form action="/liam_hayes/trainingdata.php" method="get">
        <label for="name">Name: </label>
        <input type="text" id="name" name="name" value='Any'/>
        <br><br>
        <label for="flash">Flash:</label>
            <select name="flash" id="flash">
                <option value="-1">Any</option>
                <option value="1">Flash</option>
                <option value="0">No Flash</option>
            </select>
        <br><br>
            <label for="zone">Zone: </label>
            <select name="zone" id="zone">
                <option value="-1">Any</option>
                <option value="slab">Slab</option>
                <option value="accordion">Accordion</option>
                <option value="10">10</option>
                <option value="45">45</option>
                <option value="prow">prow</option>
                <option value="30">30</option>
            </select>
        <br><br>
        <button class="button">Search</button>
    </form>
    <form action="download.php" method="get">
        <button href="/download.php" class="button">Download</button>
    </form>
    <form action="index.php" method="get">
        <button href="/index.php" class="button">Back to Logger</button>
    </form>
    </ul>

    <p>
    <?php
        $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                ORDER BY sends_ID;";
        if (isset($_GET['name'])) {
            $name = ucfirst($_GET['name']);
            if ($name == 'Any') {
                echo 'Data for all teammembers';
            } else {
                $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                        INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                        INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                        WHERE firstName='" .$name. "'
                        ORDER BY sends_ID;";
                echo 'Data for ' .$name;
            }
        }
        if (isset($_GET['flash'])) {
            $flash = $_GET['flash'];
            if ($flash == '-1') {
                echo ', flashes and non-flashes';
            } else {
                if ($name != 'Any') {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE firstName='" .$name. "' AND flash='" .$flash. "'
                            ORDER BY sends_ID;";
                    if ($flash == 1) {
                        echo ', flashes';
                    } else {
                        echo ', non-flashes';
                    }
                } else {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE flash='" .$flash. "'
                            ORDER BY sends_ID;";
                    if ($flash == 1) {
                        echo ', flashes only';
                    } else {
                        echo ', non-flashes only';
                    }
                }

            }
        }
        if (isset($_GET['zone'])) {
            $zone = $_GET['zone'];
            if ($zone == '-1') {
                echo " in all zones";
            } else {
                if ($name != 'Any' && $flash != '-1') {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE firstName='" .$name. "' AND flash='" .$flash. "' AND zone='" .$zone. "'
                            ORDER BY sends_ID;";
                } elseif ($name == 'Any' && $flash != '-1') {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE flash='" .$flash. "' AND zone='" .$zone. "'
                            ORDER BY sends_ID;";
                } elseif ($name != 'Any' && $flash == '-1') {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE firstName='" .$name. "' AND zone='" .$zone. "'
                            ORDER BY sends_ID;";
                } else {
                    $qry = "SELECT date, grade, flash, firstName, zone FROM sends 
                            INNER JOIN l_teammembers ON sends.L_teammembers_id=l_teammembers.l_teammembers_id
                            INNER JOIN l_zone ON sends.L_zone_id=l_zone.l_zone_id
                            WHERE zone='" .$zone. "'
                            ORDER BY sends_ID;";
                }
                echo ' on the ' . $zone;
            }
        }
        ?>
        <br><br>
        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', 'l4mlnMySQL', 'health');
        if ($conn->connect_error) {
            die('Connection Failed  :  '.$conn->connect_error);
        } else {
            // Get data
            $result = $conn->query($qry);
            $all_sends = array();

            // Show data
            echo '<table class="data-table">
                    <tr class="data-heading">';  
            while ($sends = mysqli_fetch_field($result)) {
                echo '<th>' . $sends->name . '</th>';  //get field name for header
                $all_sends[] = $sends->name;  //save those to array
            }
            echo '</tr>'; //end tr tag

            while ($sendsRow = $result->fetch_row()) {
                echo '<tr>';
                for ($i = 0; $i < $result->field_count; $i++) {
                    echo '<td>' .$sendsRow[$i]. '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
            $conn->close();
        }
    ?>
    </p>
</body>

</html>