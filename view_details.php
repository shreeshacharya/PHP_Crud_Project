<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-forms {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Details</h2>

        <form action="view_details.php" method="get">
            Search: <input type="text" name="query" value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <input type="submit" value="Search">
            Sort by:
            <select name="sort">
                <option value="name" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="usn" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'usn' ? 'selected' : ''; ?>>USN</option>
                <option value="phone" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'phone' ? 'selected' : ''; ?>>Phone Number</option>
            </select>
            <input type="submit" value="Sort">
        </form>

        <!-- Display Records -->
        <table border="1">
            <tr>
                <th>Name</th>
                <th>USN</th>
                <th>Phone Number</th>
                <th>Delete Record</th>
                <th>Update Record</th>
            </tr>

            <?php
            $conn = new mysqli('localhost', 'root', '', 'wshop');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $search_query = "";
            if (isset($_GET['query'])) {
                $search_query = $_GET['query'];
            }

            $sort_by = "name";
            if (isset($_GET['sort'])) {
                $sort_by = $_GET['sort'];
            }

            $sql = "SELECT * FROM students WHERE name LIKE '%$search_query%' OR usn LIKE '%$search_query%' OR phone LIKE '%$search_query%' ORDER BY $sort_by";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["usn"] . "</td>
                            <td>" . $row["phone"] . "</td>
                            <td>
                                <form action='delete.php' method='post' class='action-forms'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <input type='submit' value='Delete'>
                                </form>
                            </td>
                            <td>
                                <form action='update.php' method='post' class='action-forms'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>