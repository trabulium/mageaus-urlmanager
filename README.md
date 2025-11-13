# Maho URL Manager

Advanced 404 URL Manager for Maho Commerce - Track, redirect, and manage 404 errors with automated email notifications.

## Features

### 🔄 Redirect Management
- **Manual Redirects**: Create custom URL redirects with support for 301 (permanent) and 302 (temporary) redirects
- **Wildcard Support**: Use wildcards to redirect multiple URLs with a single rule (e.g., `/old-path/*` → `/new-path/`)
- **CSV Import/Export**: Bulk import redirects from CSV files and export existing redirects
- **Automatic Redirects**: Auto-redirect disabled products and categories to appropriate pages
- **Admin Interface**: Easy-to-use grid interface for managing redirects

### 📊 404 Error Tracking
- **Comprehensive Logging**: Track all 404 errors with URL, referrer, user agent, and timestamp
- **Hit Counting**: Automatically count repeated 404 hits to identify problematic URLs
- **Bot Filtering**: Option to include or exclude bot/crawler traffic from logs
- **Auto-Cleanup**: Automatically remove 404 logs when matching redirects are created
- **Log Management**: Set maximum log entries to prevent database bloat

### 📧 Email Notifications
- **Scheduled Reports**: Daily or weekly email reports of high-traffic 404 errors
- **Configurable Threshold**: Only report URLs with a minimum number of hits
- **Professional HTML Templates**: Beautiful, responsive email templates with detailed statistics
- **SMTP Pro Compatible**: Works seamlessly with SMTP Pro for reliable email delivery

### 🔍 Smart Product Suggestions *(Coming Soon)*
- **Meilisearch Integration**: Use Meilisearch for fuzzy matching to suggest relevant products on 404 pages
- **Fallback Support**: Falls back to native Maho search if Meilisearch is not available
- **Configurable Display**: Set the number of product suggestions to show
- **SEO-Friendly**: Help visitors find what they're looking for instead of seeing a blank 404 page

### ⚙️ Flexible Configuration
All features can be configured via **System → Configuration → Mageaus → URL Manager**:
- Enable/disable URL Manager
- Wildcard character customization
- Case-sensitive matching
- Query string handling
- 404 logging options
- Email notification settings
- Automatic redirect rules

## Installation

### Via Composer (Recommended)

```bash
composer require mageaus/urlmanager
composer dump-autoload
./maho cache:flush
```

### Manual Installation

1. Download the latest release
2. Extract to your Maho root directory
3. Run:
```bash
composer dump-autoload
./maho cache:flush
```

## Requirements

- Maho Commerce 25.x or higher
- PHP 8.3 or higher
- MySQL/MariaDB

### Optional Dependencies

- **SMTP Pro**: For reliable email delivery of 404 reports
  ```bash
  composer require aschroder/smtp-pro
  ```

## Quick Start

### 1. Enable the Module

Navigate to **System → Configuration → Mageaus → URL Manager** and:
- Set "Enable URL Manager" to "Yes"
- Enable "404 Logging" to track errors
- Configure email notifications if desired

### 2. Create Your First Redirect

Go to **Mageaus → URL Manager → Redirects** and click "Add New Redirect":
- **Source URL**: The old/broken URL (e.g., `/old-product.html`)
- **Target URL**: Where to redirect (e.g., `/new-product.html`)
- **Redirect Type**: 301 (Permanent) or 302 (Temporary)
- **Status**: Enabled

### 3. Import Bulk Redirects

For multiple redirects:
1. Go to **System → Configuration → Mageaus → URL Manager → CSV Import/Export**
2. Prepare a CSV file with format: `source_url,target_url,redirect_type`
3. Upload and save

### 4. View 404 Logs

Check **Mageaus → URL Manager → 404 Logs** to see:
- Which URLs are returning 404 errors
- How many times each URL has been hit
- When the last hit occurred
- Referrer and user agent information

## Configuration Options

### Redirect Settings

| Setting | Description | Default |
|---------|-------------|---------|
| Enable URL Manager | Master switch for all redirect functionality | No |
| Wildcard Character | Character used for wildcard matching | `*` |
| Case Sensitive Matching | Whether URL matching is case-sensitive | No |
| Strip Query String | Remove query string before matching | No |

### 404 Logging

| Setting | Description | Default |
|---------|-------------|---------|
| Enable 404 Logging | Track 404 errors in database | No |
| Log Bot Traffic | Include bot/crawler 404s | Yes |
| Maximum Log Entries | Limit log entries (0 = unlimited) | 10000 |

### Email Notifications

| Setting | Description | Default |
|---------|-------------|---------|
| Enable Email Notifications | Send periodic 404 reports | No |
| Report Frequency | Daily or Weekly | Weekly |
| Recipient Email | Email address for reports | - |
| Minimum Hit Count | Only report URLs with X+ hits | 10 |

### Smart Suggestions

| Setting | Description | Default |
|---------|-------------|---------|
| Enable Product Suggestions | Show suggested products on 404 pages | No |
| Maximum Suggestions | Number of products to suggest | 5 |
| Use Meilisearch | Use Meilisearch for fuzzy matching | No |

### Automatic Redirects

| Setting | Description | Default |
|---------|-------------|---------|
| Disabled Products Check | Redirect disabled products to category | No |
| Not Visible Products Check | Redirect not-visible products | No |
| Disabled Categories Check | Redirect disabled categories to parent | No |

## Advanced Usage

### Wildcard Redirects

Redirect multiple URLs with a single rule:

```
Source: /old-category/*
Target: /new-category/
Type: 301
```

This will redirect:
- `/old-category/product1.html` → `/new-category/`
- `/old-category/product2.html` → `/new-category/`
- `/old-category/anything` → `/new-category/`

### CSV Import Format

Your CSV file should have three columns (no header row):

```csv
/old-url-1.html,/new-url-1.html,301
/old-url-2.html,/new-url-2.html,302
/old-category/*,/new-category/,301
```

### Cron Schedule

Email reports are sent automatically:
- **Daily**: Every day at 8:00 AM
- **Weekly**: Every Monday at 8:00 AM

Cron configuration is in `etc/config.xml` if you need to customize the schedule.

### Meilisearch Integration

To use Meilisearch for product suggestions:

1. Install Meilisearch PHP client:
   ```bash
   composer require meilisearch/meilisearch-php
   ```

2. Configure Meilisearch in Maho

3. Enable in URL Manager settings:
   - System → Configuration → Mageaus → URL Manager → Smart Suggestions
   - Set "Use Meilisearch for Suggestions" to "Yes"

## Admin Interface

### Redirects Grid

**Mageaus → URL Manager → Redirects**

- View all redirects in a sortable, filterable grid
- Mass delete redirects
- Edit redirects inline
- Filter by status, type, or store

### 404 Logs Grid

**Mageaus → URL Manager → 404 Logs**

- View all 404 errors with hit counts
- Sort by hits, date, or URL
- Filter by URL or referrer
- Create redirects directly from 404 logs

## Email Report Format

The 404 email report includes:

- **Summary**: Total number of 404 URLs above threshold
- **Top 404 URLs Table**:
  - Request URL
  - Hit count
  - Last hit timestamp
- **Recommended Actions**: Suggestions for fixing common issues
- **Direct Links**: Quick link to admin 404 log grid

## Troubleshooting

### Redirects Not Working

1. Check that "Enable URL Manager" is set to "Yes"
2. Verify redirect status is "Enabled"
3. Flush cache: `./maho cache:flush`
4. Check that source URL matches exactly (or use wildcard)

### 404 Logs Not Recording

1. Verify "Enable 404 Logging" is set to "Yes"
2. Check that responses are actually returning 404 status
3. Review `var/log/mageaus_urlmanager.log` for errors

### Email Reports Not Sending

1. Check "Enable Email Notifications" is set to "Yes"
2. Verify recipient email is configured
3. Ensure cron is running: `./maho sys:cron:run`
4. Check that URLs meet minimum hit threshold
5. Review email logs for SMTP errors
6. Consider installing SMTP Pro for reliable delivery

### Product Suggestions Not Showing

1. Enable "Product Suggestions" in settings
2. Verify products exist and are enabled
3. If using Meilisearch, ensure it's properly configured
4. Check that 404 template includes suggestion block

## Database Tables

The module creates these tables:

- `mageaus_urlmanager_redirect`: Stores redirect rules
- `mageaus_urlmanager_notfoundlog`: Stores 404 error logs

## Permissions

Admin users need these ACL permissions:

- `mageaus_urlmanager/redirect`: Manage redirects
- `mageaus_urlmanager/notfoundlog`: View 404 logs

Configure in **System → Permissions → Roles**

## Development

### File Structure

```
app/code/local/Mageaus/UrlManager/
├── Block/
│   └── Adminhtml/          # Admin grid and form blocks
├── Controller/
│   └── Router.php          # Custom router for redirect matching
├── controllers/
│   └── Adminhtml/          # Admin controllers
├── etc/
│   ├── config.xml          # Module configuration
│   ├── adminhtml.xml       # ACL and admin menu
│   └── system.xml          # System configuration
├── Helper/
│   └── Data.php            # Helper methods
├── Model/
│   ├── Redirect.php        # Redirect model
│   ├── Notfoundlog.php     # 404 log model
│   ├── Observer.php        # Event observers
│   └── Resource/           # Resource models
└── sql/
    └── mageaus_urlmanager_setup/  # Database setup scripts
```

### Events

The module observes these events:

- `controller_front_init_routers`: Inject custom router
- `mageaus_urlmanager_redirect_save_after`: Clear matching 404 logs
- `http_response_send_before`: Log 404 errors

### Extending

To add custom redirect logic, create an observer for `mageaus_urlmanager_redirect_save_after`:

```php
public function customRedirectLogic($observer)
{
    $redirect = $observer->getEvent()->getRedirect();
    // Your custom logic here
}
```

## Support

For issues, feature requests, or questions:

- **GitHub Issues**: https://github.com/mageaus/urlmanager/issues
- **Email**: support@mageaus.com

## License

Open Software License v. 3.0 (OSL-3.0)

## Credits

Developed for the Maho Commerce ecosystem.

## Changelog

### Version 1.0.0 (2025-01-13)

**Initial Release**

- ✅ Manual redirect management with wildcards
- ✅ 404 error tracking and logging
- ✅ CSV import/export for bulk operations
- ✅ Email notifications (daily/weekly)
- ✅ Smart product suggestions
- ✅ Meilisearch integration
- ✅ Automatic redirect rules
- ✅ Admin interface with grids
- ✅ Comprehensive configuration options
