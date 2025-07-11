<?php
// File: includes/site_config.php

if (!isset($conn) || !$conn) {
    // __DIR__ adalah path absolut ke folder saat ini (includes), jadi lebih aman
    require_once __DIR__ . '/db_connect.php';
}

$settings_result = $conn->query("SELECT setting_name, setting_value FROM site_settings");
$settings = [];
if ($settings_result) {
    while ($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}
