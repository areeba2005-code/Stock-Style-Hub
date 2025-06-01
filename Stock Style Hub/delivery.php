<?php
// Database connection
$host = "localhost";
$dbname = "stock style hub";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Create a new delivery
if (isset($_POST['create'])) {
    // Debug: Check submitted data
    var_dump($_POST);

    $required_fields = [
        'Delivery_ID' => 'Delivery_ID',
        'WareHouse_ID' => 'WareHouse_ID',
        'OutletID' => 'OutletID',
        'CompanyName' => 'CompanyName',
        'Vehicle_Type' => 'Vehicle_Type',
        'Vehicle_Number' => 'Vehicle_Number',
        'Delivery_Date' => 'Delivery_Date',
        'Status' => 'Status',
        'Tracking_Number' => 'Tracking_Number',
        'Receiving_Date' => 'Receiving_Date' // Keep this as Receiving_Date for the form
    ];

    $missing_fields = [];
    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $label;
        }
    }

    if (!empty($missing_fields)) {
        die("Error: The following fields are required: " . implode(", ", $missing_fields) . ".");
    }

    $data = [
        'Delivery_ID' => $_POST['Delivery_ID'],
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'OutletID' => $_POST['OutletID'],
        'CompanyName' => $_POST['CompanyName'],
        'Vehicle_Type' => $_POST['Vehicle_Type'],
        'Vehicle_Number' => $_POST['Vehicle_Number'],
        'Delivery_Date' => $_POST['Delivery_Date'],
        'Status' => $_POST['Status'],
        'Tracking_Number' => $_POST['Tracking_Number'],
        'Reciving_Date' => $_POST['Receiving_Date'] // Map Receiving_Date to Reciving_Date for the database
    ];

    // Debug: Check parameters being passed to PDO
    var_dump($data);

    $sql = "INSERT INTO delivery (Delivery_ID, WareHouse_ID, OutletID, CompanyName, Vehicle_Type, Vehicle_Number, Delivery_Date, Status, Tracking_Number, Reciving_Date) 
            VALUES (:Delivery_ID, :WareHouse_ID, :OutletID, :CompanyName, :Vehicle_Type, :Vehicle_Number, :Delivery_Date, :Status, :Tracking_Number, :Reciving_Date)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Update a delivery
if (isset($_POST['update'])) {
    $required_fields = [
        'Delivery_ID' => 'Delivery_ID',
        'WareHouse_ID' => 'WareHouse_ID',
        'OutletID' => 'OutletID',
        'CompanyName' => 'CompanyName',
        'Vehicle_Type' => 'Vehicle_Type',
        'Vehicle_Number' => 'Vehicle_Number',
        'Delivery_Date' => 'Delivery_Date',
        'Status' => 'Status',
        'Tracking_Number' => 'Tracking_Number',
        'Receiving_Date' => 'Receiving_Date'
    ];

    $missing_fields = [];
    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $label;
        }
    }

    if (!empty($missing_fields)) {
        die("Error: The following fields are required: " . implode(", ", $missing_fields) . ".");
    }

    $data = [
        'Delivery_ID' => $_POST['Delivery_ID'],
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'OutletID' => $_POST['OutletID'],
        'CompanyName' => $_POST['CompanyName'],
        'Vehicle_Type' => $_POST['Vehicle_Type'],
        'Vehicle_Number' => $_POST['Vehicle_Number'],
        'Delivery_Date' => $_POST['Delivery_Date'],
        'Status' => $_POST['Status'],
        'Tracking_Number' => $_POST['Tracking_Number'],
        'Reciving_Date' => $_POST['Receiving_Date'] // Map to database column
    ];

    $sql = "UPDATE delivery SET WareHouse_ID = :WareHouse_ID, OutletID = :OutletID, CompanyName = :CompanyName, 
            Vehicle_Type = :Vehicle_Type, Vehicle_Number = :Vehicle_Number, Delivery_Date = :Delivery_Date, 
            Status = :Status, Tracking_Number = :Tracking_Number, Reciving_Date = :Reciving_Date 
            WHERE Delivery_ID = :Delivery_ID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete a delivery
if (isset($_GET['delete'])) {
    $delivery_id = $_GET['delete'];
    $sql = "DELETE FROM delivery WHERE Delivery_ID = :Delivery_ID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute(['Delivery_ID' => $delivery_id]);
    } catch (PDOException $e) {
        die("Delete failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Delivery</title>

    <style>
        .main-content { padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .btn-add { background: #28a745; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #3C91E6; color: #fff; }
        .btn-edit, .btn-delete { padding: 5px 10px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-edit { background: #007bff; color: #fff; }
        .btn-delete { background: #dc3545; color: #fff; }
        #modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 20px; border-radius: 8px; width: 400px; position: relative; }
        .close-button { position: absolute; top: 10px; right: 10px; font-size: 24px; cursor: pointer; }
        .modal-content input, .modal-content select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .modal-content button { padding: 10px 20px; margin-right: 10px; border: none; border-radius: 4px; cursor: pointer; }
        .modal-content button:first-of-type { background: #28a745; color: #fff; }
        .modal-content button:last-of-type { background: #dc3545; color: #fff; }
        .error-message { color: red; display: none; margin-bottom: 10px; }
    </style>
</head>
<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-factory'></i>
            <span class="text">Stock Style Hub</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="Employee.php" target="_blank">
                    <i class='bx bx-child'></i>
                    <span class="text">Employee</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-package'></i>
                    <span class="text">Delivery</span>
                </a>
            </li>
            <li>
                <a href="outlet.php">
                    <i class='bx bxs-store'></i>
                    <span class="text">Outlet</span>
                </a>
            </li>
            <li>
                <a href="WareHouse.php" target="_blank">
                    <i class='bx bxs-store'></i>
                    <span class="text">WareHouse</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-category'></i>
                    <span class="text">Category</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-category-alt'></i>
                    <span class="text">Category Wear</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" class="Aboutus">
                    <i class='bx bxs-help-circle'></i>
                    <span class="text">About Us</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search Here ...">
                    <button type="submit" class="search-btn"><i class='bx bx-search-alt-2'></i></button>
                </div>
            </form>
            <a href="#" class="profile">
                <img src="people.png" alt="Profile">
            </a>
        </nav>
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Delivery Information</h1>
                </div>
            </div>

            <div class="main-content">
                <div class="card">
                    <button class="btn-add" onclick="openForm()"><i class="fas fa-plus"></i> Add</button>
                    
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Delivery_ID</th>
                                    <th>WareHouse_ID</th>
                                    <th>OutletID</th>
                                    <th>CompanyName</th>
                                    <th>Vehicle_Type</th>
                                    <th>Vehicle_Number</th>
                                    <th>Delivery_Date</th>
                                    <th>Status</th>
                                    <th>Tracking_Number</th>
                                    <th>Reciving_Date</th> <!-- Match database column -->
                                </tr>
                            </thead>
                            <tbody id="warehouse-body">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM delivery");
                                $stmt->execute();
                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (empty($results)) {
                                    echo "<tr><td colspan='10'>No data found.</td></tr>";
                                } else {
                                    foreach ($results as $row) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Delivery_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['WareHouse_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['OutletID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Vehicle_Type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Vehicle_Number']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Delivery_Date']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Tracking_Number']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Reciving_Date']) . "</td>";
                                        echo "<td>";
                                        echo "<button class='btn-edit' onclick=\"editRow('" . htmlspecialchars($row['Delivery_ID']) . "')\"><i class='fas fa-pen'></i></button>";
                                        echo "<a href='?delete=" . urlencode($row['Delivery_ID']) . "' class='btn-delete' onclick=\"return confirm('Are you sure?');\"><i class='fas fa-trash'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <div id="modal" style="display: none;">
        <div class="modal-content">
            <h3 id="form-title">Add Delivery Information</h3>
            <span class="close-button" onclick="closeForm()">×</span>
            <form method="post" id="deliveryForm">
                <input type="text" name="Delivery_ID" id="input-delivery-id" placeholder="Delivery ID" required>
                <input type="text" name="WareHouse_ID" id="input-warehouse-id" placeholder="Warehouse ID" required>
                <input type="text" name="OutletID" id="input-outlet-id" placeholder="Outlet ID" required>
                <input type="text" name="CompanyName" id="input-company-name" placeholder="Company Name" required>
                <input type="number" name="Vehicle_Type" id="input-vehicle-type" placeholder="Vehicle Type" required>
                <input type="text" name="Vehicle_Number" id="input-vehicle-number" placeholder="Vehicle Number" required>
                <input type="date" name="Delivery_Date" id="input-delivery-date" placeholder="Delivery Date" required>
                <input type="text" name="Status" id="input-status" placeholder="Status" required>
                <input type="number" name="Tracking_Number" id="input-tracking-number" placeholder="Tracking Number" required>
                <input type="date" name="Receiving_Date" id="input-receiving-date" placeholder="Receiving Date" required>
                <p id="error-message" class="error-message" style="display: none;">All fields are required!</p>
                <button type="submit" name="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>" onclick="return validateForm()">Save</button>
                <button type="button" onclick="closeForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    function openForm(deliveryId = null) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById('form-title').textContent = deliveryId ? 'Edit Delivery Entry' : 'Add Delivery Entry';

        if (deliveryId) {
            <?php
            if (isset($_GET['edit'])) {
                $stmt = $union->prepare("SELECT * FROM delivery WHERE Delivery_ID = :Delivery_ID");
                $stmt->execute(['Delivery_ID' => $_GET['edit']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
            ?>
                    document.getElementById('input-delivery-id').value = '<?php echo htmlspecialchars($row['Delivery_ID']); ?>';
                    document.getElementById('input-warehouse-id').value = '<?php echo htmlspecialchars($row['WareHouse_ID']); ?>';
                    document.getElementById('input-outlet-id').value = '<?php echo htmlspecialchars($row['OutletID']); ?>';
                    document.getElementById('input-company-name').value = '<?php echo htmlspecialchars($row['CompanyName']); ?>';
                    document.getElementById('input-vehicle-type').value = '<?php echo htmlspecialchars($row['Vehicle_Type']); ?>';
                    document.getElementById('input-vehicle-number').value = '<?php echo htmlspecialchars($row['Vehicle_Number']); ?>';
                    document.getElementById('input-delivery-date').value = '<?php echo htmlspecialchars($row['Delivery_Date']); ?>';
                    document.getElementById('input-status').value = '<?php echo htmlspecialchars($row['Status']); ?>';
                    document.getElementById('input-tracking-number').value = '<?php echo htmlspecialchars($row['Tracking_Number']); ?>';
                    document.getElementById('input-receiving-date').value = '<?php echo htmlspecialchars($row['Reciving_Date']); ?>';
                    document.getElementById('input-delivery-id').readOnly = true;
            <?php
                }
            }
            ?>
        } else {
            document.getElementById('deliveryForm').reset();
            document.getElementById('input-delivery-id').readOnly = false;
        }
    }

    function closeForm() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById('error-message').style.display = 'none';
    }

    function validateForm() {
        const deliveryId = document.getElementById('input-delivery-id').value;
        const warehouseId = document.getElementById('input-warehouse-id').value;
        const outletId = document.getElementById('input-outlet-id').value;
        const companyName = document.getElementById('input-company-name').value;
        const vehicleType = document.getElementById('input-vehicle-type').value;
        const vehicleNumber = document.getElementById('input-vehicle-number').value;
        const deliveryDate = document.getElementById('input-delivery-date').value;
        const status = document.getElementById('input-status').value;
        const trackingNumber = document.getElementById('input-tracking-number').value;
        const receivingDate = document.getElementById('input-receiving-date').value;
        const errorMessage = document.getElementById('error-message');

        if (!deliveryId || !warehouseId || !outletId || !companyName || !vehicleType || !vehicleNumber || !deliveryDate || !status || !trackingNumber || !receivingDate) {
            errorMessage.textContent = 'The following fields are required: ' + 
                (!deliveryId ? 'Delivery ID, ' : '') +
                (!warehouseId ? 'Warehouse ID, ' : '') +
                (!outletId ? 'Outlet ID, ' : '') +
                (!companyName ? 'Company Name, ' : '') +
                (!vehicleType ? 'Vehicle Type, ' : '') +
                (!vehicleNumber ? 'Vehicle Number, ' : '') +
                (!deliveryDate ? 'Delivery Date, ' : '') +
                (!status ? 'Status, ' : '') +
                (!trackingNumber ? 'Tracking Number, ' : '') +
                (!receivingDate ? 'Receiving Date, ' : '').replace(/, $/, '');
            errorMessage.style.display = 'block';
            return false;
        }

        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (deliveryDate && !dateRegex.test(deliveryDate)) {
            errorMessage.textContent = 'Delivery Date must be in YYYY-MM-DD format.';
            errorMessage.style.display = 'block';
            return false;
        }
        if (receivingDate && !dateRegex.test(receivingDate)) {
            errorMessage.textContent = 'Receiving Date must be in YYYY-MM-DD format.';
            errorMessage.style.display = 'block';
            return false;
        }

        errorMessage.style.display = 'none';
        return true;
    }

    function editRow(deliveryId) {
        window.location.href = `?edit=${deliveryId}`;
    }
    </script>
</body>
</html>