# Render.com Deployment Guide — DriveEase Laravel

Follow these steps to deploy your `DriveEase` Laravel project live on Render.com using Docker.

---

## 1. Prerequisites
1. A **GitHub** or **GitLab** account with your `DriveEase` repository pushed.
2. A **Render.com** free or paid account.
3. A MySQL / MariaDB database hosted online (e.g. Render MySQL, PlanetScale, Aiven, Railway, or Supabase).

---

## 2. Steps to Deploy on Render

### Step 1: Push Project to GitHub / GitLab
Make sure all newly created Docker files are committed and pushed:
```bash
git add .
git commit -m "Add Dockerfile and Render deployment setup"
git push origin main
```

---

### Step 2: Create a New Web Service on Render
1. Log in to [Render Dashboard](https://dashboard.render.com/).
2. Click **New +** button -> Select **Web Service**.
3. Connect your **GitHub / GitLab** repository (`DriveEase`).

---

### Step 3: Configure Web Service Settings
- **Name**: `driveease` (or any custom name)
- **Region**: Choose closest to India (e.g. Singapore)
- **Branch**: `main` (or `master`)
- **Runtime / Environment**: **Docker**
- **Dockerfile Path**: `./Dockerfile`
- **Instance Type**: Free (or Starter)

---

### Step 4: Set Environment Variables (Crucial)
Under **Environment Variables** section in Render, add the following keys:

| Key | Example Value | Description |
| :--- | :--- | :--- |
| `APP_ENV` | `production` | Production environment |
| `APP_DEBUG` | `false` | Disable debug mode |
| `APP_KEY` | `base64:...` | Generate using `php artisan key:generate --show` |
| `APP_URL` | `https://your-app.onrender.com` | Render live URL |
| `DB_CONNECTION` | `mysql` | Database driver |
| `DB_HOST` | `your-db-host.com` | Database Host URL |
| `DB_PORT` | `3306` | Database Port |
| `DB_DATABASE` | `driveease` | Database Name |
| `DB_USERNAME` | `db_user` | Database User |
| `DB_PASSWORD` | `db_password` | Database Password |

---

### Step 5: Click "Create Web Service"
Render will automatically:
1. Build the Docker image from `Dockerfile`.
2. Install PHP dependencies via Composer.
3. Set permissions & run migrations automatically via `docker-entrypoint.sh`.
4. Deploy your website live!

---

## 3. Post-Deployment Database Seeding (Optional)
If you need to seed initial database records, open the **Shell** tab on Render Dashboard for your service and run:
```bash
php artisan db:seed --force
```
