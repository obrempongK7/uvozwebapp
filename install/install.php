<?php

session_start();

// Security Guard: Installation Lock
if (file_exists('../.env')) {
    die('<div style="text-align:center;font-family:sans-serif;margin-top:50px;"><h2>Installation Locked</h2><p>Application is already installed. To reinstall, delete the <b>.env</b> file.</p></div>');
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

function checkRequirements() {
    $requirements = [
        'php' => version_compare(PHP_VERSION, '8.1.0', '>='),
        'pdo_mysql' => extension_loaded('pdo_mysql') || extension_loaded('pdo'),
        'openssl' => extension_loaded('openssl'),
        'mbstring' => extension_loaded('mbstring'),
        'curl' => extension_loaded('curl'),
        'gd' => extension_loaded('gd'),
        'fileinfo' => extension_loaded('fileinfo'),
    ];
    return $requirements;
}

function checkPermissions() {
    $paths = [
        '../storage/app' => is_writable('../storage/app'),
        '../storage/framework' => is_writable('../storage/framework'),
        '../storage/logs' => is_writable('../storage/logs'),
        '../bootstrap/cache' => is_writable('../bootstrap/cache'),
        '../' => is_writable('../'),
    ];
    return $paths;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 3) {
    $dbHost = $_POST['db_host'] ?? '127.0.0.1';
    $dbPort = $_POST['db_port'] ?? '3306';
    $dbName = $_POST['db_name'] ?? '';
    $dbUser = $_POST['db_user'] ?? '';
    $dbPass = $_POST['db_pass'] ?? '';

    // Validate database name against SQL Injection
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName)) {
        $error = "Invalid database name. Only alphanumeric characters and underscores are allowed.";
    } else {
        try {
            $pdo = new PDO("mysql:host=$dbHost;port=$dbPort", $dbUser, $dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Safe execution because dbName is validated via strict regex
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
            $pdo->exec("USE `$dbName`");

            $_SESSION['db_config'] = [
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass
            ];

            header('Location: ?step=4');
            die();
        } catch (PDOException $e) {
            $error = "Database Connection Failed: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 4) {
    $adminEmail = filter_var($_POST['admin_email'] ?? 'admin@uvoz.app', FILTER_SANITIZE_EMAIL);
    $adminPass = $_POST['admin_password'] ?? 'Admin@123456';
    $appUrl = filter_var($_POST['app_url'] ?? 'http://localhost', FILTER_SANITIZE_URL);

    try {
        if (!isset($_SESSION['db_config'])) {
            throw new Exception("Database configuration missing. Please start over.");
        }

        $db = $_SESSION['db_config'];
        $pdo = new PDO("mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}", $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Import schema
        if (file_exists('schema.sql')) {
            $sql = file_get_contents('schema.sql');
            if (!empty(trim($sql))) {
                // Execute schema statements securely
                $pdo->exec($sql);
            }
        }

        // Insert Admin User
        $hashedPassword = password_hash($adminPass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, display_name, email_verified_at, role, is_active) VALUES (?, ?, ?, ?, NOW(), 'super_admin', 1)");
        $stmt->execute(['admin', $adminEmail, $hashedPassword, 'Administrator']);

        $adminId = $pdo->lastInsertId();

        // Insert admin profile record
        $stmtProfile = $pdo->prepare("INSERT INTO profiles (user_id) VALUES (?)");
        $stmtProfile->execute([$adminId]);


        // Write .env safely
        if (file_exists('../.env.example')) {
            $envContent = file_get_contents('../.env.example');

            // Function to escape values securely in .env
            $escapeEnv = function($value) {
                if (preg_match('/[\s#]/', $value)) {
                    return '"' . str_replace('"', '\"', $value) . '"';
                }
                return $value;
            };

            $envContent = preg_replace('/APP_URL=.*/', 'APP_URL=' . $escapeEnv($appUrl), $envContent);
            $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $escapeEnv($db['host']), $envContent);
            $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $escapeEnv($db['port']), $envContent);
            $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $escapeEnv($db['database']), $envContent);
            $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $escapeEnv($db['username']), $envContent);
            $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $escapeEnv($db['password']), $envContent);

            $appKey = 'base64:' . base64_encode(random_bytes(32));
            $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $appKey, $envContent);

            file_put_contents('../.env', $envContent);
        }

        $_SESSION['admin_email'] = $adminEmail;
        $_SESSION['admin_password'] = $adminPass;

        header('Location: ?step=5');
        die();
    } catch (Exception $e) {
        $error = "Installation Failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UVOZ Installation</title>
    <style>
        body { font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; color: #1f2937; line-height: 1.5; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #4f46e5; margin-bottom: 2rem; }
        .step { display: flex; justify-content: space-between; margin-bottom: 2rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; }
        .btn { display: inline-block; background: #4f46e5; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; font-size: 1rem; }
        .btn:hover { background: #4338ca; }
        .btn-disabled { background: #9ca3af; cursor: not-allowed; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        .success { color: #10b981; font-weight: bold; }
        .fail { color: #ef4444; font-weight: bold; }
        .alert { padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 4px; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="email"], input[type="url"] { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box; }
    </style>
</head>
<body>
    <div class="container">
        <h1>UVOZ Installation</h1>

        <?php if ($error): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($step === 1): ?>
            <div class="step">
                <h2>Step 1: Server Requirements</h2>
            </div>
            <table>
                <?php
                $reqs = checkRequirements();
                $allPassed = true;
                foreach ($reqs as $name => $passed):
                    if (!$passed) $allPassed = false;
                ?>
                <tr>
                    <td><?php echo strtoupper($name); ?></td>
                    <td><?php echo $passed ? '<span class="success">Passed</span>' : '<span class="fail">Failed</span>'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php if ($allPassed): ?>
                <a href="?step=2" class="btn">Next Step</a>
            <?php else: ?>
                <button class="btn btn-disabled" disabled>Please fix errors</button>
            <?php endif; ?>

        <?php elseif ($step === 2): ?>
            <div class="step">
                <h2>Step 2: Folder Permissions</h2>
            </div>
            <table>
                <?php
                $perms = checkPermissions();
                $allPassed = true;
                foreach ($perms as $path => $passed):
                    if (!$passed) $allPassed = false;
                ?>
                <tr>
                    <td><?php echo $path; ?></td>
                    <td><?php echo $passed ? '<span class="success">Writable</span>' : '<span class="fail">Not Writable</span>'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php if ($allPassed): ?>
                <a href="?step=3" class="btn">Next Step</a>
            <?php else: ?>
                <button class="btn btn-disabled" disabled>Please fix permissions (chmod 775)</button>
            <?php endif; ?>

        <?php elseif ($step === 3): ?>
            <div class="step">
                <h2>Step 3: Database Setup</h2>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Database Host</label>
                    <input type="text" name="db_host" value="127.0.0.1" required>
                </div>
                <div class="form-group">
                    <label>Database Port</label>
                    <input type="text" name="db_port" value="3306" required>
                </div>
                <div class="form-group">
                    <label>Database Name</label>
                    <input type="text" name="db_name" placeholder="uvoz_db" required>
                </div>
                <div class="form-group">
                    <label>Database Username</label>
                    <input type="text" name="db_user" required>
                </div>
                <div class="form-group">
                    <label>Database Password</label>
                    <input type="password" name="db_pass">
                </div>
                <button type="submit" class="btn">Connect & Continue</button>
            </form>

        <?php elseif ($step === 4): ?>
            <div class="step">
                <h2>Step 4: Application Setup</h2>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Application URL</label>
                    <input type="url" name="app_url" value="<?php echo htmlspecialchars('http://'.$_SERVER['HTTP_HOST']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" name="admin_email" value="admin@uvoz.app" required>
                </div>
                <div class="form-group">
                    <label>Admin Password</label>
                    <input type="text" name="admin_password" value="Admin@123456" required>
                </div>
                <button type="submit" class="btn">Install Application</button>
            </form>

        <?php elseif ($step === 5): ?>
            <div class="step">
                <h2>Step 5: Finish</h2>
            </div>
            <div style="text-align: center;">
                <h3 class="success">Installation Complete!</h3>
                <p>Please log in with the following credentials:</p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['admin_email']); ?></p>
                <p><strong>Password:</strong> <?php echo htmlspecialchars($_SESSION['admin_password']); ?></p>
                <br>
                <p class="fail">IMPORTANT: Delete the install/install.php file now for security!</p>
                <br>
                <a href="../public/" class="btn">Go to Application</a>
            </div>
            <?php session_destroy(); ?>
        <?php endif; ?>
    </div>
</body>
</html>
