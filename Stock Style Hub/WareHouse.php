<?php
// Database connection
$host = "localhost";
$dbname = "stock style hub"; // Enclose database name with spaces in backticks
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Create a new warehouse
if (isset($_POST['create'])) {
    if (empty($_POST['WareHouse_ID']) || empty($_POST['Location']) || empty($_POST['City']) || 
        empty($_POST['Capacity']) || empty($_POST['Quantity_of_Cloth']) || empty($_POST['G mail']) || 
        empty($_POST['Phone Number'])) {
        die("Error: All fields are required.");
    }

    $data = [
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'Location' => $_POST['Location'],
        'City' => $_POST['City'],
        'Capacity' => $_POST['Capacity'],
        'Quantity_of_Cloth' => $_POST['Quantity_of_Cloth'],
        'G mail' => $_POST['G mail'],
        'Phone Number' => $_POST['Phone Number']
    ];

    $sql = "INSERT INTO warehouse (WareHouse_ID, Location, City, Capacity, Quantity_of_Cloth, G mail, Phone Number) 
            VALUES (:WareHouse_ID, :Location, :City, :Capacity, :Quantity_of_Cloth, :G mail, :Phone Number)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Update a warehouse
if (isset($_POST['update'])) {
    if (empty($_POST['WareHouse_ID']) || empty($_POST['Location']) || empty($_POST['City']) || 
        empty($_POST['Capacity']) || empty($_POST['Quantity_of_Cloth']) || empty($_POST['G mail']) || 
        empty($_POST['Phone Number'])) {
        die("Error: All fields are required.");
    }

    $data = [
        'WareHouse_ID' => $_POST['WareHouse_ID'],
        'Location' => $_POST['Location'],
        'City' => $_POST['City'],
        'Capacity' => $_POST['Capacity'],
        'Quantity_of_Cloth' => $_POST['Quantity_of_Cloth'],
        'G mail' => $_POST['G mail'],
        'Phone Number' => $_POST['Phone Number']
    ];

    $sql = "UPDATE warehouse SET Location = :Location, City = :City, Capacity = :Capacity, 
            Quantity_of_Cloth = :Quantity_of_Cloth, G mail = :G mail, Phone Number = :Phone Number 
            WHERE WareHouse_ID = :WareHouse_ID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete a warehouse
if (isset($_GET['delete'])) {
    $warehouse_id = $_GET['delete'];
    $sql = "DELETE FROM warehouse WHERE WareHouse_ID = :WareHouse_ID";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute(['WareHouse_ID' => $warehouse_id]);
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
    <title>WareHouse</title>

    <style>
        #warehouse-management { 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            margin-top: 20px; 
        }
        #warehouse-management h2 { 
            margin-bottom: 20px; 
        }
        #warehouse-management input[type="text"],
        #warehouse-management input[type="email"],
        #warehouse-management input[type="number"] {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px;
            box-sizing: border-box;
        }
        #warehouse-management .table-responsive {
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
        }
        #warehouse-management table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }
        #warehouse-management th, #warehouse-management td {
            padding: 10px;
            border: 1px solid #ddd;
            white-space: nowrap;
            text-align: left;
        }
        #warehouse-management th { 
            background-color: #3C91E6; 
            color: #fff; 
        }
        #addBtn { 
            cursor: pointer; 
            border: none; 
            border-radius: 4px; 
            padding: 10px 20px;
            color: #fff; 
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-factory'></i>
            <span class="text">Stock Style Hub</span>
        </a>
        <ul class="side-menu top">
            <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="Employee.php"><i class='bx bx-child'></i><span class="text">Employee</span></a></li>
            <li><a href="delivery.php"><i class='bx bx-package'></i><span class="text">Delivery</span></a></li>
            <li><a href="outlet.php"><i class='bx bxs-store'></i><span class="text">Outlet</span></a></li>
            <li class="active"><a href="WareHouse.php"><i class='bx bxs-store'></i><span class="text">WareHouse</span></a></li>
            <li><a href="#"><i class='bx bxs-category'></i><span class="text">Category</span></a></li>
            <li><a href="#"><i class='bx bxs-category-alt'></i><span class="text">Categary Wear</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="#" class="Aboutus"><i class='bx bxs-help-circle'></i><span class="text">About Us</span></a></li>
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
            <a href="#" class="profile"><img src="people.png" alt="Profile"></a>
        </nav>
        <main>
            <section id="warehouse-management">
                <h2>WareHouse Information</h2>
                <input type="text" id="searchBox" placeholder="Search warehouse by ID, location, or city...">
                <div class="table-responsive">
                    <table id="warehouseTable">
                        <thead>
                            <tr>
                                <th>WareHouse_ID</th>
                                <th>Location</th>
                                <th>City</th>
                                <th>Capacity</th>
                                <th>Quantity_of_Cloth</th>
                                <th>G_mail</th>
                                <th>Phone_Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="warehouseBody">
                           <?php
                            $stmt = $conn->prepare("SELECT * FROM warehouse");
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($results)) {
                                echo "<tr><td colspan='9'>No data found in the warehouse table.</td></tr>";
                            } else {
                                foreach ($results as $row) {
                                    echo "<tr>";
                                    // For every column, if the key exists, display its HTML-escaped value.
                                    // If the key doesn't exist (highly unlikely for SELECT *), display an empty string.
                                    // This ensures that whatever is in the DB field, it gets rendered.
                                    echo "<td>" . (isset($row['WareHouse_ID']) ? htmlspecialchars($row['WareHouse_ID']) : '') . "</td>";
                                    echo "<td>" . (isset($row['Location']) ? htmlspecialchars($row['Location']) : '') . "</td>";
                                    echo "<td>" . (isset($row['City']) ? htmlspecialchars($row['City']) : '') . "</td>";
                                    echo "<td>" . (isset($row['Capacity']) ? htmlspecialchars($row['Capacity']) : '') . "</td>";
                                    echo "<td>" . (isset($row['Quantity_of_Cloth']) ? htmlspecialchars($row['Quantity_of_Cloth']) : '') . "</td>";
                                    echo "<td>" . (isset($row['G mail']) ? htmlspecialchars($row['G mail']) : '') . "</td>";
                                    echo "<td>" . (isset($row['Phone Number']) ? htmlspecialchars($row['Phone Number']) : '') . "</td>";

                                    echo "<td>";
                                    echo "<button class='editBtn' onclick=\"editRow('" . (isset($row['WareHouse_ID']) ? htmlspecialchars($row['WareHouse_ID']) : '') . "')\"><i class='fas fa-pen'></i> Edit</button>";
                                    echo "<a href='?delete=" . (isset($row['WareHouse_ID']) ? htmlspecialchars($row['WareHouse_ID']) : '') . "' class='deleteBtn' onclick=\"return confirm('Are you sure?');\"><i class='fas fa-trash'></i> Delete</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>Add New Warehouse</h3>
                <div class="add-warehouse-form">
                    <form method="post" id="warehouseForm">
                        <input type="text" name="WareHouse_ID" id="wareWarehouse_ID" placeholder="WareHouse ID" required>
                        <input type="text" name="Location" id="wareLocation" placeholder="Location" required>
                        <input type="text" name="City" id="wareCity" placeholder="City" required>
                        <input type="number" name="Capacity" id="wareCapacity" placeholder="Capacity" step="0.01" required>
                        <input type="number" name="Quantity_Of_Cloth" id="wareQuantity_of_Cloth" placeholder="Quantity_Of_Cloth" required>
                        <input type="email" name="G_mail" id="wareGmail" placeholder="G_mail" required>
                        <input type="text" name="Phone_Number" id="warePhone" placeholder="Phone Number (e.g., 03XX-YYYYYYY)" required>
                        <button type="submit" name="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>" id="addBtn"><?php echo isset($_GET['edit']) ? 'Update Warehouse' : 'Add Warehouse'; ?></button>
                    </form>
                </div>
            </section>
        </main>
    </section>
    <script>
        const warehouseBody = document.getElementById('warehouseBody');
        const wareWarehouse_ID = document.getElementById('wareWarehouse_ID');
        const wareLocation = document.getElementById('wareLocation');
        const wareCity = document.getElementById('wareCity');
        const wareCapacity = document.getElementById('wareCapacity');
        const wareQuantity = document.getElementById('wareQuantity_of_Cloth');
        const wareGmail = document.getElementById('wareGmail');
        const warePhone = document.getElementById('warePhone Number');
        const searchBox = document.getElementById('searchBox');

        function editRow(warehouseId) {
            window.location.href = `?edit=${warehouseId}`;
        }

        <?php if (isset($_GET['edit'])) {
            $stmt = $conn->prepare("SELECT * FROM warehouse WHERE WareHouse_ID = :WareHouse_ID");
            $stmt->execute(['WareHouse_ID' => $_GET['edit']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
        ?>
                wareWarehouse_ID.value = '<?php echo htmlspecialchars($row['WareHouse_ID']); ?>';
                wareLocation.value = '<?php echo htmlspecialchars($row['Location']); ?>';
                wareCity.value = '<?php echo htmlspecialchars($row['City']); ?>';
                wareCapacity.value = '<?php echo htmlspecialchars($row['Capacity']); ?>';
                wareQuantity_Of_Cloth.value = '<?php echo htmlspecialchars($row['Quantity_of_Cloth']); ?>';
                wareGmail.value = '<?php echo htmlspecialchars($row['G mail']); ?>';
                warePhone.value = '<?php echo htmlspecialchars($row['Phone Number']); ?>';
        <?php
            }
        }
        ?>

        searchBox.addEventListener('input', () => {
            const filter = searchBox.value.toLowerCase();
            Array.from(warehouseBody.rows).forEach(r => {
                const idText = r.cells[0].textContent.toLowerCase();
                const locationText = r.cells[1].textContent.toLowerCase();
                const cityText = r.cells[2].textContent.toLowerCase();
                r.style.display = (idText.includes(filter) || locationText.includes(filter) || cityText.includes(filter)) ? '' : 'none';
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>