<?php
session_start();
include 'classes/dbh.php';
include 'html/ban_check.php';

$search = isset($_POST['search-query']) ? $_POST['search-query'] : '';

$stmt = $dbh->prepare("SELECT * FROM users WHERE users_uid LIKE :tit");
$stmt->execute(['tit' => '%' . $search . '%']);
$users = $stmt->fetchAll();
$count = $stmt->rowCount();

$stmt = $dbh->query("SELECT * FROM users ORDER BY users_id DESC LIMIT 50");
$recentUsers = $stmt->fetchAll();

$roleColors = [
  1 => '#7CE181', // Admin
  4 => '#4AFFF7', // Mod
  5 => '#FFFA56', // Manager
  6 => '#007bff', // Council
  7 => '#444871', // E-Gang
  8 => '#FF003E', // Hacker
  2 => 'yellow'   // Wealthy
];

function getUserLink($user, $roleColors) {
    $role = intval($user['users_rank']);
    $color = isset($roleColors[$role]) ? $roleColors[$role] : '#FFFFFF';
    $username = htmlspecialchars(strip_tags($user['users_uid']));
    $hexCode = htmlspecialchars(strip_tags($user['hex_code']));
    $profileLink = '/profile.php?uid=' . strip_tags($user['users_uid']);
    $roleName = getRoleName($role);
    return '<a class="paste-link" style="color: ' . ($hexCode ? $hexCode : $color) . '; font-weight:bold;" href="' . $profileLink . '">[' . $roleName . '] ' . $username . '</a>';
}

function getRoleName($roleId) {
    switch ($roleId) {
        case 1:
            return 'Admin';
        case 3:
            return 'Dev';
        case 4:
            return 'Mod';
        case 5:
            return 'Manager';
        case 6:
            return 'Council';
        case 2:
            return 'Wealthy';
        case 7:
            return 'E-Gang';
        case 8:
            return 'Hacker';
        default:
            return 'User';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SkidBin - Users</title>
    <?php include 'html/head.html' ?>
</head>
<body>
<?php
$token = md5(rand(0, 12));
$_SESSION['token'] = $token;
include 'html/header.php';
?>
<center><img style="margin-bottom:12px" src="/uploads/pfp/skidbin.png" width="346px" height="84px"></center>
<div class="div-center">
    <form id="search-form" method="POST" action="">
        <input name="search-query" type="text" placeholder="Search for.." value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="_token" value="<?php echo $token; ?>">
        <input type="submit" value="Search">
    </form>
</div>
<div class="container">
    <?php if (!$_POST || !$search): ?>
        <div style="text-align: center;">
            <p>Showing 50 (of <?php echo intval($count); ?> total) users.</p>
        </div>
        <?php foreach ($roleColors as $roleId => $color): ?>
            <?php
            $roleUsers = array_filter($users, function($user) use ($roleId) {
                return intval($user['users_rank']) === $roleId;
            });

            if (count($roleUsers) === 0) {
                continue; // Skip rendering the table if no users for this role
            }
            ?>
            <p style="font-size: 20px"><?php echo getRoleName($roleId); ?></p>
            <table class="table table-hover">
                <thead class="tb-highlight">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Joined</th>
                    <th>Paste Count</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($roleUsers as $user): ?>
                    <tr class="<?php echo strtolower(getRoleName($roleId)); ?>-post">
                        <td><?php echo htmlspecialchars(strip_tags($user['users_id'])); ?></td>
                        <td><?php echo getUserLink($user, $roleColors); ?></td>
                        <td><?php echo htmlspecialchars(strip_tags($user['joined'])); ?></td>
                        <td><?php echo htmlspecialchars(strip_tags($user['pastes'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <table class="table table-hover">
            <thead class="tb-highlight">
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Joined</th>
                <th>Paste Count</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars(strip_tags($user['users_id'])); ?></td>
                    <td><?php echo getUserLink($user, $roleColors); ?></td>
                    <td><?php echo htmlspecialchars(strip_tags($user['joined'])); ?></td>
                    <td><?php echo htmlspecialchars(strip_tags($user['pastes'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>

    <?php if (!$_POST || !$search): ?>
    <div class="container">
    <p style="font-size: 20px">Recently joined members</p>
    <table class="table table-hover">
        <thead class="tb-highlight">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Joined</th>
            <th>Paste Count</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($recentUsers as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars(strip_tags($user['users_id'])); ?></td>
                <td><?php echo getUserLink($user, $roleColors); ?></td>
                <td><?php echo htmlspecialchars(strip_tags($user['joined'])); ?></td>
                <td><?php echo htmlspecialchars(strip_tags($user['pastes'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
