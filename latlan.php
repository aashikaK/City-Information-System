<?php
ini_set('max_execution_time', 600);
require "db.php";

// City fallback coordinates
$cityCenters = [
    "Kathmandu" => [27.7172, 85.3240],
    "Lalitpur"  => [27.6644, 85.3188],
    "Bhaktapur" => [27.6710, 85.4298],
    "Pokhara"   => [28.2096, 83.9856],
    "Chitwan"   => [27.5291, 84.3542],
    "Lumbini"   => [27.4844, 83.2760],
    "Mustang"   => [29.1833, 83.8333]
];

// Function to fetch coordinates from OSM
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

// Fetch all services
$stmt = $pdo->query("SELECT id, name, city, location FROM city_services");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {

    // Try multiple search queries
    $queries = [
        $row['name'] . ", " . $row['city'] . ", Nepal",              // name + city
        $row['name'] . ", " . $row['location'] . ", " . $row['city'] . ", Nepal" // name + location + city
    ];

    $coords = null;
    foreach ($queries as $q) {
        $coords = fetchLatLng($q);
        if ($coords) break; // stop at first successful fetch
        sleep(1); // respect rate limit
    }

    // Fallback to city center only if still not found
    if (!$coords && isset($cityCenters[$row['city']])) {
        $coords = [
            'lat' => $cityCenters[$row['city']][0],
            'lng' => $cityCenters[$row['city']][1]
        ];
    }

    if ($coords) {
        $update = $pdo->prepare("UPDATE city_services SET latitude=?, longitude=? WHERE id=?");
        $update->execute([$coords['lat'], $coords['lng'], $row['id']]);

        echo "✔ Updated: {$row['name']} ({$row['city']}) => {$coords['lat']}, {$coords['lng']}<br>";
    } else {
        echo "❌ Skipped: {$row['name']}<br>";
    }

    sleep(1); // rate limit
}

echo "<br><b>DONE — REAL COORDINATES UPDATED</b>";
