# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-13

### Added
- Initial release of Maho URL Manager
- Manual redirect management with support for 301 and 302 redirects
- Wildcard redirect support for bulk URL patterns
- Comprehensive 404 error tracking and logging
- Hit counting for repeated 404 errors
- CSV import/export for bulk redirect management
- Automated email notifications (daily/weekly) for high-traffic 404 errors
- Professional HTML email templates with detailed statistics
- Smart product suggestions on 404 pages
- Meilisearch integration for fuzzy matching product suggestions
- Automatic redirect rules for disabled products and categories
- Auto-cleanup of 404 logs when matching redirects are created
- Configurable case-sensitive URL matching
- Query string handling options
- Bot traffic filtering for 404 logs
- Maximum log entry limits to prevent database bloat
- Admin interface with sortable, filterable grids
- Mass actions for redirect management
- Comprehensive system configuration interface
- ACL permissions for admin users
- Database setup scripts with proper indexes
- Event observers for automatic functionality
- Custom router for redirect matching
- SMTP Pro compatibility for reliable email delivery

### Configuration Options
- Enable/disable URL Manager
- Wildcard character customization
- Case-sensitive matching toggle
- Query string stripping option
- 404 logging enable/disable
- Bot traffic logging option
- Maximum log entries limit
- Email notification enable/disable
- Report frequency (daily/weekly)
- Recipient email configuration
- Minimum hit count threshold
- Product suggestion enable/disable
- Maximum suggestions count
- Meilisearch toggle
- Automatic redirect rules for disabled entities

### Technical Details
- Built for Maho Commerce 25.x+
- Requires PHP 8.3+
- PSR-0 compliant code structure
- Modern PHP 8.3 features (strict typing, return types)
- Proper event-driven architecture
- Database tables with appropriate indexes
- Header-based 404 detection (compatible with Maho response handling)
- Uses `Mage_Core_Model_Locale::now()` for date/time operations
- Source models properly located in `Model/Adminhtml/System/Config/Source/`
- Composer-installable via magento-module type
