<?php
// Database connection
$host = "localhost";
$dbname = "stock style hub"; // Corrected to use underscore
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Create a new employee
if (isset($_POST['create'])) {
    // For insertion, exclude Emp_ID since it's auto-incremented
    if (empty($_POST['First_Name']) || empty($_POST['Last_Name']) || empty($_POST['BirthDate']) || empty($_POST['Position']) || empty($_POST['Salary']) || empty($_POST['HireDate']) || empty($_POST['OutletID']) || empty($_POST['ContactNum']) || empty($_POST['Role']) || empty($_POST['WareHouse_ID'])) {
        die("Error: All fields are required.");
    }

    $data = [
        'First_Name' => $_POST['First_Name'],
        'Last_Name' => $_POST['Last_Name'],
        'BirthDate' => $_POST['BirthDate'],
        'Position' => $_POST['Position'],
        'Salary' => $_POST['Salary'],
        'HireDate' => $_POST['HireDate'],
        'OutletID' => $_POST['OutletID'],
        'ContactNum' => $_POST['ContactNum'],
        'Role' => $_POST['Role'],
        'WareHouse_ID' => $_POST['WareHouse_ID']
    ];

    $sql = "INSERT INTO employee (First_Name, Last_Name, BirthDate, Position, Salary, HireDate, OutletID, ContactNum, Role, WareHouse_ID) VALUES (:First_Name, :Last_Name, :BirthDate, :Position, :Salary, :HireDate, :OutletID, :ContactNum, :Role, :WareHouse_ID)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Update an employee
if (isset($_POST['update'])) {
    if (empty($_POST['Emp_ID']) || empty($_POST['First_Name']) || empty($_POST['Last_Name']) || empty($_POST['BirthDate']) || empty($_POST['Position']) || empty($_POST['Salary']) || empty($_POST['HireDate']) || empty($_POST['OutletID']) || empty($_POST['ContactNum']) || empty($_POST['Role']) || empty($_POST['WareHouse_ID'])) {
        die("Error: All fields are required.");
    }

    $data = [
        'Emp_ID' => $_POST['Emp_ID'],
        'First_Name' => $_POST['First_Name'],
        'Last_Name' => $_POST['Last_Name'],
        'BirthDate' => $_POST['BirthDate'],
        'Position' => $_POST['Position'],
        'Salary' => $_POST['Salary'],
        'HireDate' => $_POST['HireDate'],
        'OutletID' => $_POST['OutletID'],
        'ContactNum' => $_POST['ContactNum'],
        'Role' => $_POST['Role'],
        'WareHouse_ID' => $_POST['WareHouse_ID']
    ];

    $sql = "UPDATE employee SET First_Name=:First_Name, Last_Name=:Last_Name, BirthDate=:BirthDate, Position=:Position, Salary=:Salary, HireDate=:HireDate, OutletID=:OutletID, ContactNum=:ContactNum, Role=:Role, WareHouse_ID=:WareHouse_ID WHERE Emp_ID=:Emp_ID";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete an employee
if (isset($_GET['delete'])) {
    $employee_id = $_GET['delete'];
    $sql = "DELETE FROM employee WHERE Emp_ID=:Emp_ID";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['Emp_ID' => $employee_id]);
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
    <title>Employee</title>

    <style>
        #employee-management { 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            margin-top: 20px; 
        }
        #employee-management h2 { 
            margin-bottom: 20px; 
        }
        #employee-management input[type="text"],
        #employee-management input[type="email"],
        #employee-management input[type="date"],
        #employee-management input[type="number"] {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px;
            box-sizing: border-box;
        }
        #employee-management .table-responsive {
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
        }
        #employee-management table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }
        #employee-management th, #employee-management td {
            padding: 10px;
            border: 1px solid #ddd;
            white-space: nowrap;
            text-align: left;
        }
        #employee-management th { 
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
            <li class="active"><a href="Employee.php"><i class='bx bx-child'></i><span class="text">Employee</span></a></li>
            <li><a href="delivery.php"><i class='bx bx-package'></i><span class="text">Delivery</span></a></li>
            <li><a href="outlet.php"><i class='bx bxs-store'></i><span class="text">Outlet</span></a></li>
            <li><a href="WareHouse.php"><i class='bx bxs-store'></i><span class="text">WareHouse</span></a></li>
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
            <section id="employee-management">
                <h2>Employee Information</h2>
                <input type="text" id="searchBox" placeholder="Search employee by name, position or email...">
                <div class="table-responsive">
                    <table id="employeeTable">
                        <thead>
                            <tr>
                                <th>Employee_ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Birth Date</th>
                                <th>Position</th>
                                <th>Salary</th>
                                <th>Hire Date</th>
                                <th>Outlet ID</th>
                                <th>Phone Number</th>
                                <th>Role</th>
                                <th>Warehouse_id</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeeBody">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM employee");
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($results as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['Emp_ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['First_Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Last_Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['BirthDate']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Position']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Salary']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['HireDate']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['OutletID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ContactNum']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Role']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['WareHouse_ID']) . "</td>";
                                echo "<td>";
                                echo "<button class='editBtn' onclick=\"editRow('" . htmlspecialchars($row['Emp_ID']) . "')\"><i class='fas fa-pen'></i> Edit</button>";
                                echo "<a href='?delete=" . htmlspecialchars($row['Emp_ID']) . "' class='deleteBtn' onclick=\"return confirm('Are you sure?');\"><i class='fas fa-trash'></i> Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>Add New Employee</h3>
                <div class="add-employee-form">
                    <form method="post" id="employeeForm">
                        <!-- Emp_ID is hidden for updates, not needed for create -->
                        <input type="number" name="Emp_ID" id="empEmp_ID" placeholder="employee_id" required>
                        <input type="text" name="First_Name" id="empFirstName" placeholder="First Name" required>
                        <input type="text" name="Last_Name" id="empLastName" placeholder="Last Name" required>
                        <input type="date" name="BirthDate" id="empBirthDate" required>
                        <input type="text" name="Position" id="empPosition" placeholder="Position" required>
                        <input type="number" name="Salary" id="empSalary" placeholder="Salary" step="0.01" required>
                        <input type="date" name="HireDate" id="empHireDate" required>
                        <input type="text" name="OutletID" id="empOutletId" placeholder="Outlet ID" required>
                        <input type="text" name="ContactNum" id="empPhone" placeholder="Phone Number (e.g., 03XX-YYYYYYY)" required>
                        <input type="text" name="Role" id="empRole" placeholder="Role" required>
                        <input type="text" name="WareHouse_ID" id="empWarehouseId" placeholder="Warehouse ID" required>
                        <button type="submit" name="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>" id="addBtn"><?php echo isset($_GET['edit']) ? 'Update Employee' : 'Add Employee'; ?></button>
                    </form>
                </div>
            </section>
        </main>
    </section>
    <script>
        const employeeBody = document.getElementById('employeeBody');
        const empEmp_ID = document.getElementById('empEmp_ID');
        const empFirstName = document.getElementById('empFirstName');
        const empLastName = document.getElementById('empLastName');
        const empBirthDate = document.getElementById('empBirthDate');
        const empPosition = document.getElementById('empPosition');
        const empSalary = document.getElementById('empSalary');
        const empHireDate = document.getElementById('empHireDate');
        const empOutletId = document.getElementById('empOutletId');
        const empPhone = document.getElementById('empPhone');
        const empRole = document.getElementById('empRole');
        const empWarehouseId = document.getElementById('empWarehouseId');
        const searchBox = document.getElementById('searchBox');

        function editRow(employeeId) {
            // Redirect to the same page with edit parameter
            window.location.href = `?edit=${employeeId}`;
        }

        <?php if (isset($_GET['edit'])) {
            $stmt = $conn->prepare("SELECT * FROM employee WHERE Emp_ID = :Emp_ID");
            $stmt->execute(['Emp_ID' => $_GET['edit']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
        ?>
                empEmp_ID.value = '<?php echo htmlspecialchars($row['Emp_ID']); ?>';
                empFirstName.value = '<?php echo htmlspecialchars($row['First_Name']); ?>';
                empLastName.value = '<?php echo htmlspecialchars($row['Last_Name']); ?>';
                empBirthDate.value = '<?php echo htmlspecialchars($row['BirthDate']); ?>';
                empPosition.value = '<?php echo htmlspecialchars($row['Position']); ?>';
                empSalary.value = '<?php echo htmlspecialchars($row['Salary']); ?>';
                empHireDate.value = '<?php echo htmlspecialchars($row['HireDate']); ?>';
                empOutletId.value = '<?php echo htmlspecialchars($row['OutletID']); ?>';
                empPhone.value = '<?php echo htmlspecialchars($row['ContactNum']); ?>';
                empRole.value = '<?php echo htmlspecialchars($row['Role']); ?>';
                empWarehouseId.value = '<?php echo htmlspecialchars($row['WareHouse_ID']); ?>';
        <?php
            }
        }
        ?>

        searchBox.addEventListener('input', () => {
            const filter = searchBox.value.toLowerCase();
            Array.from(employeeBody.rows).forEach(r => {
                const firstNameText = r.cells[1].textContent.toLowerCase();
                const lastNameText = r.cells[2].textContent.toLowerCase();
                const positionText = r.cells[4].textContent.toLowerCase();
                r.style.display = (firstNameText.includes(filter) || lastNameText.includes(filter) || positionText.includes(filter)) ? '' : 'none';
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>