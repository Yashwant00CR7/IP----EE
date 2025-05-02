<?php
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projects = json_decode($_POST['data'], true);

    $html = "<h2>GitHub Project Report</h2>";
    foreach ($projects as $p) {
        $html .= "<hr>
                  <h3>{$p['repo_name']}</h3>
                  <p><strong>Primary Language:</strong> {$p['primary_language']}</p>
                  <p><strong>Tech Stack:</strong> " . implode(", ", $p['tech_stack']) . "</p>
                  <p><strong>Stars:</strong> {$p['stars']} | <strong>Forks:</strong> {$p['forks']}</p>
                  <p><a href='{$p['repo_url']}'>View Repo</a></p>";
    }

    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("github_report.pdf", array("Attachment" => true));
}
?>
