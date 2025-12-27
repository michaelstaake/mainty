# Docker Setup for Mainty

## Quick Start

### Build and Run

```bash
# Build the Docker image
docker-compose build

# Start the container
docker-compose up -d

# View logs
docker-compose logs -f
```

The application will be available at: **http://localhost:8080**

### Stop the Application

```bash
docker-compose down
```

## What's Included

- **PHP 8.4** with Apache
- **SQLite** with PDO extension
- **mod_rewrite** enabled for pretty URLs
- **Persistent data** volume for the database

## Configuration

### Change Port

Edit `docker-compose.yml` and modify the ports section:

```yaml
ports:
  - "3000:80"  # Access on port 3000 instead
```

### Development Mode

To sync code changes without rebuilding, uncomment this line in `docker-compose.yml`:

```yaml
volumes:
  - ./data:/var/www/html/data
  - .:/var/www/html  # Uncomment this line
```

Then restart:
```bash
docker-compose restart
```

## Data Persistence

The SQLite database is stored in the `./data` directory on your host machine, which is mounted to the container. This means:

- ✅ Your data persists even if you stop/remove the container
- ✅ You can backup by copying the `data` folder
- ✅ Database survives container rebuilds

## Useful Commands

```bash
# Rebuild after code changes
docker-compose up -d --build

# View container logs
docker-compose logs -f mainty

# Access container shell
docker-compose exec mainty bash

# Stop and remove everything
docker-compose down -v

# Check container status
docker-compose ps
```

## Troubleshooting

### Permission Issues

If you get permission errors with the database:

```bash
chmod -R 755 data
```

### Port Already in Use

If port 8080 is already in use, change it in `docker-compose.yml`:

```yaml
ports:
  - "8081:80"  # Use a different port
```

### Reset Everything

To start fresh:

```bash
docker-compose down -v
rm -rf data/mainty.db
docker-compose up -d
```

Then access http://localhost:8080 to run setup again.

## Production Deployment

For production:

1. Set `DEBUG` to `false` in `config.php`
2. Use environment variables for sensitive data
3. Consider using a reverse proxy (nginx) in front
4. Enable HTTPS

Example production `docker-compose.yml`:

```yaml
services:
  mainty:
    build: .
    container_name: mainty-app
    ports:
      - "8080:80"
    volumes:
      - ./data:/var/www/html/data
      # Uncomment below to sync code changes in development
      # - .:/var/www/html
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html
    restart: unless-stopped
```
