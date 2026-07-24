# EG Computer Solutions & Enterprises

A lightweight static business website for EG Computer Solutions & Enterprises, showcasing computer repair, parts and upgrades, CCTV installation, networking, printer support, and FDAS / door access solutions.

The live website is available at:
- https://egcompsolution.com

## Overview

This repository contains the source files for the company website, including:
- a responsive landing page
- a services page
- a gallery page
- a contact section with a PHP mail handler
- PHPMailer integration for SMTP-based contact delivery

## Tech Stack

- HTML5
- CSS3
- JavaScript

This repository is a purely static website. There is no server-side processing, no PHPMailer integration, and no PHP mail delivery in the current setup.

## Project Structure

- `index.html` — homepage
- `services.html` — services overview page
- `gallery.html` — gallery page
- `styles.css` — main site styling
- `assets/` — images, logo, and site media

Note: legacy PHP files such as `contact.php` and `composer.json` may still exist in the repository but are not part of the active static-site workflow.

## Local Development

### Requirements

- Any basic static file server or local web server such as XAMPP, WAMP, or just a browser with local file access
- A modern browser

### Run Locally

1. Clone the repository.
2. Open the project folder in your local web server document root, such as `htdocs` for XAMPP.
3. Start your local web server.
4. Open the site in your browser:

```text
http://localhost/egcs-site/
```

## Contact

The current website uses a static contact approach. The call-to-action links open the user’s email client rather than submitting to a server-side form handler.

If you want to update the contact details later, edit the content directly in `index.html` and related pages.

## Deployment Notes

This site can be deployed on any PHP-enabled hosting environment.

Recommended deployment checklist:
- confirm the `assets/` images load correctly
- verify the contact form sends mail successfully
- test the site on mobile and desktop widths
- confirm all anchor links and pages work correctly

## License

This project is for the EG Computer Solutions & Enterprises website and is intended for internal repository use unless otherwise stated by the business owner.

## Maintainer

EG Computer Solutions & Enterprises
