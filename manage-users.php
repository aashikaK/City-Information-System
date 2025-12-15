<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

require "db.php";

/* Handle Activate / Deactivate */
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($_GET['action'] == 'deactivate') {
        $stmt = $pdo->prepare("UPDATE users SET status='inactive' WHERE id=?");
        $stmt->execute([$id]);
    }

    if ($_GET['action'] == 'activate') {
        $stmt = $pdo->prepare("UPDATE users SET status='active' WHERE id=?");
        $stmt->execute([$id]);
    }

    header("Location: manage-users.php");
    exit;
}

/* Fetch all users */
$stmt = $pdo->query("SELECT id, username, email, role, status FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - CIS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
body { background:#f4f7fb; font-family:"Segoe UI", Arial, sans-serif; }
.container {
    max-width:1000px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align:center;
    margin-bottom:20px;
    color:#4a90e2;
}
table {
    width:100%;
    border-collapse:collapse;
}
th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
th {
    background:#4a90e2;
    color:white;
}
.status-active {
    color:green;
    font-weight:bold;
}
.status-inactive {
    color:red;
    font-weight:bold;
}
.action-btn {
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:0.9rem;
}
.activate { background:green; }
.deactivate { background:red; }

@media (max-width:600px){
    table, thead, tbody, th, td, tr {
        display:block;
    }
    th {
        display:none;
    }
    td {
        padding:10px;
        border:none;
        position:relative;
    }
    td::before {
        content: attr(data-label);
        font-weight:bold;
        display:block;
    }
}
</style>
</head>

<body>

<?php include "admin-navbar.php"; ?>

<div class="container">
    <h2>Manage Users</h2>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($users as $u) { ?>
            <tr>
                <td data-label="Username"><?php echo htmlspecialchars($u['username']); ?></td>
                <td data-label="Email"><?php echo htmlspecialchars($u['email']); ?></td>
                <td data-label="Role"><?php echo $u['role']; ?></td>
                <td data-label="Status">
                    <span class="<?php echo $u['status']=='active' ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $u['status']; ?>
                    </span>
                </td>
                <td data-label="Action">
                    <?php if ($u['status'] == 'active') { ?>
                        <a class="action-btn deactivate"
                           href="?action=deactivate&id=<?php echo $u['id']; ?>">
                           Deactivate
                        </a>
                    <?php } else { ?>
                        <a class="action-btn activate"
                           href="?action=activate&id=<?php echo $u['id']; ?>">
                           Activate
                        </a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
