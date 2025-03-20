<?php
// Inclui o arquivo da classe DadosExternos
require_once __DIR__ . '/../inc/functions.php';


// Chama o método fetchMeshTranslations
$translations = DadosExternos::MeshExato("diabetes");

// Exibe o resultado
echo "<pre>";
print_r($translations);
echo "</pre>";

// Digite no navegador para consultar a disponibilidade do endpoint URL: https://id.nlm.nih.gov/mesh/lookup/descriptor?label=diabetes&match=exact&lang=en
// Para rodar essa função digite no Bash: php tests/test_fetchMesh2.php
?>