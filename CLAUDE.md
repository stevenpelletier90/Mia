# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Codebase Overview

This is a custom WordPress theme for Mia Aesthetics, a multi-location plastic surgery practice. The theme is built without a build process - all CSS and JavaScript is hand-written and served directly through WordPress's enqueue system.

## Development Workflow

**No Build Process:** This theme uses direct file editing with WordPress enqueue system for asset management. There are no npm scripts, webpack, or other build tools.

**Key Development Commands:**
- WordPress cache clearing via admin interface or WP-CLI
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
CSS and JS files are conditionally loaded based on page context through `inc/enqueue.php`. The theme uses smart dependency management to only load required assets on specific pages.

### Data Structure
Before/after gallery data is stored in `/assets/data/before-after-gallery.json` and organized by surgeon with procedure categories.

## Important Patterns

**Performance Optimization:**
- Extensive use of wp_cache and transients
- Hero image lazy loading exclusions
- Critical CSS prioritization
- Font preloading

**Medical Practice Features:**
- Multi-surgeon support with individual profiles
- Hierarchical procedure organization
- Location-based content architecture
- Before/after case management

**Code Organization:**
- Modular approach with specialized helper files
- Template hierarchy optimized for custom post types
- Component-based CSS architecture
- Context-aware JavaScript loading