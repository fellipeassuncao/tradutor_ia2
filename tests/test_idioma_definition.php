<?php
// Configurações iniciais
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.php';
require('inc/config.php');

// Classe UMLSMeSHService (simplificada para teste)
class UMLSMeSHService {
    private $MESH_API_KEY;
    private $baseUrl = "https://uts-ws.nlm.nih.gov/rest";

    public function __construct($MESH_API_KEY) {
        $this->MESH_API_KEY = $MESH_API_KEY;
    }

    // Função para buscar definições por idiomas
    public function buscarDefinicoesPorIdiomas($cui, $idiomasSelecionados) {
        $idiomaVocabulario = [
            "Português" => "MSHPOR",
            "Inglês" => "MSHENG",
            "Espanhol" => "MSHSPA",
            "Francês" => "MSHFRE",
            "Alemão" => "MSHGER",
            "Italiano" => "MSHITA",
            "Russo" => "MSHRUS",
            "Chinês" => "MSHCHI",
            "Japonês" => "MSHJPN",
        ];

        $definicoesPorIdioma = [];

        foreach ($idiomasSelecionados as $idiomaNome) {
            $codigoIdioma = $idiomaVocabulario[$idiomaNome] ?? null;
            if (!$codigoIdioma) {
                $definicoesPorIdioma[$idiomaNome] = ["Idioma não suportado."];
                continue;
            }

            $url = "{$this->baseUrl}/content/current/CUI/{$cui}/definitions?apiKey={$this->MESH_API_KEY}&sabs={$codigoIdioma}";
            $resultado = $this->fazerRequisicao($url);

            if (!empty($resultado['result'])) {
                $definicoes = [];
                foreach ($resultado['result'] as $item) {
                    if (!empty($item['value'])) {
                        $definicoes[] = $item['value'];
                    }
                }
                $definicoesPorIdioma[$idiomaNome] = $definicoes;
            } else {
                $definicoesPorIdioma[$idiomaNome] = ["Nenhuma definição encontrada."];
            }
        }

        return $definicoesPorIdioma;
    }

    // Função auxiliar para requisições HTTP
    private function fazerRequisicao($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resposta = curl_exec($ch);
        curl_close($ch);
        return json_decode($resposta, true);
    }
}

// Teste da função
try {
    // Configurações do teste
    $MESH_API_KEY = 'YOUR_API_KEY'; // Substitua pela sua API Key do UMLS
    $umls = new UMLSMeSHService($MESH_API_KEY);
    $cui = 'C0011849'; // Exemplo: CUI para "Diabetes Mellitus"
    $idiomasSelecionados = ['Português', 'Inglês', 'Espanhol']; // Idiomas a serem testados

    // Executa a função
    $definicoes = $umls->buscarDefinicoesPorIdiomas($cui, $idiomasSelecionados);

    // Exibe os resultados
    echo "<h2>Resultados para CUI: $cui</h2>";
    foreach ($definicoes as $idioma => $definicao) {
        echo "<h3>$idioma:</h3>";
        if (is_array($definicao)) {
            echo "<ul>";
            foreach ($definicao as $texto) {
                echo "<li>$texto</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>$definicao</p>";
        }
    }
} catch (\Exception $e) {
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
}
?>