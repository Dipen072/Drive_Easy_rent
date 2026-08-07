# Render.com Deployment Guide — DriveEase (Clean Docker Setup)

This guide provides step-by-step instructions to deploy your `DriveEase` Laravel 12 project to **Render.com** without build errors.

---

## 🚀 Key Improvements in this Setup

1. **Multi-Stage Build**: Automatically compiles frontend assets with Node.js/Vite (`npm run build`) before preparing the PHP 8.2 Apache server.
2. **Fixed Line Endings**: Entrypoint script (`docker-entrypoint.sh`) uses LF line endings to prevent Linux shell binary errors on Render.
3. **Dynamic Port Support**: Apache automatically binds to Render's dynamic `$PORT` environment variable.
4. **Permissions & Upload Folders**: Automatically manages folder permissions for file uploads (`public/upload`) and Laravel cache (`storage`, `bootstrap/cache`).
5. **Auto Database Migration**: Runs `php artisan migrate --force` automatically during app startup if database connection variables are provided.

---

## 📌 Step-by-Step Deployment Instructions

### Step 1: Commit and Push Code to GitHub / GitLab

Run the following commands in your local project terminal:

```bash
git add .
git commit -m "Rebuild clean Render Docker deployment setup"
git push origin main
```

---

### Step 2: Create a New Web Service on Render

1. Open [Render Dashboard](https://dashboard.render.com/).
2. Click **New +** -> Select **Web Service**.
3. Connect your **GitHub / GitLab** repository containing `DriveEase`.

---

### Step 3: Web Service Configuration Settings

- **Name**: `driveease` (or any preferred name)
- **Region**: Choose closest to target users (e.g. Singapore)
- **Branch**: `main` (or `master`)
- **Runtime**: **Docker**
- **Dockerfile Path**: `./Dockerfile`
- **Instance Type**: **Free** (or Starter)

---

### Step 4: Configure Environment Variables

Under **Environment Variables** in Render, add the following required keys:

| Key | Suggested Value | Description |
| :--- | :--- | :--- |
| `APP_ENV` | `production` | Production environment mode |
| `APP_DEBUG` | `false` | Disable debug output |
| `APP_KEY` | `base64:...` | Generate locally via `php artisan key:generate --show` |
| `APP_URL` | `https://your-app.onrender.com` | Your assigned Render service URL |
| `LOG_CHANNEL` | `stderr` | Redirect logs to Render console |
| `DB_CONNECTION` | `mysql` | Database driver |
| `DB_HOST` | `your-db-host.com` | Online MySQL DB Host |
| `DB_PORT` | `3306` | MySQL Port |
| `DB_DATABASE` | `driveease` | Database Name |
| `DB_USERNAME` | `your_db_user` | Database Username |
| `DB_PASSWORD` | `your_db_password` | Database Password |

---

### Step 5: Click "Create Web Service"

Render will perform the following steps automatically:
1. Compile Vite frontend assets (`npm run build`).
2. Build PHP 8.2 runtime image with all required extensions (`pdo_mysql`, `mbstring`, `gd`, `zip`, `bcmath`, `intl`, `opcache`).
3. Set up directory permissions and storage link.
4. Run database migrations (`php artisan migrate --force`).
5. Launch the live website!

---

## 🛠️ Post-Deployment Seeding (Optional)

If you need to seed initial database records (such as default admin user or car categories):
1. Go to your Web Service page on Render Dashboard.
2. Click the **Shell** tab.
3. Run:
```bash
php artisan db:seed --force
```
