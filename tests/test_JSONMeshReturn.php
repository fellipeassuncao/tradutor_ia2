<?php
// Simulação de um retorno JSON do MeSH
$meshData = '{
    "resource": "https://id.nlm.nih.gov/mesh/D000818",
    "label": "Diabetes Mellitus",
    "definition": "A group of diseases characterized by high blood glucose levels that result from defects in insulin secretion, insulin action, or both.",
    "translations": [
        "Diabetes Mellitus",
        "Diabete",
        "Diabetes",
        "糖尿病",
        "Diabetes Mellito"
    ],
    "entryTerms": [
        "Diabetes",
        "Diabetes Mellitus Type 1",
        "Diabetes Mellitus Type 2",
        "Insulin-Dependent Diabetes Mellitus",
        "Non-Insulin-Dependent Diabetes Mellitus",
        "Type 1 Diabetes",
        "Type 2 Diabetes"
    ]
}';

// Decodificar o JSON em um array PHP
$translations = json_decode($meshData, true);

// Exibir a definição
echo "<p><b>Definição:</b> " . htmlspecialchars($translations['definition']) . "</p>";

// Exibir as traduções
echo "<p><b>Traduções:</b> " . implode(" OR ", $translations['translations']) . "</p>";

// Exibir os sinônimos
echo "<p><b>Sinônimos:</b> " . implode(" OR ", $translations['entryTerms']) . "</p>";
?>