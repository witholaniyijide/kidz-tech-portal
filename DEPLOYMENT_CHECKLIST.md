# Kidz Tech Portal - Deployment Checklist

## Pre-Deployment Checklist

### 1. Environment Setup
- [ ] `.env` file configured for production
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` is set (run `php artisan key:generate`)
- [ ] Database credentials configured correctly
- [ ] `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- [ ] Mail configuration set up (SMTP or service)
- [ ] Queue configuration (recommend `database` or `redis` for production)
- [ ] Session driver set to `database` or `redis` (not `file` on load-balanced systems)
- [ ] Cache driver configured (`redis` recommended for production)

### 2. Database Preparation
- [ ] Backup production database before deployment
  ```bash
  mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
  ```
- [ ] Run `php artisan migrate:status` to check migration status
- [ ] Test migrations on staging environment first
- [ ] Verify database indexes exist for frequently queried columns

### 3. Code Quality & Tests
- [ ] All tests pass locally: `php artisan test`
- [ ] No critical errors in logs: `tail -200 storage/logs/laravel.log`
- [ ] All routes verified: `php artisan route:list`
- [ ] No unused dependencies in composer.json
- [ ] Frontend assets compiled for production

### 4. File System & Storage
- [ ] Storage symlink created: `php artisan storage:link`
- [ ] Ensure `storage/` and `bootstrap/cache/` are writable by web server
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```
- [ ] Backup media files: `tar czf storage_backup.tgz storage/app/public`
- [ ] Create directories for uploads:
  ```bash
  mkdir -p storage/app/public/profile_photos/tutors
  mkdir -p storage/app/public/profile_photos/students
  mkdir -p storage/app/public/notices
  ```

### 5. Security Checks
- [ ] All admin routes protected by `role:admin` middleware
- [ ] All manager routes protected by `role:manager` middleware
- [ ] All tutor routes protected by `role:tutor` middleware
- [ ] CSRF protection enabled (default)
- [ ] XSS protection: using `{{ }}` not `{!! !!}` for user input
- [ ] SQL injection protection: using Eloquent ORM (built-in)
- [ ] Mass assignment protection: `$fillable` arrays defined on all models
- [ ] Password hashing enabled (bcrypt, default)
- [ ] Force HTTPS in production (configure web server)

### 6. Performance Optimizations
- [ ] Composer autoload optimized: `composer install --optimize-autoloader --no-dev`
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Views cached: `php artisan view:cache`
- [ ] Icons cached: `php artisan icons:cache` (if using blade-icons)
- [ ] Opcache enabled in PHP
- [ ] Query result caching for expensive reads
- [ ] CDN configured for static assets (optional)

---

## Deployment Steps (Production)

### Step 1: Pre-Deploy Backup
```bash
# Backup database
mysqldump -u username -p dbname > /backups/db_backup_$(date +%Y%m%d_%H%M%S).sql

# Backup media files
tar czf /backups/storage_backup_$(date +%Y%m%d_%H%M%S).tgz storage/app/public

# Backup .env file
cp .env /backups/.env_backup_$(date +%Y%m%d_%H%M%S)
```

### Step 2: Enable Maintenance Mode
```bash
php artisan down --message="Upgrading system. Back in 5 minutes."
```

### Step 3: Pull Latest Code
```bash
git fetch origin
git checkout main
git pull origin main
```

### Step 4: Install Dependencies
```bash
# Install PHP dependencies (production only)
composer install --no-dev --prefer-dist --optimize-autoloader

# Install NPM dependencies and build assets
npm ci
npm run build
```

### Step 5: Run Migrations
```bash
# Preview migrations first
php artisan migrate:status

# Run migrations (with confirmation)
php artisan migrate --force

# If any migrations fail, rollback and restore backup
# php artisan migrate:rollback --step=1
```

### Step 6: Clear & Cache Everything
```bash
# Clear all caches
php artisan optimize:clear

# Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure storage link exists
php artisan storage:link
```

### Step 7: Restart Services
```bash
# Restart PHP-FPM (adjust for your server)
sudo systemctl restart php8.2-fpm

# Restart queue workers (if using)
php artisan queue:restart

# Restart supervisor (if managing queues)
sudo supervisorctl restart all
```

### Step 8: Disable Maintenance Mode
```bash
php artisan up
```

### Step 9: Smoke Test
- [ ] Visit homepage: `https://yourdomain.com`
- [ ] Login as admin: `https://yourdomain.com/login`
- [ ] Check admin dashboard
- [ ] Check manager dashboard
- [ ] Check tutor dashboard
- [ ] Submit test attendance
- [ ] Create test report
- [ ] Verify notice board
- [ ] Check logs for errors: `tail -50 storage/logs/laravel.log`

---

## Post-Deployment Monitoring

### Immediate (First 30 minutes)
- [ ] Monitor error logs continuously
  ```bash
  tail -f storage/logs/laravel.log
  ```
- [ ] Check server resources (CPU, memory, disk)
- [ ] Verify queue workers are processing jobs
- [ ] Test critical user flows
- [ ] Check response times

### First 24 Hours
- [ ] Monitor application performance
- [ ] Check for spike in error rates
- [ ] Verify scheduled tasks are running (`schedule:run`)
- [ ] Review user feedback
- [ ] Monitor database query performance

### Week 1
- [ ] Review analytics for anomalies
- [ ] Check for slow queries
- [ ] Verify all email notifications are sent
- [ ] Review user-reported issues
- [ ] Optimize based on real-world usage patterns

---

## Rollback Plan

### If Critical Issue Detected:

1. **Enable Maintenance Mode**
   ```bash
   php artisan down
   ```

2. **Rollback Code**
   ```bash
   git checkout <previous-stable-commit>
   composer install --no-dev --optimize-autoloader
   ```

3. **Rollback Database** (if migrations were run)
   ```bash
   # Restore database backup
   mysql -u username -p dbname < /backups/db_backup_YYYYMMDD_HHMMSS.sql
   ```

4. **Restore Media Files** (if needed)
   ```bash
   tar xzf /backups/storage_backup_YYYYMMDD_HHMMSS.tgz
   ```

5. **Clear Caches**
   ```bash
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. **Restart Services**
   ```bash
   sudo systemctl restart php8.2-fpm
   php artisan queue:restart
   ```

7. **Disable Maintenance Mode**
   ```bash
   php artisan up
   ```

8. **Verify Rollback**
   - Test critical flows
   - Check logs
   - Confirm system stability

---

## Environment-Specific Configuration

### Production (.env)
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning

DB_CONNECTION=mysql
QUEUE_CONNECTION=database

CACHE_DRIVER=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
```

### Staging (.env)
```env
APP_ENV=staging
APP_DEBUG=true
LOG_LEVEL=debug

DB_CONNECTION=mysql
QUEUE_CONNECTION=database

CACHE_DRIVER=array
SESSION_DRIVER=file

MAIL_MAILER=log
```

---

## Scheduled Tasks Configuration

Ensure cron job is configured:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Queue Workers Configuration (Supervisor)

Create file: `/etc/supervisor/conf.d/kidz-tech-queue.conf`
```ini
[program:kidz-tech-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/kidz-tech-portal/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/kidz-tech-portal/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start kidz-tech-queue:*
```

---

## Monitoring & Alerting

### Recommended Tools
- **Application Monitoring**: Sentry, Bugsnag, or Flare
- **Server Monitoring**: Uptime Robot, Pingdom
- **Log Aggregation**: Papertrail, Logtail
- **Performance Monitoring**: New Relic, Blackfire

### Key Metrics to Monitor
- Response time (P50, P95, P99)
- Error rate (5xx responses)
- Database query time
- Queue job processing time
- Memory usage
- Disk space
- CPU usage

---

## Testing Requirements

### Before Deployment (Staging)
- [ ] **SQLite Driver**: Install `php-sqlite3` for tests
  ```bash
  sudo apt-get install php8.2-sqlite3
  ```
- [ ] Run full test suite: `php artisan test`
- [ ] All tests must pass before deploying to production
- [ ] Test migrations on staging database
- [ ] Smoke test all critical workflows

---

## Security Best Practices

1. **Never commit** `.env` file to version control
2. **Never commit** sensitive keys or passwords
3. **Always use** HTTPS in production
4. **Rotate** application key after major security incidents
5. **Backup** database daily (automated)
6. **Monitor** logs for suspicious activity
7. **Update** dependencies regularly: `composer update` (test first!)
8. **Scan** for vulnerabilities: `composer audit`
9. **Enable** firewall rules (only allow HTTP/HTTPS, SSH)
10. **Disable** unnecessary PHP functions (`exec`, `shell_exec` if not needed)

---

## Common Issues & Solutions

### Issue: 500 Error After Deployment
- Clear all caches: `php artisan optimize:clear`
- Check file permissions on `storage/` and `bootstrap/cache/`
- Check `.env` file is present and correct
- Check logs: `storage/logs/laravel.log`

### Issue: Routes Not Found
- Clear route cache: `php artisan route:clear`
- Rebuild route cache: `php artisan route:cache`
- Verify route middleware is correct

### Issue: Views Not Updating
- Clear view cache: `php artisan view:clear`
- Clear config cache: `php artisan config:clear`

### Issue: Queue Jobs Not Processing
- Restart queue workers: `php artisan queue:restart`
- Check queue worker is running: `ps aux | grep queue:work`
- Check supervisor status: `sudo supervisorctl status`

### Issue: File Uploads Failing
- Check storage link: `php artisan storage:link`
- Check permissions: `chmod -R 775 storage`
- Check disk space: `df -h`

---

## Support & Documentation

- **Laravel Documentation**: https://laravel.com/docs
- **Application Logs**: `storage/logs/laravel.log`
- **Web Server Logs**: `/var/log/nginx/error.log` or `/var/log/apache2/error.log`
- **PHP Error Logs**: `/var/log/php8.2-fpm.log`

---

## Deployment Sign-Off

**Deployed By**: _____________________
**Date/Time**: _____________________
**Version**: _____________________
**Backup Location**: _____________________
**Rollback Plan Verified**: [ ] Yes [ ] No
**Smoke Tests Passed**: [ ] Yes [ ] No

**Notes**:
___________________________________________
___________________________________________
___________________________________________
