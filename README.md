# Mainty

A simple PHP web app for tracking vehicle maintenance records. Free, simple, open source, and self-hosted. Use Docker, or host it on any Apache/PHP web server. Uses SQLite for easy backup, with built-in Export via JSON or HTML so you can import that data into something else or print records for your mechanic or the next owner of your vehicle.

## Requirements

- Apache web server
- PHP 8 or higher
- SQLite extension

## Installation

### Option 1: Docker

```bash
docker-compose up -d
```

Then open http://localhost:8080

### Option 2: Traditional Web Server

1. Upload the entire folder to your web server
2. Rename `example.htaccess` to `.htaccess`
3. If the app is not in the root directory, edit `.htaccess` and set the `RewriteBase`:
   ```apache
   RewriteBase /subfolder/
   ```
4. Navigate to the app URL in your browser
5. If everything is configured correctly, you'll see the setup page
6. Set your password to initialize the database

## Need help? Want to learn more? Go to the official GitHub!

https://github.com/michaelstaake/mainty
