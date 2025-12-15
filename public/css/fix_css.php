<?php
$file = 'e:/laragon/www/de/duan1/public/css/style.css';
$lines = file($file);
$totalLines = count($lines);

// Keep lines 0-322 (1-323 in 1-indexed) and lines 523-end (524+ in 1-indexed)
$newLines = array_merge(
    array_slice($lines, 0, 323),  // Lines 1-323
    array_slice($lines, 523)       // Lines 524 onwards
);

file_put_contents($file, implode('', $newLines));
echo "Done! Removed " . ($totalLines - count($newLines)) . " duplicate lines.";
