<?php
include 'check_admin.php';
include '../config/db_connect.php';
include '../config/helpers.php';
if (!isset($_GET['confirm']) || $_GET['confirm'] !== '1') {
    echo "Chạy chuẩn hóa: /admin/maintenance_images.php?confirm=1";
    exit;
}
$q1 = "UPDATE hinh_anh_nuoc_hoa SET duong_dan_hinh = CASE WHEN duong_dan_hinh REGEXP '^(http|https)://' THEN duong_dan_hinh WHEN duong_dan_hinh LIKE '%/duan1/img/%' THEN SUBSTRING(duong_dan_hinh, LOCATE('img/', duong_dan_hinh)) WHEN duong_dan_hinh LIKE '/img/%' THEN SUBSTRING(duong_dan_hinh, 2) WHEN duong_dan_hinh LIKE 'public/img/%' THEN SUBSTRING(duong_dan_hinh, LENGTH('public/') + 1) WHEN duong_dan_hinh LIKE './img/%' THEN SUBSTRING(duong_dan_hinh, 3) WHEN duong_dan_hinh LIKE '../img/%' THEN SUBSTRING(duong_dan_hinh, 4) ELSE duong_dan_hinh END";
$q1b = "UPDATE hinh_anh_nuoc_hoa SET duong_dan_hinh = REPLACE(REPLACE(REPLACE(REPLACE(duong_dan_hinh, './img/', 'img/'), '../img/', 'img/'), '/duan1/img/', 'img/'), 'public/img/', 'img/') WHERE duong_dan_hinh LIKE './img/%' OR duong_dan_hinh LIKE '../img/%' OR duong_dan_hinh LIKE '%/duan1/img/%' OR duong_dan_hinh LIKE 'public/img/%'";
$q2 = "UPDATE slider SET Thumbnail = CASE WHEN Thumbnail REGEXP '^(http|https)://' THEN Thumbnail WHEN Thumbnail LIKE '%/duan1/img/%' THEN SUBSTRING(Thumbnail, LOCATE('img/', Thumbnail)) WHEN Thumbnail LIKE '/img/%' THEN SUBSTRING(Thumbnail, 2) WHEN Thumbnail LIKE 'public/img/%' THEN SUBSTRING(Thumbnail, LENGTH('public/') + 1) WHEN Thumbnail LIKE './img/%' THEN SUBSTRING(Thumbnail, 3) WHEN Thumbnail LIKE '../img/%' THEN SUBSTRING(Thumbnail, 4) ELSE Thumbnail END";
$q2b = "UPDATE slider SET Thumbnail = REPLACE(REPLACE(REPLACE(REPLACE(Thumbnail, './img/', 'img/'), '../img/', 'img/'), '/duan1/img/', 'img/'), 'public/img/', 'img/') WHERE Thumbnail LIKE './img/%' OR Thumbnail LIKE '../img/%' OR Thumbnail LIKE '%/duan1/img/%' OR Thumbnail LIKE 'public/img/%'";
$q3 = "UPDATE danh_muc SET hinh_anh = CASE WHEN hinh_anh REGEXP '^(http|https)://' THEN hinh_anh WHEN hinh_anh LIKE '%/duan1/img/%' THEN SUBSTRING(hinh_anh, LOCATE('img/', hinh_anh)) WHEN hinh_anh LIKE '/img/%' THEN SUBSTRING(hinh_anh, 2) WHEN hinh_anh LIKE 'public/img/%' THEN SUBSTRING(hinh_anh, LENGTH('public/') + 1) WHEN hinh_anh LIKE './img/%' THEN SUBSTRING(hinh_anh, 3) WHEN hinh_anh LIKE '../img/%' THEN SUBSTRING(hinh_anh, 4) ELSE hinh_anh END";
$q3b = "UPDATE danh_muc SET hinh_anh = REPLACE(REPLACE(REPLACE(REPLACE(hinh_anh, './img/', 'img/'), '../img/', 'img/'), '/duan1/img/', 'img/'), 'public/img/', 'img/') WHERE hinh_anh LIKE './img/%' OR hinh_anh LIKE '../img/%' OR hinh_anh LIKE '%/duan1/img/%' OR hinh_anh LIKE 'public/img/%'";
$q4 = "UPDATE nguoi_dung SET anh_dai_dien = CASE WHEN anh_dai_dien REGEXP '^(http|https)://' THEN anh_dai_dien WHEN anh_dai_dien LIKE '%/duan1/img/%' THEN SUBSTRING(anh_dai_dien, LOCATE('img/', anh_dai_dien)) WHEN anh_dai_dien LIKE '/img/%' THEN SUBSTRING(anh_dai_dien, 2) WHEN anh_dai_dien LIKE 'public/img/%' THEN SUBSTRING(anh_dai_dien, LENGTH('public/') + 1) WHEN anh_dai_dien LIKE './img/%' THEN SUBSTRING(anh_dai_dien, 3) WHEN anh_dai_dien LIKE '../img/%' THEN SUBSTRING(anh_dai_dien, 4) ELSE anh_dai_dien END";
$q4b = "UPDATE nguoi_dung SET anh_dai_dien = REPLACE(REPLACE(REPLACE(REPLACE(anh_dai_dien, './img/', 'img/'), '../img/', 'img/'), '/duan1/img/', 'img/'), 'public/img/', 'img/') WHERE anh_dai_dien LIKE './img/%' OR anh_dai_dien LIKE '../img/%' OR anh_dai_dien LIKE '%/duan1/img/%' OR anh_dai_dien LIKE 'public/img/%'";
$queries = [$q1,$q1b,$q2,$q2b,$q3,$q3b,$q4,$q4b];
$labels = ['hinh_anh_nuoc_hoa_case','hinh_anh_nuoc_hoa_replace','slider_case','slider_replace','danh_muc_case','danh_muc_replace','nguoi_dung_case','nguoi_dung_replace'];
for ($i=0; $i<count($queries); $i++) {
    $ok = mysqli_query($conn, $queries[$i]);
    if ($ok) {
        echo $labels[$i] . ': ' . mysqli_affected_rows($conn) . "\n";
    } else {
        echo $labels[$i] . ' ERROR: ' . mysqli_error($conn) . "\n";
    }
}
mysqli_close($conn);
?>
