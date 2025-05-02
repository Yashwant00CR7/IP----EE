<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GitHub Repo Fetcher</title>
    <style>
        .card {
            border: 1px solid #ccc; padding: 15px;
            margin: 10px; border-radius: 10px;
            width: 300px; display: inline-block;
            vertical-align: top; background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Enter GitHub Username</h2>
    <form method="POST" action="">
        <input type="text" name="username" required>
        <button type="submit">Get Repos</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = htmlspecialchars($_POST['username']);
        $url = "http://127.0.0.1:5000/github/". urlencode($username); // Local Flask API or dummy data

        $json = file_get_contents($url);
        $data = json_decode($json, true);

        if (isset($data["projects"])) {
            require 'db.php';

            echo "<h3>Found " . count($data["projects"]) . " projects for <b>$username</b></h3>";
            echo '<form method="post" action="export_pdf.php">';
            echo '<input type="hidden" name="data" value=\'' . json_encode($data["projects"]) . '\'>';
            echo '<button type="submit">Export to PDF</button>';
            echo '</form><br>';

            foreach ($data["projects"] as $project) {
                $repo_name = $project["repo_name"];
                $tech_stack = implode(", ", $project["tech_stack"]);
                $primary_language = $project["primary_language"];
                $stars = $project["stars"];
                $forks = $project["forks"];
                $repo_url = $project["repo_url"];

                echo "<div class='card'>
                        <h4>$repo_name</h4>
                        <p><strong>Primary Language:</strong> $primary_language</p>
                        <p><strong>Tech Stack:</strong> $tech_stack</p>
                        <p><strong>Stars:</strong> $stars | <strong>Forks:</strong> $forks</p>
                        <a href='$repo_url' target='_blank'>View on GitHub</a>
                      </div>";

                // Insert into MySQL
                $stmt = $conn->prepare("INSERT INTO repos (username, repo_name, tech_stack, primary_language, stars, forks, repo_url)
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssiis", $username, $repo_name, $tech_stack, $primary_language, $stars, $forks, $repo_url);
                $stmt->execute();
            }

            $conn->close();
        } else {
            echo "<p>No projects found or error in fetching data.</p>";
        }
    }
    ?>
</body>
</html>
