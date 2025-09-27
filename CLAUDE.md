# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo/Image is a PHP image manipulation library that provides an object-oriented wrapper around PHP's GD extension. The library supports image resizing, cropping, filtering, watermarking, and text overlays with support for GIF, JPEG, PNG, and WEBP formats.

## Core Architecture

### Main Classes Structure
- `src/Image.php` - Main Image class that handles image loading, manipulation, and saving
- `src/Filter.php` - Contains image filter constants and filter application logic
- `src/Text.php` - Handles text rendering on images with font support
- `src/Exception.php` - Custom exception class for image operations

### Key Dependencies
- **PHP Extensions**: `ext-gd`, `ext-exif`, `ext-ctype` (required for image operations)
- **JBZoo Libraries**: `jbzoo/utils` (utilities), `jbzoo/data` (data handling)
- **Dev Tooling**: `jbzoo/toolbox-dev` (provides development tools via Makefile)

## Development Commands

### Setup and Dependencies
```bash
make update                # Install/update all dependencies
```

### Testing
```bash
make test                  # Run PHPUnit tests
make test-all              # Run all tests and code quality checks
```

### Code Quality
```bash
make codestyle             # Run all linters and code style checks
```

### Reporting
```bash
make report-all            # Generate all project reports
make report-coveralls      # Upload coverage to Coveralls
```

## Testing Structure

Tests are located in `tests/` directory with the following organization:
- `ImageTest.php` - Core image manipulation tests
- `FilterTest.php` - Image filter functionality tests
- `TextTest.php` - Text overlay functionality tests
- `ResizeTest.php` - Image resizing tests
- `WatermarkTest.php` - Watermark overlay tests
- `TransformsTest.php` - Image transformation tests
- `tests/resources/` - Test images and fonts
- `tests/expected/` - Expected test output images

The project uses PHPUnit with image comparison testing - many tests generate images and compare them against expected outputs.

## Image Class Usage Patterns

### Image Loading
The Image class accepts multiple input formats:
- File paths
- Base64 data URIs
- Raw binary data
- GD image resources

### Method Chaining
All manipulation methods return `$this` to enable fluent interface:
```php
$img = (new Image('./source.jpg'))
    ->addFilter('grayscale')
    ->resize(320, 240)
    ->saveAs('./output.png');
```

### Filter System
Filters are applied via `addFilter()` method with filter name and parameters. The Filter class contains constants for filter types (blur types, etc.).

## CI/CD Configuration

The project uses GitHub Actions with three main jobs:
- **PHPUnit**: Runs tests across PHP 8.2, 8.3, 8.4 with different composer flags
- **Linters**: Runs code quality checks across PHP versions
- **Reports**: Generates coverage and static analysis reports

The Makefile integrates with `jbzoo/toolbox-dev` which provides standardized development commands across JBZoo projects.