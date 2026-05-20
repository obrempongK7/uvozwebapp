<?php
/**
 * UVOZ Setup & Diagnostic Tool
 * Run this AFTER installation to complete setup
 * DELETE this file once setup is complete
 */

$root = dirname(__DIR__);
$errors = [];
$warnings = [];
$success = [];

// ── Checks ───────────────────────────────────────────────────────────────────

// 1. PHP version
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    $success[] = 'PHP ' . PHP_VERSION . ' ✓';
} else {
    $errors[] = 'PHP 8.1+ required. Current: ' . PHP_VERSION;
}

// 2. Vendor directory
$vendorExists = is_dir($root . '/vendor') && file_exists($root . '/vendor/autoload.php');
if ($vendorExists) {
    $success[] = 'Composer vendor directory ✓';
} else {
    $errors[] = 'vendor/ directory missing — Composer packages not installed';
}

// 3. .env file
$envExists = file_exists($root . '/.env');
if ($envExists) {
    $success[] = '.env file exists ✓';
    // Check APP_KEY
    $env = file_get_contents($root . '/.env');
    if (preg_match('/^APP_KEY=base64:.+/m', $env)) {
        $success[] = 'APP_KEY is set ✓';
    } else {
        $errors[] = 'APP_KEY is empty — generate one';
    }
} else {
    $errors[] = '.env file missing — run installer first';
}

// 4. Storage writable
$storageDirs = [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    $path = $root . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
    if (is_writable($path)) {
        $success[] = $dir . ' writable ✓';
    } else {
        @chmod($path, 0775);
        if (is_writable($path)) {
            $success[] = $dir . ' (fixed permissions) ✓';
        } else {
            $errors[] = $dir . ' is NOT writable';
        }
    }
}

// 5. Public storage symlink
$storageLinkTarget = $root . '/storage/app/public';
$storageLinkPath   = $root . '/public/storage';
if (!is_dir($storageLinkTarget)) {
    @mkdir($storageLinkTarget, 0775, true);
}
if (is_link($storageLinkPath)) {
    $success[] = 'public/storage symlink ✓';
} elseif (is_dir($storageLinkPath)) {
    $warnings[] = 'public/storage is a real directory (not a symlink) — uploads may not be served';
} else {
    if (@symlink($storageLinkTarget, $storageLinkPath)) {
        $success[] = 'public/storage symlink created ✓';
    } else {
        // Try copy method for hosts that don't support symlinks
        $warnings[] = 'Could not create symlink for public/storage — using folder copy method';
        if (!is_dir($storageLinkPath)) @mkdir($storageLinkPath, 0775, true);
    }
}

// 6. Try to run composer if available
$composerInstalled = false;
$composerOutput = '';
if (!$vendorExists) {
    // Try multiple composer locations
    $composers = ['composer', 'composer.phar', '/usr/local/bin/composer', '/usr/bin/composer'];
    foreach ($composers as $composer) {
        $test = shell_exec("$composer --version 2>&1");
        if ($test && strpos($test, 'Composer') !== false) {
            $composerInstalled = true;
            $composerOutput = shell_exec("cd $root && $composer install --no-dev --optimize-autoloader 2>&1");
            break;
        }
    }
}

// Re-check vendor after potential install
$vendorExists = is_dir($root . '/vendor') && file_exists($root . '/vendor/autoload.php');

// 7. Test Laravel bootstrap
$laravelBoots = false;
if ($vendorExists && $envExists) {
    try {
        require $root . '/vendor/autoload.php';
        $laravelBoots = true;
        $success[] = 'Laravel bootstrap ✓';
    } catch (\Throwable $e) {
        $errors[] = 'Laravel bootstrap error: ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>UVOZ Setup</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'Segoe UI',system-ui,sans-serif;}
body{background:#0B1020;color:#fff;min-height:100vh;padding:30px 20px;}
.wrap{max-width:700px;margin:0 auto;}
.logo{font-size:28px;font-weight:900;background:linear-gradient(135deg,#7C3AED,#EC4899,#F59E0B);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:8px;}
.sub{color:#64748B;font-size:14px;margin-bottom:30px;}
.card{background:#121A2E;border:1px solid #1E293B;border-radius:16px;padding:24px;margin-bottom:16px;}
h2{font-size:16px;font-weight:700;margin-bottom:14px;}
.item{display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid #1E293B;font-size:14px;}
.item:last-child{border:none;}
.ok{color:#34D399;}.warn{color:#FCD34D;}.err{color:#FCA5A5;}
.icon{font-size:16px;flex-shrink:0;margin-top:1px;}
.cmd{background:#0D1525;border:1px solid #1E293B;border-radius:10px;padding:16px;font-family:monospace;font-size:13px;color:#A78BFA;word-break:break-all;margin:10px 0;}
.step{background:#0D1525;border-left:3px solid #7C3AED;border-radius:0 10px 10px 0;padding:14px 16px;margin-bottom:10px;font-size:13px;}
.step strong{color:#A78BFA;display:block;margin-bottom:4px;}
.btn{display:inline-block;padding:10px 22px;border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;border:none;background:linear-gradient(135deg,#7C3AED,#EC4899);color:#fff;margin-top:8px;}
.alert-err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:16px;margin-bottom:16px;}
.alert-ok{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:12px;padding:16px;margin-bottom:16px;}
pre{white-space:pre-wrap;font-size:12px;color:#94A3B8;margin-top:8px;max-height:200px;overflow-y:auto;}
</style>
</head>
<body>
<div class="wrap">
<div class="logo">UVOZ</div>
<div class="sub">Setup Diagnostic Tool — Delete this file after setup is complete</div>

<?php if (!$vendorExists && !$composerInstalled): ?>
<div class="alert-err">
<strong style="color:#FCA5A5">⚠ Main Issue: Composer packages not installed</strong>
<p style="color:#CBD5E1;font-size:13px;margin-top:6px;">The <code>vendor/</code> directory is missing. This is why you're getting HTTP 500 errors. Follow the steps below to fix it.</p>
</div>
<?php elseif ($vendorExists && empty($errors)): ?>
<div class="alert-ok">
<strong style="color:#34D399">✓ Everything looks good!</strong>
<p style="color:#CBD5E1;font-size:13px;margin-top:6px;">Your UVOZ installation should be working. <a href="/" style="color:#A78BFA">Visit the homepage →</a></p>
</div>
<?php endif; ?>

<!-- Status Checks -->
<div class="card">
<h2>📋 System Status</h2>
<?php foreach ($success as $s): ?>
<div class="item"><span class="icon ok">✓</span><span class="ok"><?= htmlspecialchars($s) ?></span></div>
<?php endforeach; ?>
<?php foreach ($warnings as $w): ?>
<div class="item"><span class="icon warn">⚠</span><span class="warn"><?= htmlspecialchars($w) ?></span></div>
<?php endforeach; ?>
<?php foreach ($errors as $e): ?>
<div class="item"><span class="icon err">✗</span><span class="err"><?= htmlspecialchars($e) ?></span></div>
<?php endforeach; ?>
</div>

<?php if (!$vendorExists): ?>
<!-- Fix Instructions -->
<div class="card">
<h2>🔧 How to Fix — Install Composer Packages</h2>

<div class="step"><strong>Option 1: SSH / Terminal (Recommended)</strong>
Connect via SSH to your server, navigate to your app root, and run:
<div class="cmd">cd /path/to/your/uvoz<br>composer install --no-dev --optimize-autoloader</div>
<small style="color:#64748B">Replace /path/to/your/uvoz with your actual app root (not public_html/public)</small>
</div>

<div class="step"><strong>Option 2: cPanel Terminal</strong>
In cPanel → Terminal, run the same command above.
</div>

<div class="step"><strong>Option 3: cPanel Git Version Control</strong>
If your host has Composer in cPanel File Manager, use the built-in terminal.
</div>

<div class="step"><strong>Option 4: Ask your host</strong>
Contact your hosting provider and ask them to run <code>composer install</code> in your app directory, or ask if they support Composer.
</div>

<div class="step"><strong>Option 5: Download vendor.zip separately</strong>
If you cannot run Composer, you can download a pre-built vendor package:<br>
<span style="color:#64748B;font-size:12px;">1. On your local machine: run <code>composer install --no-dev</code> inside the uvoz folder<br>2. Zip the <code>vendor/</code> directory<br>3. Upload and extract it to your server inside the uvoz/ folder</span>
</div>

</div>
<?php endif; ?>

<?php if ($composerOutput): ?>
<div class="card">
<h2>📦 Composer Output</h2>
<pre><?= htmlspecialchars($composerOutput) ?></pre>
<?php if ($vendorExists): ?>
<div style="margin-top:12px;padding:12px;background:rgba(16,185,129,.1);border-radius:10px;color:#34D399;font-size:14px;">✓ Composer install succeeded! Refresh this page to verify, then visit your site.</div>
<a href="/" class="btn">Visit UVOZ →</a>
<?php endif; ?>
</div>
<?php endif; ?>

<!-- .htaccess Check -->
<div class="card">
<h2>⚙️ Apache / .htaccess</h2>
<div class="item"><span class="icon"><?= file_exists(__DIR__.'/.htaccess') ? '<span class="ok">✓</span>' : '<span class="err">✗</span>' ?></span><span><?= file_exists(__DIR__.'/.htaccess') ? '<span class="ok">public/.htaccess exists</span>' : '<span class="err">public/.htaccess missing</span>' ?></span></div>
<div class="item"><span class="icon"><?= function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '<span class="ok">✓</span>' : '<span class="warn">?</span>' ?></span>
<span><?= function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '<span class="ok">mod_rewrite enabled</span>' : '<span class="warn">mod_rewrite status unknown (may still work)</span>' ?></span></div>
<p style="color:#64748B;font-size:12px;margin-top:10px;">Your domain root MUST point to the <strong style="color:#CBD5E1">uvoz/public/</strong> folder, not uvoz/ itself.</p>
<div class="cmd">Document Root: /home/username/public_html/uvoz/public</div>
</div>

<!-- PHP Info -->
<div class="card">
<h2>🖥️ Server Info</h2>
<div class="item"><span>PHP</span><span style="margin-left:auto;color:#A78BFA"><?= PHP_VERSION ?></span></div>
<div class="item"><span>Server</span><span style="margin-left:auto;color:#A78BFA"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></span></div>
<div class="item"><span>Document Root</span><span style="margin-left:auto;color:#A78BFA;font-size:12px"><?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?></span></div>
<div class="item"><span>Script Path</span><span style="margin-left:auto;color:#A78BFA;font-size:12px"><?= __DIR__ ?></span></div>
<div class="item"><span>App Root</span><span style="margin-left:auto;color:#A78BFA;font-size:12px"><?= $root ?></span></div>
</div>

<?php if ($vendorExists && $laravelBoots): ?>
<div class="alert-ok">
<strong style="color:#34D399">✓ Setup Complete!</strong>
<p style="color:#CBD5E1;font-size:13px;margin-top:4px;">Laravel is booting correctly. <strong style="color:#FCD34D">Delete this file (public/setup.php) now for security.</strong></p>
<a href="/" class="btn">Open UVOZ →</a>
<a href="/admin" class="btn" style="margin-left:8px;background:#1E293B;">Admin Panel →</a>
</div>
<?php endif; ?>

<p style="text-align:center;color:#334155;font-size:12px;margin-top:20px;">UVOZ Setup Tool — Delete public/setup.php after use</p>
</div>
</body>
</html>
