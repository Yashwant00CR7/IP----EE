# 👤 GitHub Profile Fetcher & PDF Exporter

A PHP web app that fetches GitHub user profiles and their public repositories, with one-click PDF export using `dompdf`.

**[Try it live →](https://github-project-explorer.infinityfreeapp.com/?i=1)**

---

## 🧰 Tech Stack

- **Backend**: PHP
- **API**: GitHub REST API v3
- **PDF**: [dompdf](https://github.com/dompdf/dompdf)
- **Frontend**: HTML + CSS
- **Hosting**: Heroku

---

## ✨ Features

- Search any GitHub username and view their public profile
- Lists all public repositories with metadata
- Export the full profile + repo list as a formatted PDF
- Optional MySQL logging via `db.php`

---

## 🚀 Setup

### Prerequisites
- PHP 7.4+
- Composer

### Installation

```bash
git clone https://github.com/Yashwant00CR7/IP----EE.git
cd IP----EE
composer install
```

Configure your GitHub token (optional, increases API rate limit):
```bash
cp .env.example .env
# Add GITHUB_TOKEN=your_token to .env
```

Run locally:
```bash
php -S localhost:8000
```

Open `http://localhost:8000`.

---

## 📁 Project Structure

```
├── index.php          # Main search page
├── export_pdf.php     # PDF generation via dompdf
├── db.php             # Optional MySQL connection
├── .env               # Environment variables
├── Procfile           # Heroku deployment config
└── requirements.txt   # Deployment dependencies
```

---

## 📄 License

MIT License

---

**Built by [Yashwant K](https://github.com/Yashwant00CR7)**
