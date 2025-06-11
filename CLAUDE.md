# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Codebase Overview

This is a custom WordPress theme for Mia Aesthetics, a multi-location plastic surgery practice. The theme is built without a build process - all CSS and JavaScript is hand-written and served directly through WordPress's enqueue system.

## Development Workflow

**No Build Process:** This theme uses direct file editing with WordPress enqueue system for asset management. There are no npm scripts, webpack, or other build tools.

**Key Development Commands:**
- Asset cache busting: File modification times are used automatically via `inc/enqueue.php:298-300`
- WordPress cache clearing: Use WP-CLI `wp cache purge` or admin interface
- Schema validation: WordPress debug mode shows schema output in page source 
- Manual FTP/SFTP deployment for file changes
- Database imports for ACF field configurations

## Architecture

### Custom Post Types
- `surgeon` - Individual surgeon profiles
- `procedure` - Surgical procedures (hierarchical) 
- `condition` - Medical conditions
- `location` - Practice locations
- `case` - Before/after case studies
- `special` - Special offers/promotions
- `non-surgical` - Non-surgical treatments

### File Organization
- `/inc/` - Modular functionality helpers (12 specialized files)
- `/assets/css/` - Component-based CSS with underscore prefixes
- `/assets/js/` - Context-specific JavaScript modules
- Template files follow WordPress hierarchy with custom post types

### Key Dependencies
- **Advanced Custom Fields (ACF)** - Critical for all custom content
- Bootstrap 5 and Font Awesome 6 included as complete libraries
- Performance-first approach with context-aware asset loading

### Asset Management
Advanced context-aware loading system in `inc/enqueue.php`:
- **Context Detection:** `mia_get_current_context()` determines page type and loads appropriate CSS/JS
- **Critical Resource Loading:** Fonts, base CSS, and Bootstrap loaded with high priority
- **Automatic Cache Busting:** File modification times used for versioning (`filemtime()`)
- **Performance Optimizations:** Preloading (`mia_preload_critical_assets()`), script deferring, resource hints

**Key Functions:**
- `mia_enqueue_context_styles()` - Conditional asset loading
- `mia_get_current_context()` - Page type detection with static caching
- Context types: home, archive, single, gallery, condition, surgeon, location, etc.

### Data Structure
Before/after gallery data is stored in `/assets/data/before-after-gallery.json` and organized by surgeon with procedure categories.

## Important Patterns

**Performance Optimization:**
- **Caching Strategy:** `wp_cache` with 2-hour expiration for post counts and site stats (`inc/cache-helpers.php`)
- **Query Optimization:** `update_post_meta_cache` and `update_post_term_cache` disabled when not needed (`inc/queries.php:294-302`)
- **Asset Optimization:** Critical resource preloading, script deferring, resource hints
- **Static Caching:** Context detection cached per request to prevent repeated calculations

**Medical Practice Features:**
- **Schema.org Integration:** Comprehensive medical schema (procedures, surgeons, locations) complementing Yoast SEO
- **Custom Query Modifications:** Archive-specific ordering and pagination (`inc/queries.php`)
- **Multi-location Architecture:** Hierarchical content with location-based filtering
- **ACF Integration:** All custom content managed through Advanced Custom Fields

**Code Organization:**
- **Modular Helper System:** 12 specialized files in `/inc/` handle specific functionality areas
- **Context-Driven Loading:** Assets loaded based on page context rather than globally
- **Template Hierarchy:** WordPress template hierarchy extended for custom post types
- **Function Naming Convention:** All theme functions prefixed with `mia_`

**Key Helper Files:**
- `inc/enqueue.php` - Asset management and context detection
- `inc/queries.php` - Query modifications and archive behaviors  
- `inc/schema.php` - Medical schema.org structured data
- `inc/cache-helpers.php` - Performance caching utilities