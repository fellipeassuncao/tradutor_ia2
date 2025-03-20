<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Load libraries for PHP composer */
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.php';
require('inc/config.php');
$yourApiKey = OPENAI_API_KEY;
$client = OpenAI::client($yourApiKey);
?>
<!doctype html>
<link rel="shortcut icon" href="/tradutor_ia2/inc/images/favicon.ico" type="image/x-icon"/>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultado de busca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include_once('inc/header.php'); ?>
    <div class="container">
        <main>
            <h3>Analisador de artigos usando Inteligência Artificial (ChatGPT)</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <p><b>Resumo:</b>
                        <?php
                        $result = $client->chat()->create([
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                ['role' => 'user', 'content' => 'Responda em Português sobre o que é o texto?:  ' . $_REQUEST['userInput']],
                            ],
                        ]);
                        echo htmlspecialchars($result->choices[0]->message->content) . \PHP_EOL;
                        ?>
                    </p>

                    <!--<h5>Inteligência Artificial (IA) - ChatGPT - Palavras-chave</h5>-->
                    <?php
                    // Solicita ao ChatGPT para gerar as 3 principais palavras-chave separadas por "|"
                    $result2 = $client->chat()->create([
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => 'Com base no seguinte texto: "' . $_REQUEST['userInput'] . '", liste as 3 principais palavras-chave que melhor descrevem o tema central do artigo. Responda apenas com as palavras-chave, separadas por "|".'],
                        ],
                    ]);

                    // Processa as palavras-chave retornadas pelo ChatGPT
                    $palavrasChave = explode('|', $result2->choices[0]->message->content);
                    $palavrasChave = array_map('trim', $palavrasChave); // Remove espaços extras
                    $query = implode(' OR ', $palavrasChave); // Formata para uso em URLs

                    // Exibe as palavras-chave na tela
                    echo '<p><strong>Palavras-chave:</strong> ' . htmlspecialchars(implode(', ', $palavrasChave)) . '</p>';
                    ?>
                    
                    <!-- Links para pesquisa por palavra-chave -->
                    <span class="small text-body-secondary">Pesquisar nas seguintes fontes por palavra-chave:</span><br>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://search.scielo.org/?q=<?= urlencode($query) ?>&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://pubmed.ncbi.nlm.nih.gov/?term=<?= urlencode($query) ?>" target="_blank">Pubmed</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=<?= urlencode($query) ?>" target="_blank">Scopus</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=<?= urlencode($query) ?>" target="_blank">BVS</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://scholar.google.com/scholar?q=<?= urlencode($query) ?>" target="_blank">Google Scholar</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://www.webofscience.com/wos/woscc/basic-search?query=<?= urlencode($query) ?>" target="_blank">Web of Science</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://www.cochranelibrary.com/search?text=<?= urlencode($query) ?>" target="_blank">Cochrane Library</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://www.semanticscholar.org/search?q=<?= urlencode($query) ?>" target="_blank">Semantic Scholar</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://data.mendeley.com/research-data/?search=<?= urlencode($query) ?>" target="_blank">Mendeley Data</a>
                    <a class="btn btn-primary btn-sm me-2 mb-2" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=<?= urlencode($query) ?>" target="_blank">Zenodo</a>
                    <br><br>
                </div>
            </div>
        </main>
        <?php include_once('inc/footer.php'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>