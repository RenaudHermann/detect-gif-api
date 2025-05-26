<?php
header('Content-Type: application/json');
function is_animated_gif($url) {
    $content = @file_get_contents($url);
    if (!$content) return false;
    if (substr($content, 0, 6) !== "GIF89a" && substr($content, 0, 6) !== "GIF87a") return false;
    return preg_match_all('#\x00\x21\xF9\x04#', $content, $m) > 1;
}
$input = json_decode(file_get_contents('php://input'), true);
$urls = $input['imageUrls'] ?? [];
$animated = false;
foreach ($urls as $url) {
    if (is_animated_gif($url)) {
        $animated = true;
        break;
    }
}
echo json_encode([
    'score' => $animated ? 0 : 1,
    'animated' => $animated,
]);