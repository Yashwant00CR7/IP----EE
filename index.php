<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Repo Fetcher</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f0f2f5;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #1a1a1a;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        input[type="text"] {
            width: 300px;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #0366d6;
        }

        button {
            padding: 1rem 2rem;
            background: #0366d6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0356b6;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .repo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h4 {
            color: #0366d6;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .card p {
            margin-bottom: 0.5rem;
            color: #555;
        }

        .card a {
            display: inline-block;
            margin-top: 1rem;
            color: #0366d6;
            text-decoration: none;
            font-weight: 500;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .stats {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #586069;
        }

        .export-btn {
            background: #28a745;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .export-btn:hover {
            background: #22863a;
        }

        .error-message {
            text-align: center;
            color: #dc3545;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            margin: 2rem auto;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>GitHub Repository Explorer <i class="fab fa-github"></i></h2>
        
        <form method="POST" action="" class="search-form">
            <input type="text" name="username" required placeholder="Enter GitHub username...">
            <button type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = htmlspecialchars($_POST['username']);
            $url = "https://ip-ee.onrender.com/github/". urlencode($username);

            $json = file_get_contents($url);
            $data = json_decode($json, true);

            if (isset($data["projects"])) {
                require 'db.php';

                echo '<div class="results-header">';
                echo '<h3>Showing ' . count($data["projects"]) . ' repositories for <span style="color: #0366d6;">' . $username . '</span></h3>';
                echo '<form method="post" action="export_pdf.php">';
                echo '<input type="hidden" name="data" value=\'' . json_encode($data["projects"]) . '\'>';
                echo '<button type="submit" class="export-btn">';
                echo '<i class="fas fa-file-pdf"></i> Export PDF';
                echo '</button>';
                echo '</form>';
                echo '</div>';

                echo '<div class="repo-grid">';
                foreach ($data["projects"] as $project) {
                    $repo_name = $project["repo_name"];
                    $tech_stack = implode(", ", $project["tech_stack"]);
                    $primary_language = $project["primary_language"];
                    $stars = $project["stars"];
                    $forks = $project["forks"];
                    $repo_url = $project["repo_url"];

                    echo "<div class='card'>
                            <h4><i class='fas fa-book'></i> $repo_name</h4>
                            <p><strong>Primary Language:</strong> $primary_language</p>
                            <p><strong>Tech Stack:</strong> $tech_stack</p>
                            <div class='stats'>
                                <div class='stat-item'>
                                    <i class='fas fa-star'></i>
                                    <span>$stars</span>
                                </div>
                                <div class='stat-item'>
                                    <i class='fas fa-code-branch'></i>
                                    <span>$forks</span>
                                </div>
                            </div>
                            <a href='$repo_url' target='_blank'>
                                <i class='fas fa-external-link-alt'></i> View Repository
                            </a>
                          </div>";

                    // Insert into MySQL (your existing code)
                    $stmt = $conn->prepare("INSERT INTO repos (username, repo_name, tech_stack, primary_language, stars, forks, repo_url)
                                            VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssiis", $username, $repo_name, $tech_stack, $primary_language, $stars, $forks, $repo_url);
                    $stmt->execute();
                }

                $conn->close();
                echo '</div>';
            } else {
                echo '<div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>No repositories found or error in fetching data.</p>
                      </div>';
            }
        }
        ?>
    </div>
</body>
</html>