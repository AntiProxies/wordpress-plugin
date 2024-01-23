<?php
/*
Plugin Name: AntiProxies protect
Description: Limit access to your website to only allow visitors from specific countries to view your website content.
Version: 1.0
Author: AntiProxies
*/
// Add admin menu
add_action('admin_menu', 'csv_importer_menu');

function csv_importer_menu()
{
    add_menu_page('CSV Importer', 'CSV Importer', 'manage_options', 'csv-importer', 'csv_importer_page');
}

// Display imported data
function csv_importer_page()
{
    global $ban_list_data, $whitelist_data;

    echo '<div class="wrap">';
    echo '<h2>CSV Importer</h2>';

    // Display Ban List
    echo '<h3>Ban List</h3>';
    foreach ($ban_list_data as $csv_data) {
        echo '<pre>';
        print_r($csv_data);
        echo '</pre>';
    }

    // Display Whitelist
    echo '<h3>Whitelist</h3>';
    foreach ($whitelist_data as $csv_data) {
        echo '<pre>';
        print_r($csv_data);
        echo '</pre>';
    }

    echo '</div>';
}

// Load Ban List CSVs
$ban_list_path = plugin_dir_path(__FILE__) . 'ban-list/';
$ban_list_data = parse_csv_folder($ban_list_path);

// Load Whitelist CSVs
$whitelist_path = plugin_dir_path(__FILE__) . 'whitelist/';
$whitelist_data = parse_csv_folder($whitelist_path);

// Function to parse all CSVs in a folder
function parse_csv_folder($folder_path) {
    $csv_data = [];

    // Check if the folder exists
    if (is_dir($folder_path)) {
        // Open the folder
        if ($handle = opendir($folder_path)) {
            // Read all files in the folder
            while (false !== ($entry = readdir($handle))) {
                // Check if it's a CSV file
                if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'csv') {
                    $csv_data[] = parse_csv($folder_path . $entry);
                }
            }
            closedir($handle);
        }
    }

    return $csv_data;
}



// Function to parse CSV
function parse_csv($file_path)
{
    $csv_data = [];
    if (($handle = fopen($file_path, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $csv_data[] = $data;
        }
        fclose($handle);
    }
    return $csv_data;
}