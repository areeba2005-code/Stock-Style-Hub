<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--BOXICON LINK-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--CSS LINK-->
    <link rel="stylesheet" href="style.css">
    <title>Stock Style Hub</title>
</head>
<body>
    <!--SIDE BAR-->
    <section id="sidebar">
        <a href="" class="brand">
            <i class='bx bxs-factory'></i>
            <span class="text">Stock Style Hub</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
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
    <!--SIDEBAR-->

    <!--CONTENT-->
    <section id="content">
        <!--Navigation Bar-->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="search.php" method="GET">
                <div class="form-input">
                    <input type="search" name="search_query" placeholder="Search by Company...">
                    <button type="submit" class="search-btn"><i class='bx bx-search-alt-2'></i></button>
                </div>
            </form>
            <a href="#" class="profile">
                <img src="people.png">
            </a>
        </nav>
        <!--Navigation Bar-->

        <!--MAIN-->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                </div>
            </div>
            <ul class="box-info">
                <li>
                    <i class='bx bx-male-female'></i>
                    <a href="Employee.php"><span class="text">
                        <h3>30</h3>
                        <p>Employee</p>
                    </span></a>
                </li>
                <li>
                    <i class='bx bx-store-alt'></i>
                    <a href="outlet.php"><span class="text">
                        <h3>10</h3>
                        <p>Outlets</p>
                    </span>
                    </a>
                </li>
                <li>
                    <i class='bx bxs-category-alt'></i>
                    <span class="text">
                        <h3>20</h3>
                        <p>Category</p>
                    </span>
                </li>
            </ul>
            <div class="table-data">
                <div class="Delivery-Info">
                    <div class="head">
                        <h3>Delivery Information</h3>
                        <i class='bx bx-filter'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Delivery_ID</th>
                                <th>WareHouse_ID</th>
                                <th>OutletID</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include PHP logic to display search results or default data
                            if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
                                // Database connection
                                $conn = new mysqli("localhost", "root", "", "warehouse_db");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $search_query = $conn->real_escape_string($_GET['search_query']);
                                $sql = "SELECT * FROM delivery_info WHERE company LIKE '%$search_query%'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td><p>" . htmlspecialchars($row['company']) . "</p></td>";
                                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                                        echo "<td><span class='status " . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No results found for '$search_query'</td></tr>";
                                }

                                $conn->close();
                            } else {
                                // Default static data (as a fallback)
                                ?>
                                <tr>
                                    <td><p>1001</p></td>
                                    <td>WARE K101</td>
                                    <td>OUT101</td>
                                </tr>
                                <tr>
                                    <td><p>1002</p></td>
                                    <td>Ware K101</td>
                                    <td>OUT112</td>
                                   
                                </tr>
                                
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="Category-Wear">
                    <div class="head">
                        <h3>Category Wear</h3>
                        <i class='bx bx-filter'></i>
                    </div>
                    <ul class="wear-list">
                        <li class="completed">
                            <p>MEN</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="completed">
                            <p>WOMEN</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="not-completed">
                            <p>KID</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
        <!--MAIN-->
    </section>
    <!--CONTENT-->
    <script src="script.js"></script>
</body>
</html>