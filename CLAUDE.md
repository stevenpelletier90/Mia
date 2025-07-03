# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**MIA Aesthetics v3** is a custom WordPress theme for a multi-location medical aesthetics practice, hosted on WP Engine. This is a professional medical aesthetics website with performance optimization, security focus, and responsive design.

**Current Version**: v3.0

### Design Philosophy
The theme is built on four core principles:
1. **Lightweight Architecture** - "Every byte matters" - Focus on minimal, efficient code
2. **Security-First Approach** - "Fort Knox for websites" - Enterprise-grade security considerations
3. **Mobile-First Design** - "Beautiful on every screen" - Responsive design priority
4. **Performance Obsessed** - "Speed is a feature" - Optimized for fast load times

### Performance Targets
- **Load Time**: < 3 seconds
- **Core Web Vitals**: 90+ score
- **Mobile Speed**: 95+ score
- **Security Score**: A+ rating

### Practice Structure
- **Multi-location practice** with locations across the country
- Each location has one or multiple surgeons
- All surgeons and locations operate under the Mia Aesthetics brand
- Hierarchical relationship: Mia Aesthetics > Locations > Surgeons

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
│   ├── queries.php        # Custom query modifications
│   └── /schema/           # Schema.org structured data classes
│       └── class-clinic-schema.php # Medical clinic/location schema markup
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
- **Primary CPTs**: `surgeon` (post key), `location`, `procedure`, `case`, `condition`, `special`, `fat-transfer`, `non-surgical`
- **Relationships**: Surgeons belong to locations, locations belong to Mia Aesthetics organization
- Before/after gallery data stored in `/assets/data/before-after-gallery.json`
- ACF Pro manages flexible content architecture with custom fields for schema data
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

## CSS Architecture

### CSS Organization & Loading Strategy
The theme implements a modular CSS architecture with performance optimization:

#### File Structure
```
/assets/css/
├── base.css              # Core styles, CSS variables, typography, buttons
├── fonts.css             # @font-face declarations for Inter & Montserrat
├── header.css            # Site header and navigation styles
├── footer.css            # Site footer styles
├── front-page.css        # Homepage-specific styles
├── hero-section.css      # Modular hero section component
├── page-*.css            # Template-specific styles
├── single-*.css          # Single post type styles
├── archive-*.css         # Archive page styles
└── [template-name].css   # Auto-loaded based on template
```

#### CSS Custom Properties (CSS Variables)
Defined in `base.css`, providing consistent theming:
- **Colors**: `--color-primary: #1b1b1b`, `--color-gold: #c8b273`
- **Typography**: `--font-heading: 'Montserrat'`, `--font-body: 'Inter'`
- **Layout**: `--navbar-height: 65px`
- **Z-Index System**: Managed hierarchy from `--z-index-dropdown: 1000` to `--z-index-header: 9999`
- **Component Variables**: Button padding, transitions, footer colors

#### Loading Strategy
1. **Base Styles**: Always loaded in order: `base.css` → `header.css` → `footer.css`
2. **Template-Specific**: Conditionally loaded based on active template (inc/enqueue.php)
3. **Cache Busting**: Uses `filemtime()` for version numbers
4. **Performance**: No build process - optimized for HTTP/2 parallel loading

### Component Styling Patterns

#### Button System (`.mia-button`)
Data-attribute driven variants:
- `data-variant`: gold, gold-outline, hero, white, black
- `data-size`: sm, lg (default: medium)
- Consistent hover states with transitions
- Accessibility-focused with proper contrast ratios

#### Responsive Design
- Mobile-first approach using Bootstrap 5 grid
- Custom breakpoints for component adjustments
- Fluid typography using `clamp()` for scaling
- Container queries for component-level responsiveness

#### Hero Section Pattern
- Modular component in `hero-section.css`
- 16:9 aspect ratio enforcement
- Carousel + sidebar box layout
- Responsive stacking on mobile

### CSS Best Practices
1. **BEM-inspired naming**: `.section-name`, `.section-name__element`
2. **Utility classes**: Following Bootstrap conventions
3. **Scoped styles**: Template-specific styles prefixed appropriately
4. **Performance optimizations**:
   - CSS custom properties for runtime theming
   - Minimal specificity chains
   - GPU-accelerated transforms for animations
   - Critical CSS considerations documented inline

### Typography System
- **Headings**: Montserrat, 700 weight, responsive sizing with `calc()`
- **Body**: Inter, 400 weight, optimized for readability
- **Font Loading**: Self-hosted in `/assets/fonts/` for performance
- **Fluid Typography**: Implemented via `clamp()` and viewport units

### Color System
- Primary brand colors defined as CSS variables
- RGBA variations for opacity states
- Accessibility-compliant contrast ratios
- Consistent hover/focus state transformations

### Form Styling
- Gravity Forms integration with custom overrides
- Consistent input/button styling across forms
- Validation state styles
- Mobile-optimized touch targets

## Schema.org & SEO
### Medical Clinic Schema
- **Class**: `Mia_Aesthetics\Schema\Clinic_Schema` (inc/schema/class-clinic-schema.php)
- **Purpose**: Generates Schema.org compliant MedicalBusiness/MedicalClinic markup for location pages
- **Key Features**:
  - Medical business information (specialty, price range, payment methods)
  - Location data (address, geo coordinates, maps integration)
  - Business hours parsing (handles multiple time formats)
  - Employee relationships (surgeons linked to locations)
  - Video schema integration (YouTube VideoObject markup)
  - Aggregate ratings and reviews
  - Fallback defaults for missing data
- **Integration**: Works with Yoast SEO context, ACF Pro fields, and custom post types
- **Usage**: Automatically applied to `location` post type pages for enhanced search visibility