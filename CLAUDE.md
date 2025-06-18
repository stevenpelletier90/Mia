# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Codebase Overview

This is a custom WordPress theme for Mia Aesthetics, a multi-location plastic surgery practice. The theme is built without a build process - all CSS and JavaScript is hand-written and served directly through WordPress's enqueue system.

## Development Workflow

**No Build Process:** This theme uses direct file editing with WordPress enqueue system for asset management. There are no npm scripts, webpack, or other build tools.

**Key Development Commands:**
- Asset cache busting: File modification times are used automatically via `inc/enqueue.php`
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
- `fat-transfer` - Fat transfer procedures

### Template System Architecture

**Flexible Template System:** Posts can use different templates regardless of post type through WordPress's Template dropdown.

**Template Categories:**

1. **Fixed Post Types (Always Use Dedicated Templates):**
   - `condition` → `single-condition.php` → `page-condition-layout.css/js`
   - `surgeon` → `single-surgeon.php` → `single-surgeon.css/js`
   - `location` → `single-location.php` → `single-location.css/js`
   - `case` → `single-case.php` → `single-case.css/js`
   - `special` → `single-special.php` → `single-special.css/js`

2. **Flexible Post Types (Require Template Selection):**
   - `procedure` - Must select template from available options
   - `non-surgical` - Must select template from available options
   - `fat-transfer` - Must select template from available options

3. **Available Selectable Templates:**
   - `page-treatment-layout.php` - Unified treatment template for procedures/non-surgical/fat-transfer
   - `page-condition-layout.php` - Condition-style layout (available for flexible post types)
   - `page-hero-canvas.php` - Hero-style layout
   - `page-blank-canvas.php` - Minimal custom layout
   - `page-before-after-by-doctor.php` - Gallery template (case-specific)
   - `page-case-category.php` - Case category template (case-specific)

### File Organization
- `/inc/` - Modular functionality helpers (12 specialized files)
- `/assets/css/` - Template-specific CSS files matching PHP template names exactly
- `/assets/js/` - Template-specific JavaScript files matching PHP template names exactly
- Template files follow WordPress hierarchy with custom post types

**Naming Convention:** Template files and their assets must match exactly:
- `page-template-name.php` → `page-template-name.css` + `page-template-name.js`
- `single-posttype.php` → `single-posttype.css` + `single-posttype.js`
- `archive-posttype.php` → `archive-posttype.css` + `archive-posttype.js`

### Key Dependencies
- **Advanced Custom Fields (ACF)** - Critical for all custom content
- **Gravity Forms** - Contact forms and lead capture
- Bootstrap 5 and Font Awesome 6 included as complete libraries
- Performance-first approach with template-aware asset loading

### Asset Management

**Template-Based Loading System** in `inc/enqueue.php`:
- **Template Detection:** `mia_detect_template_key()` prioritizes user template selection over post type
- **Context Mappings:** `mia_get_template_mappings()` maps templates to their CSS/JS files
- **Critical Resource Loading:** Fonts, base CSS, and Bootstrap loaded with high priority
- **Automatic Cache Busting:** File modification times used for versioning (`filemtime()`)
- **Performance Optimizations:** Script deferring, resource hints

**Key Functions:**
- `mia_detect_template_key()` - Template detection with user selection priority
- `mia_get_template_mappings()` - Single source of truth for template→asset mapping
- `mia_enqueue_assets()` - Main asset loading function

**Asset Loading Priority:**
1. User Selected Template (highest priority)
2. Default Template for Post Type
3. Generic Fallback Template

### Consolidated Assets

**Shared Template Assets:**
- **Treatment Layout:** `procedure`, `non-surgical` → both use `page-treatment-layout.css/js`
- **Condition Layout:** `condition`, `fat-transfer` singles → both use `page-condition-layout.css/js`
- **Fat-Transfer Archives:** Use condition layout assets (`page-condition-layout.css/js`) instead of archive-specific

### Data Structure
Before/after gallery data is stored in `/assets/data/before-after-gallery.json` and organized by surgeon with procedure categories.

## Important Patterns

**Template Selection System:**
- **Fixed post types** cannot select templates - always use dedicated single templates
- **Flexible post types** must select templates via WordPress Template dropdown
- **Template assets** are loaded based on selected template, not post type
- **ACF fields** can be associated with templates or post types via WordPress admin

**Performance Optimization:**
- **Caching Strategy:** `wp_cache` with 2-hour expiration for post counts and site stats (`inc/cache-helpers.php`)
- **Query Optimization:** `update_post_meta_cache` and `update_post_term_cache` disabled when not needed (`inc/queries.php`)
- **Asset Optimization:** Template-specific loading, script deferring, resource hints
- **Static Caching:** Template detection cached per request

**Medical Practice Features:**
- **Schema.org Integration:** Comprehensive medical schema (procedures, surgeons, locations) complementing Yoast SEO
- **Custom Query Modifications:** Archive-specific ordering and pagination (`inc/queries.php`)
- **Multi-location Architecture:** Hierarchical content with location-based filtering
- **ACF Integration:** All custom content managed through Advanced Custom Fields

**Code Organization:**
- **Modular Helper System:** 12 specialized files in `/inc/` handle specific functionality areas
- **Template-Driven Loading:** Assets loaded based on template selection rather than post type
- **Semantic HTML:** Proper use of HTML5 semantic elements (avoid nested headers)
- **Function Naming Convention:** All theme functions prefixed with `mia_`

**Key Helper Files:**
- `inc/enqueue.php` - Template-based asset management and detection
- `inc/queries.php` - Query modifications and archive behaviors  
- `inc/schema.php` - Medical schema.org structured data
- `inc/cache-helpers.php` - Performance caching utilities

## CSS and Form Integration

**Gravity Forms Integration:**
- Forms located in `.consultation-card` containers within header sections
- CSS targets `.consultation-card .gform_wrapper` hierarchy
- Styled for dark gradient background with custom field styling
- Field-specific targeting using actual Gravity Form field IDs

**CSS Optimization:**
- Template CSS files cleaned of unused selectors
- All selectors match actual HTML structure in templates
- Responsive design with mobile-first approach
- Performance optimized with minimal unused code

## Important Notes

**Template System Rules:**
- Never create default `single-procedure.php` or `single-non-surgical.php` templates
- Flexible post types must use template selection system
- Asset naming must match template names exactly
- Remove unused/orphaned CSS and JS files regularly

**ACF Field Management:**
- Custom fields can be associated with specific post types
- Custom fields can be associated with specific templates
- User controls field visibility via WordPress admin interface
- Template selection overrides post-type-specific styling