<?php
// Database connection
$host = "localhost";
$dbname = "stock style hub"; // Enclose database name in backticks to handle spaces
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Create a new outlet
if (isset($_POST['create'])) {
    // Debug: Check submitted data
    var_dump($_POST); // Debug form submission

    $required_fields = [
        'OutletID' => 'Outlet ID',
        'Location' => 'Location',
        'City' => 'City',
        'Cloth_Category' => 'Cloth Category', // Key matches form field name
        'Quantity_Of_Cloth' => 'Quantity Of Cloth',
        'WareHouse_ID' => 'Warehouse ID',
        'Phone_Number' => 'Phone Number'
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
        'OutletID' => $_POST['OutletID'],
        'Location' => $_POST['Location'],
        'City' => $_POST['City'],
        'Cloth_Category' => $_POST['Cloth_Category'], // Matches form field name
        'Quantity_Of_Cloth' => $_POST['Quantity_Of_Cloth'],
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'Phone_Number' => $_POST['Phone_Number']
    ];

    // Debug: Check parameters being passed to PDO
    var_dump($data);

    $sql = "INSERT INTO outlet (OutletID, Location, City, `Cloth Category`, Quantity_Of_Cloth, WareHouse_ID, Phone_Number) 
            VALUES (:OutletID, :Location, :City, :Cloth_Category, :Quantity_Of_Cloth, :WareHouse_ID, :Phone_Number)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Update an outlet
if (isset($_POST['update'])) {
    $required_fields = [
        'OutletID' => 'Outlet ID',
        'Location' => 'Location',
        'City' => 'City',
        'Cloth_Category' => 'Cloth Category',
        'Quantity_Of_Cloth' => 'Quantity Of Cloth',
        'WareHouse_ID' => 'Warehouse ID',
        'Phone_Number' => 'Phone Number'
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
        'OutletID' => $_POST['OutletID'],
        'Location' => $_POST['Location'],
        'City' => $_POST['City'],
        'Cloth_Category' => $_POST['Cloth_Category'],
        'Quantity_Of_Cloth' => $_POST['Quantity_Of_Cloth'],
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'Phone_Number' => $_POST['Phone_Number']
    ];

    // Debug: Check parameters being passed to PDO
    var_dump($data);

    $sql = "UPDATE outlet SET Location = :Location, City = :City, `Cloth Category` = :Cloth_Category, 
            Quantity_Of_Cloth = :Quantity_Of_Cloth, WareHouse_ID = :WareHouse_ID, Phone_Number = :Phone_Number 
            WHERE OutletID = :OutletID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete an outlet
if (isset($_GET['delete'])) {
    $outlet_id = $_GET['delete'];
    $sql = "DELETE FROM outlet WHERE OutletID = :OutletID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute(['OutletID' => $outlet_id]);
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
    <title>Outlet</title>

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
                <a href="delivery.php">
                    <i class='bx bx-package'></i>
                    <span class="text">Delivery</span>
                </a>
            </li>
            <li>
                <a href="#">
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
                    <span class="text">Categary Wear</span>
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
                    <h1>Outlet</h1>
                </div>
            </div>

            <div class="main-content">
                <div class="card">
                    <button class="btn-add" onclick="openForm()"><i class="fas fa-plus"></i> Add</button>
                    
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Outlet ID</th>
                                    <th>Location</th>
                                    <th>City</th>
                                    <th>Cloth Category</th>
                                    <th>Quantity Of Cloth</th>
                                    <th>WareHouse ID</th>
                                    <th>Phone Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="warehouse-body">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM outlet");
                                $stmt->execute();
                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (empty($results)) {
                                    echo "<tr><td colspan='8'>No outlets found.</td></tr>";
                                } else {
                                    foreach ($results as $row) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['OutletID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Location']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['City']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Cloth Category']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Quantity_of_Cloth']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['WareHouse_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Phone_Number']) . "</td>";
                                        echo "<td>";
                                        echo "<button class='btn btn-edit' onclick=\"editRow('" . htmlspecialchars($row['OutletID']) . "')\"><i class='fas fa-pen'></i></button>";
                                        echo "<a href='?delete=" . urlencode($row['OutletID']) . "' class='btn btn-delete' onclick=\"return confirm('Are you sure?');\"><i class='fas fa-trash'></i></a>";
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

    <div id="modal">
        <div class="modal-content">
            <h3 id="form-title">Add Outlet Entry</h3>
            <span class="close-button" onclick="closeForm()">×</span>
            <form method="post" id="outletForm">
                <input type="text" name="OutletID" id="input-id" placeholder="Outlet ID" required>
                <input type="text" name="Location" id="input-location" placeholder="Location" required>
                <input type="text" name="City" id="input-city" placeholder="City" required>
                <input type="text" name="Cloth_Category" id="input-cloth-category" placeholder="Cloth Category" required> <!-- Fixed name and id -->
                <input type="number" name="Quantity_Of_Cloth" id="input-quantity" placeholder="Quantity Of Cloth" required>
                <input type="text" name="WareHouse_ID" id="input-warehouse-id" placeholder="WareHouse ID" required>
                <input type="number" name="Phone_Number" id="input-phone" placeholder="Phone Number" required>
                <p id="error-message" class="error-message">All fields are required!</p>
                <button type="submit" name="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>" onclick="return validateForm()">Save</button>
                <button type="button" onclick="closeForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    function openForm(outletId = null) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById('form-title').textContent = outletId ? 'Edit Outlet Entry' : 'Add Outlet Entry';

        if (outletId) {
            <?php
            if (isset($_GET['edit'])) {
                $stmt = $conn->prepare("SELECT * FROM outlet WHERE OutletID = :OutletID");
                $stmt->execute(['OutletID' => $_GET['edit']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
            ?>
                    document.getElementById('input-id').value = '<?php echo htmlspecialchars($row['OutletID']); ?>';
                    document.getElementById('input-location').value = '<?php echo htmlspecialchars($row['Location']); ?>';
                    document.getElementById('input-city').value = '<?php echo htmlspecialchars($row['City']); ?>';
                    document.getElementById('input-cloth-category').value = '<?php echo htmlspecialchars($row['Cloth Category']); ?>'; // Matches database column
                    document.getElementById('input-quantity').value = '<?php echo htmlspecialchars($row['Quantity_Of_Cloth']); ?>';
                    document.getElementById('input-warehouse-id').value = '<?php echo htmlspecialchars($row['WareHouse_ID']); ?>';
                    document.getElementById('input-phone').value = '<?php echo htmlspecialchars($row['Phone_Number']); ?>';
                    document.getElementById('input-id').readOnly = true; // Prevent editing the primary key
            <?php
                }
            }
            ?>
        } else {
            document.getElementById('outletForm').reset();
            document.getElementById('input-id').readOnly = false;
        }
    }

    function closeForm() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById('error-message').style.display = 'none';
    }

    function validateForm() {
        const id = document.getElementById('input-id').value;
        const location = document.getElementById('input-location').value;
        const city = document.getElementById('input-city').value;
        const clothCategory = document.getElementById('input-cloth-category').value; // Fixed id
        const quantity = document.getElementById('input-quantity').value;
        const warehouseId = document.getElementById('input-warehouse-id').value;
        const phone = document.getElementById('input-phone').value;
        const errorMessage = document.getElementById('error-message');

        if (!id || !location || !city || !clothCategory || !quantity || !warehouseId || !phone) {
            errorMessage.textContent = 'The following fields are required: ' + 
                (!id ? 'Outlet ID, ' : '') +
                (!location ? 'Location, ' : '') +
                (!city ? 'City, ' : '') +
                (!clothCategory ? 'Cloth Category, ' : '') +
                (!quantity ? 'Quantity Of Cloth, ' : '') +
                (!warehouseId ? 'Warehouse ID, ' : '') +
                (!phone ? 'Phone Number' : '').replace(/, $/, '') + '.';
            errorMessage.style.display = 'block';
            return false;
        }
        return true;
    }

    function editRow(outletId) {
        window.location.href = `?edit=${outletId}`;
    }
    </script>

    <script src="script.js"></script>
</body>
</html> 