<?php
// Convert file byte size to different formats
function formatFileSize($sizeInBytes) {
    if ($sizeInBytes === null) {
        // Handle the case when $sizeInBytes is null
        return '0 MB';
    }

    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $unitIndex = 0;

    while ($sizeInBytes >= 1024 && $unitIndex < count($units) - 1) {
        $sizeInBytes /= 1024;
        $unitIndex++;
    }

    $formattedSize = round($sizeInBytes, 2) . ' ' . $units[$unitIndex];

    // Optionally, you can add logic to convert to MB or GB as needed
    if ($units[$unitIndex] == 'KB' || $units[$unitIndex] == 'MB' || $units[$unitIndex] == 'GB') {
        $formattedSize = round($sizeInBytes, 2) . ' ' . $units[$unitIndex];
    }

    return $formattedSize;
}
?>
