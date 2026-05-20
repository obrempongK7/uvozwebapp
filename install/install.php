<?php
/**
 * UVOZ Web Application Installer
 * Easy installation wizard for cPanel/shared hosting
 * Version 1.0
 */

define('INSTALL_VERSION', '1.0.0');
define('MIN_PHP', '8.1.0');

session_start();

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];
$success = [];

// ─── Helper functions ─────────────────────────────────────────────────────────

function checkRequirement($name, $check, $required = true) {
    return ['name' => $name, 'status' => $check, 'required' => $required];
}

function runSQL($pdo, $sql) {
    try {
        $pdo->exec($sql);
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function generateKey() {
    return 'base64:' . base64_encode(random_bytes(32));
}

function writeEnv($config) {
    $env = "APP_NAME=UVOZ\n";
    $env .= "APP_ENV=production\n";
    $env .= "APP_KEY={$config['app_key']}\n";
    $env .= "APP_DEBUG=false\n";
    $env .= "APP_URL={$config['app_url']}\n\n";
    $env .= "DB_CONNECTION=mysql\n";
    $env .= "DB_HOST={$config['db_host']}\n";
    $env .= "DB_PORT={$config['db_port']}\n";
    $env .= "DB_DATABASE={$config['db_name']}\n";
    $env .= "DB_USERNAME={$config['db_user']}\n";
    $env .= "DB_PASSWORD={$config['db_pass']}\n\n";
    $env .= "SESSION_DRIVER=file\n";
    $env .= "QUEUE_CONNECTION=sync\n";
    $env .= "CACHE_STORE=file\n\n";
    $env .= "FILESYSTEM_DISK=local\n\n";
    $env .= "MAIL_MAILER=smtp\n";
    $env .= "MAIL_HOST={$config['mail_host']}\n";
    $env .= "MAIL_PORT={$config['mail_port']}\n";
    $env .= "MAIL_USERNAME={$config['mail_user']}\n";
    $env .= "MAIL_PASSWORD={$config['mail_pass']}\n";
    $env .= "MAIL_FROM_ADDRESS={$config['mail_from']}\n";
    $env .= "MAIL_FROM_NAME=UVOZ\n\n";
    $env .= "STRIPE_KEY={$config['stripe_key']}\n";
    $env .= "STRIPE_SECRET={$config['stripe_secret']}\n\n";
    $env .= "PAYSTACK_PUBLIC_KEY={$config['paystack_public']}\n";
    $env .= "PAYSTACK_SECRET_KEY={$config['paystack_secret']}\n\n";
    $env .= "LOG_CHANNEL=stack\n";
    $env .= "LOG_LEVEL=error\n";
    
    return file_put_contents(__DIR__ . '/../.env', $env) !== false;
}

// ─── Requirements check ──────────────────────────────────────────────────────
$requirements = [
    checkRequirement('PHP ' . MIN_PHP . '+', version_compare(PHP_VERSION, MIN_PHP, '>='), true),
    checkRequirement('PDO MySQL', extension_loaded('pdo_mysql'), true),
    checkRequirement('OpenSSL', extension_loaded('openssl'), true),
    checkRequirement('Mbstring', extension_loaded('mbstring'), true),
    checkRequirement('cURL', extension_loaded('curl'), true),
    checkRequirement('Fileinfo', extension_loaded('fileinfo'), true),
    checkRequirement('JSON', extension_loaded('json'), true),
    checkRequirement('GD Library', extension_loaded('gd'), false),
    checkRequirement('BCMath', extension_loaded('bcmath'), false),
    checkRequirement('storage/ writable', is_writable(__DIR__ . '/../storage'), true),
    checkRequirement('bootstrap/cache/ writable', is_writable(__DIR__ . '/../bootstrap/cache'), true),
];

$allRequired = !array_filter($requirements, fn($r) => $r['required'] && !$r['status']);

// ─── Process POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        // Test DB connection
        $host = trim($_POST['db_host'] ?? 'localhost');
        $port = trim($_POST['db_port'] ?? '3306');
        $name = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = $_POST['db_pass'] ?? '';

        if (!$name || !$user) {
            $errors[] = 'Database name and username are required.';
        } else {
            try {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                // Create database if not exists
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$name}`");
                $_SESSION['db'] = compact('host','port','name','user','pass');
                header('Location: ?step=3');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Database connection failed: ' . $e->getMessage();
            }
        }
    }

    if ($step === 3) {
        // Save all config
        $appUrl = rtrim(trim($_POST['app_url'] ?? ''), '/');
        if (!$appUrl) $appUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $config = array_merge($_SESSION['db'] ?? [], [
            'app_url'       => $appUrl,
            'app_key'       => generateKey(),
            'mail_host'     => trim($_POST['mail_host'] ?? 'smtp.mailgun.org'),
            'mail_port'     => trim($_POST['mail_port'] ?? '587'),
            'mail_user'     => trim($_POST['mail_user'] ?? ''),
            'mail_pass'     => $_POST['mail_pass'] ?? '',
            'mail_from'     => trim($_POST['mail_from'] ?? 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'example.com')),
            'stripe_key'    => trim($_POST['stripe_key'] ?? ''),
            'stripe_secret' => trim($_POST['stripe_secret'] ?? ''),
            'paystack_public'=> trim($_POST['paystack_public'] ?? ''),
            'paystack_secret'=> trim($_POST['paystack_secret'] ?? ''),
        ]);

        if (!writeEnv($config)) {
            $errors[] = 'Could not write .env file. Please check file permissions.';
        } else {
            $_SESSION['config'] = $config;
            header('Location: ?step=4');
            exit;
        }
    }

    if ($step === 4) {
        // Run migrations
        $db = $_SESSION['db'] ?? [];
        try {
            $pdo = new PDO(
                "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4",
                $db['user'], $db['pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Run the full schema
            $schema = file_get_contents(__DIR__ . '/schema.sql');
            if ($schema) {
                $statements = array_filter(array_map('trim', explode(';', $schema)));
                foreach ($statements as $stmt) {
                    if ($stmt) runSQL($pdo, $stmt);
                }
            }

            // Seed admin user
            $adminUser = trim($_POST['admin_email'] ?? 'admin@uvoz.app');
            $adminPass = password_hash(trim($_POST['admin_pass'] ?? 'Admin@123456'), PASSWORD_BCRYPT, ['cost' => 12]);
            $adminName = trim($_POST['admin_name'] ?? 'UVOZ Admin');
            $refCode   = strtoupper(bin2hex(random_bytes(4)));

            $pdo->exec("INSERT IGNORE INTO users (username,email,password,display_name,role,is_verified,is_active,email_verified_at,referral_code,created_at,updated_at)
                VALUES ('admin','{$adminUser}','{$adminPass}','{$adminName}','super_admin',1,1,NOW(),'{$refCode}',NOW(),NOW())");

            $adminId = $pdo->lastInsertId() ?: 1;

            $pdo->exec("INSERT IGNORE INTO profiles (user_id,profile_public,created_at,updated_at) VALUES ({$adminId},1,NOW(),NOW())");
            $pdo->exec("INSERT IGNORE INTO wallets (user_id,points_balance,usd_balance,today_date,created_at,updated_at) VALUES ({$adminId},0,0,CURDATE(),NOW(),NOW())");

            // Seed subscription plans
            $plans = [
                ['Free','free','Get started for free.',0,0,1000,0,1,0,0,'["1000 pts/day cap","Ads enabled","Standard access"]',1,1],
                ['UVOZ Plus','plus','Boost your earnings.',3,29,2000,30,1,0,1,'["2000 pts/day cap","30% earning boost","Fewer ads","Faster withdrawals"]',1,2],
                ['UVOZ Pro','pro','Maximum earnings.',7,65,5000,70,0,1,1,'["5000 pts/day cap","70% earning boost","Ad-free","Priority support"]',1,3],
            ];
            foreach ($plans as $p) {
                $pdo->exec("INSERT IGNORE INTO subscription_plans (name,slug,description,monthly_price,yearly_price,daily_points_cap,earning_boost_percent,ads_enabled,priority_support,faster_withdrawals,features,is_active,sort_order,created_at,updated_at) VALUES ('{$p[0]}','{$p[1]}','{$p[2]}',{$p[3]},{$p[4]},{$p[5]},{$p[6]},{$p[7]},{$p[8]},{$p[9]},'{$p[10]}',{$p[11]},{$p[12]},NOW(),NOW())");
            }

            // Seed reward rates
            $rates = [
                ['voice_post_published',50,5],['voice_play_received',5,null],['voice_listen',2,200],
                ['comment_posted',5,50],['status_view_received',3,null],['contact_click',10,null],
                ['podcast_listen',3,100],['live_room_listen',5,50],['referral_signup',200,null],
                ['daily_login',10,1],
            ];
            foreach ($rates as $r) {
                $limit = $r[2] ? $r[2] : 'NULL';
                $pdo->exec("INSERT IGNORE INTO reward_rates (action_type,points_value,daily_limit,is_active,created_at,updated_at) VALUES ('{$r[0]}',{$r[1]},{$limit},1,NOW(),NOW())");
            }

            // Seed site settings
            $siteUrl = $_SESSION['config']['app_url'] ?? 'https://uvoz.app';
            $settings = [
                ['site_name','UVOZ','general','string'],
                ['site_tagline','Speak. Connect. Earn.','general','string'],
                ['site_url',$siteUrl,'general','string'],
                ['points_per_usd','1000','rewards','integer'],
                ['min_withdrawal','5','rewards','integer'],
                ['referral_commission','10','rewards','integer'],
                ['maintenance_mode','0','general','boolean'],
                ['registration_open','1','general','boolean'],
            ];
            foreach ($settings as $s) {
                $pdo->exec("INSERT IGNORE INTO site_settings (`key`,value,`group`,type,is_public,created_at,updated_at) VALUES ('{$s[0]}','{$s[1]}','{$s[2]}','{$s[3]}',1,NOW(),NOW())");
            }

            // Create storage dirs and symlink
            $storageDirs = ['storage/app/public','storage/framework/cache','storage/framework/sessions','storage/framework/views','storage/logs'];
            foreach ($storageDirs as $dir) {
                $path = __DIR__ . '/../' . $dir;
                if (!is_dir($path)) mkdir($path, 0775, true);
            }

            $_SESSION['installed'] = true;

            // Attempt Composer install automatically
            $appRoot = dirname(__DIR__);
            $composerBins = ['composer', 'composer.phar', '/usr/local/bin/composer', '/usr/bin/composer', '/opt/cpanel/composer/bin/composer'];
            foreach ($composerBins as $bin) {
                $test = @shell_exec("$bin --version 2>&1");
                if ($test && strpos($test, 'Composer') !== false) {
                    @shell_exec("cd $appRoot && $bin install --no-dev --optimize-autoloader 2>&1");
                    break;
                }
            }

            // Fix storage permissions
            $storagePaths = [
                $appRoot.'/storage',
                $appRoot.'/storage/app',
                $appRoot.'/storage/app/public',
                $appRoot.'/storage/framework',
                $appRoot.'/storage/framework/cache',
                $appRoot.'/storage/framework/cache/data',
                $appRoot.'/storage/framework/sessions',
                $appRoot.'/storage/framework/views',
                $appRoot.'/storage/logs',
                $appRoot.'/bootstrap/cache',
            ];
            foreach ($storagePaths as $path) {
                if (!is_dir($path)) @mkdir($path, 0775, true);
                @chmod($path, 0775);
            }

            // Create storage symlink
            $linkPath = $appRoot.'/public/storage';
            if (!file_exists($linkPath) && !is_link($linkPath)) {
                @symlink($appRoot.'/storage/app/public', $linkPath);
            }

            header('Location: ?step=5');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Database setup failed: ' . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>UVOZ Installer v<?= INSTALL_VERSION ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'Segoe UI',system-ui,sans-serif;}
body{background:#0B1020;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
.container{width:100%;max-width:640px;}
.header{text-align:center;margin-bottom:32px;}
.logo{display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;}
.logo-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#7C3AED,#EC4899);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:20px;color:#fff;}
.logo-text{font-size:28px;font-weight:900;background:linear-gradient(135deg,#7C3AED,#EC4899,#F59E0B);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.subtitle{color:#64748B;font-size:14px;}
.card{background:#121A2E;border:1px solid #1E293B;border-radius:20px;padding:32px;margin-bottom:20px;}
.steps{display:flex;gap:8px;margin-bottom:32px;}
.step{flex:1;height:4px;border-radius:4px;background:#1E293B;transition:background .3s;}
.step.done{background:linear-gradient(90deg,#7C3AED,#EC4899);}
.step.active{background:#7C3AED;}
h2{font-size:20px;font-weight:700;color:#fff;margin-bottom:8px;}
p.desc{font-size:14px;color:#64748B;margin-bottom:24px;}
.form-group{margin-bottom:16px;}
label{display:block;font-size:13px;font-weight:500;color:#94A3B8;margin-bottom:6px;}
input[type=text],input[type=email],input[type=password],input[type=url],input[type=number]{width:100%;background:#1E293B;border:1px solid #253045;border-radius:10px;padding:10px 14px;color:#fff;font-size:14px;outline:none;transition:border .2s;}
input:focus{border-color:#7C3AED;box-shadow:0 0 0 3px rgba(124,58,237,.12);}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;border:none;transition:all .2s;width:100%;margin-top:8px;}
.btn-primary{background:linear-gradient(135deg,#7C3AED,#EC4899);color:#fff;}
.btn-primary:hover{opacity:.9;transform:translateY(-1px);}
.btn-secondary{background:#1E293B;color:#CBD5E1;border:1px solid #253045;}
.btn-secondary:hover{border-color:#7C3AED;color:#fff;}
.requirement{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:10px;margin-bottom:6px;background:#0D1525;}
.req-name{font-size:14px;color:#CBD5E1;}
.badge{padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;}
.badge-ok{background:rgba(16,185,129,.2);color:#34D399;}
.badge-fail{background:rgba(239,68,68,.2);color:#FCA5A5;}
.badge-warn{background:rgba(245,158,11,.2);color:#FCD34D;}
.alert{padding:12px 16px;border-radius:12px;margin-bottom:16px;font-size:14px;}
.alert-error{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.25);color:#FCA5A5;}
.alert-success{background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.25);color:#34D399;}
.section-title{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#475569;margin:20px 0 10px;}
.success-icon{width:72px;height:72px;background:linear-gradient(135deg,#7C3AED,#EC4899);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:32px;}
.cred-box{background:#0D1525;border:1px solid #1E293B;border-radius:12px;padding:16px;margin:12px 0;font-size:13px;}
.cred-row{display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #1E293B;}
.cred-row:last-child{border:none;}
.cred-key{color:#64748B;}
.cred-val{color:#A78BFA;font-weight:600;font-family:monospace;}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">
            <div class="logo-icon">U</div>
            <span class="logo-text">UVOZ</span>
        </div>
        <p class="subtitle">Installation Wizard v<?= INSTALL_VERSION ?></p>
    </div>

    <!-- Progress Steps -->
    <div class="steps">
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="step <?= $i < $step ? 'done' : ($i === $step ? 'active' : '') ?>"></div>
        <?php endfor; ?>
    </div>

    <?php if ($errors): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?><div>⚠ <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ── Step 1: Requirements ─────────────────────────────────────────── -->
    <?php if ($step === 1): ?>
    <div class="card">
        <h2>System Requirements</h2>
        <p class="desc">Checking your server is ready to run UVOZ.</p>
        <?php foreach ($requirements as $req): ?>
        <div class="requirement">
            <span class="req-name"><?= htmlspecialchars($req['name']) ?></span>
            <?php if ($req['status']): ?>
            <span class="badge badge-ok">✓ OK</span>
            <?php elseif ($req['required']): ?>
            <span class="badge badge-fail">✗ Required</span>
            <?php else: ?>
            <span class="badge badge-warn">⚠ Optional</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if ($allRequired): ?>
        <a href="?step=2" class="btn btn-primary" style="margin-top:20px;">Continue →</a>
        <?php else: ?>
        <div class="alert alert-error" style="margin-top:16px;">Please fix the required issues above before continuing.</div>
        <a href="?step=1" class="btn btn-secondary" style="margin-top:8px;">Re-check</a>
        <?php endif; ?>
    </div>

    <!-- ── Step 2: Database ──────────────────────────────────────────────── -->
    <?php elseif ($step === 2): ?>
    <div class="card">
        <h2>Database Configuration</h2>
        <p class="desc">Enter your MySQL database credentials.</p>
        <form method="POST">
            <div class="grid">
                <div class="form-group">
                    <label>DB Host</label>
                    <input type="text" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost') ?>" placeholder="localhost">
                </div>
                <div class="form-group">
                    <label>DB Port</label>
                    <input type="number" name="db_port" value="<?= htmlspecialchars($_POST['db_port'] ?? '3306') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Database Name</label>
                <input type="text" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? '') ?>" placeholder="uvoz_db" required>
            </div>
            <div class="form-group">
                <label>Database Username</label>
                <input type="text" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? '') ?>" placeholder="uvoz_user" required>
            </div>
            <div class="form-group">
                <label>Database Password</label>
                <input type="password" name="db_pass" placeholder="••••••••">
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <a href="?step=1" class="btn btn-secondary" style="flex:1;text-decoration:none;">← Back</a>
                <button type="submit" class="btn btn-primary" style="flex:2;">Test & Continue →</button>
            </div>
        </form>
    </div>

    <!-- ── Step 3: App Config ────────────────────────────────────────────── -->
    <?php elseif ($step === 3): ?>
    <div class="card">
        <h2>Application Settings</h2>
        <p class="desc">Configure your app URL, email and payment gateways.</p>
        <form method="POST">
            <div class="form-group">
                <label>App URL</label>
                <input type="url" name="app_url" value="<?= htmlspecialchars($_POST['app_url'] ?? (isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST']) ?>" required>
            </div>

            <div class="section-title">📧 Email (SMTP)</div>
            <div class="grid">
                <div class="form-group">
                    <label>SMTP Host</label>
                    <input type="text" name="mail_host" value="<?= htmlspecialchars($_POST['mail_host'] ?? 'smtp.mailgun.org') ?>">
                </div>
                <div class="form-group">
                    <label>SMTP Port</label>
                    <input type="number" name="mail_port" value="<?= htmlspecialchars($_POST['mail_port'] ?? '587') ?>">
                </div>
            </div>
            <div class="grid">
                <div class="form-group">
                    <label>SMTP Username</label>
                    <input type="text" name="mail_user" value="<?= htmlspecialchars($_POST['mail_user'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>SMTP Password</label>
                    <input type="password" name="mail_pass">
                </div>
            </div>
            <div class="form-group">
                <label>From Email</label>
                <input type="email" name="mail_from" value="<?= htmlspecialchars($_POST['mail_from'] ?? 'noreply@'.$_SERVER['HTTP_HOST']) ?>">
            </div>

            <div class="section-title">💳 Payment Gateways (Optional)</div>
            <div class="grid">
                <div class="form-group">
                    <label>Stripe Publishable Key</label>
                    <input type="text" name="stripe_key" placeholder="pk_live_...">
                </div>
                <div class="form-group">
                    <label>Stripe Secret Key</label>
                    <input type="password" name="stripe_secret" placeholder="sk_live_...">
                </div>
            </div>
            <div class="grid">
                <div class="form-group">
                    <label>Paystack Public Key</label>
                    <input type="text" name="paystack_public" placeholder="pk_live_...">
                </div>
                <div class="form-group">
                    <label>Paystack Secret Key</label>
                    <input type="password" name="paystack_secret" placeholder="sk_live_...">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <a href="?step=2" class="btn btn-secondary" style="flex:1;text-decoration:none;">← Back</a>
                <button type="submit" class="btn btn-primary" style="flex:2;">Save & Continue →</button>
            </div>
        </form>
    </div>

    <!-- ── Step 4: Install ───────────────────────────────────────────────── -->
    <?php elseif ($step === 4): ?>
    <div class="card">
        <h2>Install & Create Admin</h2>
        <p class="desc">Running database migrations and setting up your admin account.</p>
        <form method="POST">
            <div class="form-group">
                <label>Admin Full Name</label>
                <input type="text" name="admin_name" value="UVOZ Admin" required>
            </div>
            <div class="form-group">
                <label>Admin Email</label>
                <input type="email" name="admin_email" value="admin@<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'uvoz.app') ?>" required>
            </div>
            <div class="form-group">
                <label>Admin Password (min 12 chars)</label>
                <input type="password" name="admin_pass" placeholder="Strong password..." required minlength="8">
            </div>
            <div class="alert alert-success" style="margin-bottom:16px;">
                ✓ Database connected &nbsp;·&nbsp; ✓ .env file written
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <a href="?step=3" class="btn btn-secondary" style="flex:1;text-decoration:none;">← Back</a>
                <button type="submit" class="btn btn-primary" style="flex:2;">🚀 Install UVOZ</button>
            </div>
        </form>
    </div>

    <!-- ── Step 5: Complete ──────────────────────────────────────────────── -->
    <?php elseif ($step === 5): ?>
    <div class="card">
        <div class="success-icon">🎉</div>
        <h2 style="text-align:center;">Database Setup Complete!</h2>
        <p class="desc" style="text-align:center;">Database installed. Now complete the final step below.</p>

        <div class="cred-box">
            <div class="cred-row"><span class="cred-key">App URL</span><span class="cred-val"><?= htmlspecialchars($_SESSION['config']['app_url'] ?? '') ?></span></div>
            <div class="cred-row"><span class="cred-key">Admin URL</span><span class="cred-val"><?= htmlspecialchars(($_SESSION['config']['app_url'] ?? '') . '/admin') ?></span></div>
            <div class="cred-row"><span class="cred-key">Database</span><span class="cred-val"><?= htmlspecialchars($_SESSION['db']['name'] ?? '') ?></span></div>
        </div>

        <!-- CRITICAL NEXT STEP -->
        <div style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);border-radius:12px;padding:16px;margin:16px 0;">
            <p style="color:#FCD34D;font-weight:700;font-size:15px;margin-bottom:10px;">⚡ Required: Install PHP Packages</p>
            <p style="color:#CBD5E1;font-size:13px;margin-bottom:12px;">You must install Composer packages before the site will work. Connect via SSH or cPanel Terminal and run:</p>
            <div style="background:#0B1020;border-radius:8px;padding:12px;font-family:monospace;font-size:13px;color:#A78BFA;word-break:break-all;">
                cd <?= htmlspecialchars(dirname(dirname(__FILE__))) ?><br>
                composer install --no-dev --optimize-autoloader
            </div>
            <p style="color:#64748B;font-size:12px;margin-top:8px;">After running composer, visit the setup tool below to verify everything is working.</p>
        </div>

        <!-- Alternative: Setup tool -->
        <div style="background:#0D1525;border:1px solid #1E293B;border-radius:12px;padding:14px;margin-bottom:16px;">
            <p style="color:#CBD5E1;font-size:13px;font-weight:600;margin-bottom:4px;">📋 Then run the Setup Diagnostic Tool:</p>
            <p style="color:#64748B;font-size:12px;">This tool checks your setup, fixes permissions, creates storage links, and confirms everything works.</p>
            <a href="<?= htmlspecialchars(($_SESSION['config']['app_url'] ?? '') . '/setup.php') ?>" style="display:inline-block;margin-top:10px;padding:8px 18px;border-radius:10px;background:linear-gradient(135deg,#7C3AED,#EC4899);color:#fff;font-size:13px;font-weight:600;text-decoration:none;">
                Open Setup Tool →
            </a>
        </div>

        <div class="alert alert-error" style="text-align:left;">
            <strong>⚠ Security:</strong> Delete <code>install/install.php</code> and <code>public/setup.php</code> after setup is complete.
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <a href="<?= htmlspecialchars($_SESSION['config']['app_url'] ?? '/') ?>" class="btn btn-secondary" style="flex:1;text-decoration:none;">🌍 View Site</a>
            <a href="<?= htmlspecialchars(($_SESSION['config']['app_url'] ?? '') . '/admin') ?>" class="btn btn-primary" style="flex:1;text-decoration:none;">⚡ Admin Panel</a>
        </div>
    </div>
    <?php endif; ?>

    <p style="text-align:center;font-size:12px;color:#334155;margin-top:16px;">UVOZ v<?= INSTALL_VERSION ?> · Built with Laravel 12</p>
</div>
</body>
</html>
