EG Computer Solutions & Enterprises — Static Website

Files:
- index.html — homepage
- styles.css — site styles
- assets/logo.svg — simple logo SVG

Preview locally:

Using Python 3 (from the `egcs-site` folder):

```bash
python -m http.server 8000
```

Then open http://localhost:8000 in your browser.

Contact form:

- This release includes a simple server-side PHP handler at `contact.php` that:
  - validates and sanitizes form input,
  - attempts to send mail with PHP `mail()`,
  - logs submissions to `data/submissions.csv`.
- Update the destination email inside `contact.php` (`$to` variable) to your real inbox.
- On XAMPP you may need to configure `php.ini` / sendmail settings for `mail()` to work, or use an SMTP/email API instead.

Next steps you might want:
- Provide your real contact details and logo image in `assets/`.
- I can replace the `mail()` call with SMTP (PHPMailer) or integrate services like SendGrid for reliable delivery.
