<?php
$apiKey = "e0043acd-f023-4680-8a7c-60fe6e0e1e2b";
$termo = "coração";  // Altere para testar outros termos
$url = "https://uts-ws.nlm.nih.gov/rest/search/current?string=" . urlencode($termo) . "&searchType=exact&sabs=MSHPOR&apiKey=" . $apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<pre>";
echo "HTTP Status Code: " . $http_code . "\n";
echo "Resposta da API:\n";
print_r(json_decode($response, true));
echo "</pre>";
?>