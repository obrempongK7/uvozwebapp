═══════════════════════════════════════════════════
  UVOZ WEB APPLICATION - INSTALLATION GUIDE
═══════════════════════════════════════════════════

STEP 1: UPLOAD FILES
─────────────────────
Upload the entire uvoz/ folder to your server.
Example path: /home/username/uvoz/

STEP 2: SET DOCUMENT ROOT
──────────────────────────
In cPanel → Domains, point your domain's Document Root to:
   /home/username/uvoz/public

NOT to uvoz/ — it MUST be uvoz/public/

STEP 3: RUN THE INSTALLER
──────────────────────────
Visit: https://yourdomain.com/install/install.php
Complete all 5 steps.

STEP 4: INSTALL COMPOSER PACKAGES (REQUIRED)
──────────────────────────────────────────────
Connect via SSH or cPanel Terminal and run:

   cd /home/uvoz/public_html
   composer install --no-dev --optimize-autoloader

If composer is not found, try:
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   php composer.phar install --no-dev --optimize-autoloader

STEP 5: RUN SETUP DIAGNOSTIC
──────────────────────────────
Visit: https://yourdomain.com/setup.php
This verifies everything and creates required symlinks.

STEP 6: CLEANUP (IMPORTANT)
─────────────────────────────
Delete these files for security:
   uvoz/install/install.php
   uvoz/public/setup.php

═══════════════════════════════════════════════════
DEFAULT ADMIN LOGIN
   URL:      https://yourdomain.com/admin
   Email:    (set during installer step 4)
   Password: (set during installer step 4)
═══════════════════════════════════════════════════

TROUBLESHOOTING
───────────────
500 Error → Run composer install (Step 4 above)
404 on /admin → Check domain points to uvoz/public/
White page → Check storage/ is writable (chmod 775)
Upload fails → Check public/storage symlink exists

For help: support@uvoz.app
═══════════════════════════════════════════════════
