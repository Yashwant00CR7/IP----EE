# 👤 GitHub Profile Fetcher & PDF Exporter using PHP

A dynamic PHP-based web application that fetches **GitHub user profiles** and their **public repositories**, with an option to **export the results as a PDF** using `dompdf`.

This project showcases API integration, data rendering, and document generation — ideal for developers looking to learn PHP and GitHub API usage.

---

## 🧰 Tech Stack

- **Frontend**: HTML, CSS (with PHP)
- **Backend**: PHP
- **API**: GitHub REST API v3
- **PDF Generator**: [dompdf](https://github.com/dompdf/dompdf)
- **Hosting**: Heroku (based on `Procfile`)
- **Database (Optional)**: `db.php` included, suggest MySQL for user logging (if used)

---

## 📁 Project Structure

```bash
.
├── index.php          # Main page for searching GitHub users
├── export_pdf.php     # Generates PDF using dompdf
├── db.php             # (Optional) DB connection (e.g., for logs)
├── .env               # Environment variables (e.g., DB credentials)
├── .gitignore         # Files to ignore in Git
├── Procfile           # For Heroku deployment
├── requirements.txt   # Required libraries for deployment
├── app.py             # Unused? Possibly for future Python integration
└── README.md
