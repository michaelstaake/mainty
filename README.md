# Mainty

A simple PHP web app for tracking vehicle maintenance records. Free, simple, open source, and self-hosted. Runs on any Apache/PHP web server, or use Docker. Uses SQLite for easy backup, with built-in Export via JSON or HTML so you can import that data into something else or print records for your mechanic or the next owner of your vehicle.

## Requirements

- Apache web server
- PHP 8 or higher
- SQLite extension

## Installation

### Option 1: Traditional Web Server

1. Upload the entire folder to your web server
2. Rename `example.htaccess` to `.htaccess`
3. If the app is not in the root directory, edit `.htaccess` and set the `RewriteBase`:
   ```apache
   RewriteBase /subfolder/
   ```
4. Navigate to the app URL in your browser
5. If everything is configured correctly, you'll see the setup page
6. Set your password to initialize the database

### Option 2: Docker

```bash
docker-compose up -d
```

Then open http://localhost:8080

## First Time Setup

When you first access the app, you'll be prompted to:
1. Create a password
2. Initialize the database

That's it! You're ready to start tracking your vehicle maintenance.
