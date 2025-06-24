# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**MIA Aesthetics v3** is a custom WordPress theme for the aesthetics industry, hosted on WP Engine. This is a professional medical aesthetics website with performance optimization, security focus, and responsive design.

### Technology Stack
- **CMS**: WordPress with custom theme
- **Framework**: Bootstrap for responsive design
- **Performance**: WP Rocket caching + Imagify image optimization
- **Functionality**: Advanced Custom Fields (ACF) Pro, Gravity Forms
- **Hosting**: WP Engine (enterprise-grade)

## Architecture

### Theme Structure
The theme follows WordPress best practices with modular organization:

```
/
├── functions.php           # Theme bootstrap - loads all helper modules
├── style.css              # Theme identification (actual styles in /assets/css/)
├── /inc/                  # Core functionality modules
│   ├── enqueue.php        # Asset management with hash-based versioning
│   ├── theme-support.php  # WordPress theme features & Gutenberg setup
│   ├── template-helpers.php # UI utilities, logo handling, formatting
│   ├── media-helpers.php  # Image sizes, video helpers, media utilities
│   ├── menu-helpers.php   # Menu rendering with caching
│   ├── cache-helpers.php  # Cache management and clearing
│   ├── schema.php         # Structured data output
│   └── queries.php        # Custom query modifications
├── /assets/
│   ├── /css/              # Page-specific stylesheets
│   ├── /js/               # Page-specific JavaScript
│   ├── /data/             # JSON data files (e.g., before-after-gallery.json)
│   └── /fonts/            # Web fonts (Inter, Montserrat)
└── page-*.php, single-*.php, archive-*.php # Template files
```

### Template Hierarchy
- **Custom Post Types**: surgeon, location, procedure, case, condition, special, fat-transfer, non-surgical
- **Template Variations**: 
  - `page-hero-canvas.php` - Full-width with hero header and breadcrumbs
  - `page-blank-canvas.php` - Minimal wrapper for Gutenberg
  - `page-no-bc.php` - Without breadcrumbs
  - `page-condition-layout.php` / `page-treatment-layout.php` - Specialized layouts

### Asset Management
The theme uses a sophisticated asset system in `inc/enqueue.php`:
- File modification time-based versioning for cache busting
- Conditional loading based on template/page
- WP Rocket and WP Engine optimized
- Page-specific CSS/JS files matching template names

### Custom Post Types & Data
- Before/after gallery data stored in `/assets/data/before-after-gallery.json`
- ACF Pro manages flexible content architecture
- Custom queries handle procedure/location/surgeon relationships

## Development Workflow

### File Organization
- Each template has corresponding CSS/JS files in `/assets/css/` and `/assets/js/`
- All functionality is modularized in `/inc/` directory
- No build process required - direct file editing

### Key Functions & Helpers
- `mia_register_asset()` - Register stylesheets/scripts with versioning (inc/enqueue.php:36)
- `mia_get_logo_url()` - Logo handling with fallbacks (inc/template-helpers.php:26)
- `mia_breadcrumbs()` - Breadcrumb navigation system
- Asset versioning uses `filemtime()` for cache busting

### Performance Considerations
- All assets use modification time versioning
- WP Rocket handles caching optimization
- Imagify handles image compression
- File existence checks prevent 404s on missing assets

### Code Conventions
- PHP follows WordPress coding standards
- All includes are relative to theme directory
- Error logging enabled in development (`WP_DEBUG`)
- Direct access prevention in all PHP files
- Extensive inline documentation

## WordPress Environment
- **WordPress Version**: Latest (hosted on WP Engine)
- **Required Plugins**: ACF Pro, Gravity Forms, WP Rocket, Imagify
- **Theme Support**: Post thumbnails, title tags, HTML5, automatic feeds
- **Custom Image Sizes**: Defined in `inc/media-helpers.php`

## Content Management
- ACF Pro handles flexible content fields
- Gravity Forms manages contact/consultation forms
- Custom post types for medical procedures and locations
- JSON data files for complex galleries and datasets