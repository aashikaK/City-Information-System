<?php
ini_set('max_execution_time', 600);
require "db.php";

/* City fallback coordinates (REAL city centers) */
$cityCenters = [
    "Kathmandu" => [27.7172, 85.3240],
    "Lalitpur"  => [27.6644, 85.3188],
    "Bhaktapur" => [27.6710, 85.4298],
    "Pokhara"   => [28.2096, 83.9856],
    "Chitwan"   => [27.5291, 84.3542],
    "Lumbini"   => [27.4844, 83.2760],
    "Mustang"   => [29.1833, 83.8333]
];

function fetchLatLng($query) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($query);
    $opts = [
        "http" => [
            "header" => "User-Agent: CityInformationSystem/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    $data = json_decode($json, true);

    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon']
        ];
    }
    return null;
}

/* Get all records */
$stmt = $pdo->query("SELECT id, name, city FROM city_services");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {

    $search = $row['name'] . ", " . $row['city'] . ", Nepal";
    $coords = fetchLatLng($search);

    /* Fallback to city center if exact place not found */
    if (!$coords && isset($cityCenters[$row['city']])) {
        $coords = [
            'lat' => $cityCenters[$row['city']][0],
            'lng' => $cityCenters[$row['city']][1]
        ];
    }

    if ($coords) {
        $update = $pdo->prepare(
            "UPDATE city_services SET latitude=?, longitude=? WHERE id=?"
        );
        $update->execute([$coords['lat'], $coords['lng'], $row['id']]);

        echo "✔ Updated: {$row['name']} ({$row['city']})<br>";
    } else {
        echo "❌ Skipped: {$row['name']}<br>";
    }

    sleep(1); // REQUIRED (OSM rate limit)
}

echo "<br><b>DONE — REAL COORDINATES ADDED</b>";
