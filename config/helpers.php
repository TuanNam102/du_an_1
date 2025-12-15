<?php
function base_url() { return '/de/duan1/'; }
function url($path) {
    if (!$path) return base_url();
    $path = trim($path);
    if (preg_match('/^https?:\/\//', $path)) return $path;
    $p = ltrim($path, '/');
    return base_url() . $p;
}
function asset($path) {
    $base = '/de/duan1';
    if (!$path) return $base . '/public/img/placeholder.png';
    $path = trim($path);
    if (preg_match('/^https?:\/\//', $path)) return $path;
    $p = ltrim($path, '/');
    if (strpos($p, './') === 0) { $p = substr($p, 2); }
    if (strpos($p, '../') === 0) { $p = substr($p, 3); }
    $pos = strpos($p, 'duan1/img/');
    if ($pos !== false) { $p = substr($p, $pos + strlen('duan1/')); }
    if (strpos($p, 'public/') === 0) return $base . '/' . $p;
    return $base . '/public/' . $p;
}
?>
