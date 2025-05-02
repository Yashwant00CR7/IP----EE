from flask import Flask, request, jsonify
from pymongo import MongoClient
import requests
from collections import defaultdict
from bson import ObjectId
from urllib.parse import urlparse
from dotenv import load_dotenv
import os

app = Flask(__name__)

# Load GitHub token from .env file
load_dotenv()
GITHUB_TOKEN = os.environ.get("GITHUB_TOKEN")
HEADERS = {"Authorization": f"token {GITHUB_TOKEN}"}

# GitHub API utility functions
def get_user_repos(username):
    url = f"https://api.github.com/users/{username}/repos"
    response = requests.get(url, headers=HEADERS)
    try:
        data = response.json()
        if isinstance(data, list):
            return data
        else:
            print("Unexpected repo response:", data)
            return []
    except Exception as e:
        print("Error parsing GitHub repos:", str(e))
        return []

def get_repo_languages(username, repo_name):
    url = f"https://api.github.com/repos/{username}/{repo_name}/languages"
    response = requests.get(url, headers=HEADERS)
    if response.status_code == 200:
        return list(response.json().keys())
    return []

# API route to get details of all repos for a given GitHub username
@app.route("/github/<username>", methods=["GET"])
def get_github_summary(username):
    repos = get_user_repos(username)
    if not repos:
        return jsonify({"error": "No repositories found or user not found"}), 404

    summarized_data = []
    for repo in repos:
        summary = {
            "repo_name": repo.get("name"),
            "tech_stack": get_repo_languages(username, repo.get("name")),
            "primary_language": repo.get("language"),
            "stars": repo.get("stargazers_count"),
            "forks": repo.get("forks_count"),
            "repo_url": repo.get("html_url"),
        }
        summarized_data.append(summary)

    return jsonify({
        "username": username,
        "total_repos": len(summarized_data),
        "projects": summarized_data
    }), 200


if __name__ == "__main__":
    app.run(debug=True)
