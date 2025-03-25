<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.php';
require('inc/config.php');

$resultParserExists = Parser::parserQueryExists($_REQUEST);


use ModelflowAi\Ollama\Ollama;

// Create a client instance
$client = Ollama::client();

// Use the client
$chat = $client->chat();
// $completion = $client->completion();
// $embeddings = $client->embeddings();

?>


<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultado de busca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include_once('inc/header_secondary.php'); ?>
    <div class="container">
        <main>         
            
            <?php if ($resultParserExists): ?>

            <div class="alert alert-info" role="alert">
                Você pesquisou por: <?php echo $_REQUEST['search'] ?>
            </div>
            <?php
                sleep(1); // Simula um processamento demorado
                $tempo = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                // Formata o tempo para exibir 4 casas decimais
                $tempoFormatado = number_format($tempo, 4, '.', '');
            ?>
            <?php Parser::parserQuery($_REQUEST); 
                ?>

            <?php if (count(Parser::parserQuery($_REQUEST)) == 1): ?>

            <?php if (isset($_REQUEST['ChatGPT'])): ?>

            <h3>Inteligência Artificial (IA) - ChatGPT</h3>
            <div class="card mb-3">
                
                <div class="card-body">
                    <h5><?= htmlspecialchars($_REQUEST['search']) ?></h5>
                    <p><b>Definição:</b>
                    <?php
                        $yourApiKey = OPENAI_API_KEY;
                        $client = OpenAI::client($yourApiKey);

                        try {
                            $result = $client->chat()->create([
                                'model' => 'gpt-4o-mini',
                                'messages' => [
                                    ['role' => 'user', 'content' => 'Defina o termo:' . $_REQUEST['search'] . 'como se fosse um dicionário da área médica sem incluir titulos e formatações como ** ou ##. Retorne apenas a definição.'],
                                ],
                            ]);

                            echo $result->choices[0]->message->content;
                        } catch (\Exception $e) {
                            echo "Erro ao processar a solicitação: " . $e->getMessage();
                        }        
                    ?>
                    </p>
                    <p><b>Traduções:</b>

                    <?php
                        try{
                            $result2 = $client->chat()->create([
                                'model' => 'gpt-4o-mini',
                                'messages' => [
                                    ['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo ' . $_REQUEST['search'] . ' em uma lista separada por |, sem incluir o idioma, somente a lista dos resultados. Não é preciso incluir uma definição. Responda somente a lista'],
                                    ],
                            ]);

                            echo $result2->choices[0]->message->content;
                        } catch (\Exception $e) {
                            echo "Erro ao processar a solicitação: " . $e->getMessage();
                        }        
                       

                        $traducoesOpenAI = explode('|', $result2->choices[0]->message->content);

                        // Consulta expandida para traduções
                        $consultaPorTraducoes = implode(" OR ", array_unique($traducoesOpenAI));
                        echo "<p><strong>Consulta expandida: </strong> $consultaPorTraducoes</p>";
                        
                        // Buscar sinônimos via OpenAI
                        $resultSinonimos = $client->chat()->create([
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                [
                                    'role' => 'user',
                                    'content' => 'Liste sinônimos e variações para o termo "' . $_REQUEST['search'] . '" em uma lista separada por |, sem incluir definições. Responda somente a lista.'
                                ],
                            ],
                        ]);

                        // Processar a resposta
                        $sinonimosOpenAI = explode('|', $resultSinonimos->choices[0]->message->content);
                        $sinonimosOpenAI = array_map('trim', $sinonimosOpenAI); // Remover espaços extras
                        $sinonimosOpenAI = array_unique($sinonimosOpenAI); // Remover duplicados

                        // Consulta expandida para sinônimos
                        $consultaPorSinonimos = implode(" OR ", $sinonimosOpenAI);
                        echo "<p><strong>Consulta por sinônimos: </strong> $consultaPorSinonimos</p>";

                        // Exibir sinônimos em lista
                        /*echo "<p><strong>Sinônimos:</strong></p>";
                        echo "<ul>";
                        foreach ($sinonimosOpenAI as $sinonimo) {
                            echo "<li>$sinonimo</li>";
                        }
                        echo "</ul>";
                        */     
    
                        // Gera links para todas as bases de dados
                        echo '<span class="small text-body-secondary">Pesquisar por idioma: </span>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://search.scielo.org/?q=' . urlencode(implode(" OR ", $traducoesOpenAI)) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . urlencode(implode(' OR ', $traducoesOpenAI)) . '" target="_blank">Pubmed</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . urlencode(implode(' OR ', $traducoesOpenAI)) . '" target="_blank">Scopus</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . urlencode(implode('&quot; OR &quot;', $traducoesOpenAI)) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://data.mendeley.com/research-data/?search=' . urlencode(implode(' OR ', $traducoesOpenAI)) . '" target="_blank">Mendeley Data</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . urlencode(implode(' OR ', $traducoesOpenAI)) . '" target="_blank">Zenodo</a>';
                        echo '<br><br>'; 
                        // Links para bases de dados com sinônimos
                        echo '<span class="small text-body-secondary">Pesquisar por sinônimos: </span>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://search.scielo.org/?q=' . urlencode($consultaPorSinonimos) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . urlencode($consultaPorSinonimos) . '" target="_blank">Pubmed</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . urlencode($consultaPorSinonimos) . '" target="_blank">Scopus</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . urlencode($consultaPorSinonimos) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://data.mendeley.com/research-data/?search=' . urlencode($consultaPorSinonimos) . '" target="_blank">Mendeley Data</a>';
                        echo '<a class="btn btn-primary btn-sm me-2" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . urlencode($consultaPorSinonimos) . '" target="_blank">Zenodo</a>';
                            
                    ?>               
                </div>
            </div>

            <?php endif; ?>

            <?php if (isset($_REQUEST['Llhama'])): ?>

            <h3>Inteligência Artificial (IA) - Llama 3.1</h3>
            <div class="card mb-3">
                <div class="card-body">


                    <p><b>Definição:</b>
                        <?php

                                    // Example usage of chat
                                    $chatResponse = $chat->create([
                                        'model' => 'llama3.1',
                                        'messages' => [['role' => 'user', 'content' => 'Defina de maneira extensiva:' . $_REQUEST['search'] . '']],
                                    ]);
                                    echo $chatResponse->message->content . \PHP_EOL;
                                    ?>
                    </p>
                    
                    <h5>Inteligência Artificial (IA) - Llama 3.1 - Expande sinônimos e variações para o termo</h5>

                    <?php
                                // Example usage of chat
                                $chatResponse4 = $chat->create([
                                    'model' => 'llama3.1',
                                    'messages' => [['role' => 'user', 'content' => 'Forneça sinônimos e variações para o termo: ' . $_REQUEST['$search'] . '. Não é preciso incluir uma definição']],
                                ]);   
                                echo $chatResponse4->message->content . \PHP_EOL;

                                ?>
                    <br /><br />

                    <h5>Inteligência Artificial (IA) - Llama 3.1 - Traduções</h5>

                    <?php
                                // Example usage of chat
                                $chatResponse2 = $chat->create([
                                    'model' => 'llama3.1',
                                    'messages' => [['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo:' . $_REQUEST['search'] . '. Não é preciso incluir uma definição']],
                                ]);
                                echo $chatResponse2->message->content . \PHP_EOL;

                                ?>
                    <br /><br />
                    <?php
                                // Example usage of chat
                                $chatResponse3 = $chat->create([
                                    'model' => 'llama3.1',
                                    'messages' => [['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo ' . $_REQUEST['search'] . ' em uma lista separada por |, sem incluir o idioma, somente a lista dos resultados. Não é preciso incluir uma definição. Responda somente a lista']],
                                ]);
                                $traducoes = explode('|', $chatResponse3->message->content);
                                echo '<span class="small text-body-secondary">Pesquisar por idioma:</span>';
                                echo '<a href="https://search.scielo.org/?q=' . implode(" OR ", $traducoes) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> - ';
                                echo '<a href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $traducoes) . '" target="_blank">Pubmed</a> - ';
                                echo '<a href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $traducoes) . '" target="_blank">Scopus</a> - ';
                                echo '<a href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $traducoes) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';

                                ?>
                </div>
            </div>

            <?php endif; ?>
            <h3>Mesh Exato</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <?php
                    // Função para exibir mensagens de erro
                    function exibirErro($mensagem) {
                        echo "<p class='text-danger'><strong>Erro:</strong> $mensagem</p>";
                    }

                    try {
                        // Verifica se o termo foi informado
                        if (!isset($_REQUEST['search']) || empty(trim($_REQUEST['search']))) {
                            exibirErro("Termo não informado.");
                            exit;
                        }

                        // Sanitiza o termo de busca
                        $termo = htmlspecialchars(trim($_REQUEST['search']));

                        // Verifica se a classe UMLSMeSHService existe
                        if (!class_exists('UMLSMeSHService')) {
                            exibirErro("Classe UMLSMeSHService não encontrada.");
                            exit;
                        }

                        // Instancia a classe com a chave da API
                        $umls = new UMLSMeSHService(MESH_API_KEY);

                        // Busca o CUI do termo fornecido
                        $cui = $umls->buscarCUI($termo);
                        if ($cui) {
                            // Exibe o termo e o CUI
                            echo "<h5>$termo</h5>";
                            echo "<p><strong>CUI encontrado:</strong> $cui</p>";

                            // Processar definições
                            $definicoes = $umls->buscarDefinicoes($cui);
                            if (!empty($definicoes)) {
                                echo "<p><strong>Definições:</strong></p>";
                                echo "<ul>";
                                foreach ($definicoes as $definicao) {
                                    echo "<li>$definicao</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>Nenhuma definição encontrada.</p>";
                            }

                            // Processar traduções
                            $traducoes = $umls->buscarTraducoes($cui);
                            if (!empty($traducoes)) {
                                echo "<p><strong>Traduções:</strong></p>";
                                echo "<ul>";
                                foreach ($traducoes as $idioma => $traducao) {
                                    echo "<li><strong>$idioma:</strong> " . implode(", ", $traducao) . "</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>Nenhuma tradução encontrada.</p>";
                            }
                            
                            // Processar sinônimos
                            $sinonimos = $umls->buscarSinonimos($cui);
                            $sinonimosAchatados = $umls->achatarArray($sinonimos);
                            $sinonimosUnicos = array_unique($sinonimosAchatados);

                            // Exibir consulta por sinônimos
                            if (!empty($sinonimosUnicos)) {
                                echo "<p><strong>Consulta por sinônimos:</strong> " . implode(" OR ", $sinonimosUnicos) . "</p>";
                            }
                            // Construir consulta expandida
                            $consultaExpandida = [$termo];
                            $consultaExpandida = array_merge($consultaExpandida, $sinonimosUnicos);

                            if (!empty($traducoes)) {
                                $traducoesAchatadas = $umls->achatarArray($traducoes);
                                $consultaExpandida = array_merge($consultaExpandida, $traducoesAchatadas);
                            }

                            // Exibir consulta expandida
                            $consultaExpandida = implode(" OR ", array_unique($consultaExpandida));
                            echo "<p><strong>Consulta expandida:</strong> $consultaExpandida</p>";

                            // Links para pesquisa em bases de dados por idioma
                            if (!empty($consultaExpandida)){
                                echo '<span class="small text-body-secondary">Pesquisar por idioma:</span>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://search.scielo.org/?q=' . urlencode($consultaExpandida) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . urlencode($consultaExpandida) . '" target="_blank">Pubmed</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . urlencode($consultaExpandida) . '" target="_blank">Scopus</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . urlencode($consultaExpandida) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://data.mendeley.com/research-data/?search=' . urlencode($consultaExpandida) . '" target="_blank">Mendeley Data</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . urlencode($consultaExpandida) . '" target="_blank">Zenodo</a><br>';
                            
                        } else {
                            exibirErro("Termo não encontrado no UMLS/MeSH.");
                        }

                            // Links para pesquisa em bases de dados por sinônimos
                            if (!empty($sinonimosUnicos)) {
                                $consultaSinonimos = implode(" OR ", $sinonimosUnicos);
                                echo '<span class="small text-body-secondary">Pesquisar por sinônimos:</span>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://search.scielo.org/?q=' . urlencode($consultaSinonimos) . '" target="_blank">Scielo</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . urlencode($consultaSinonimos) . '" target="_blank">Pubmed</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . urlencode($consultaSinonimos) . '" target="_blank">Scopus</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . urlencode($consultaSinonimos) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://data.mendeley.com/research-data/?search=' . urlencode($consultaSinonimos) . '" target="_blank">Mendeley Data</a>';
                                echo '<a class="btn btn-primary btn-sm me-2" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . urlencode($consultaSinonimos) . '" target="_blank">Zenodo</a>';
                            }

                        } else {
                            exibirErro("Termo não encontrado no UMLS/MeSH.");
                        }
                    } catch (\Exception $e) {
                        exibirErro("Erro ao processar a solicitação: " . $e->getMessage());
                    }
                    ?>
                </div>
            </div>

            <?php if ($_REQUEST['area'] == 'Medicina'): ?>
            <h3>DECS Exato</h3>
            <?php DadosExternos::exatoDECS($_REQUEST['search']); ?>

            <h3>DECS Busca</h3>
            <?php DadosExternos::DECS($_REQUEST['search']); ?>
            <?php endif; ?>

            <?php if ($_REQUEST['area'] == 'Agricultura'): ?>
            <h3>AGROVOC</h3>
            <?php DadosExternos::AGROVOC($_REQUEST['search']); ?>
            <?php endif; ?>

            <h3>Wikipédia</h3>
            <?php DadosExternos::Wikipedia($_REQUEST['search']); ?>
            <?php else: ?>

            <h3>DECS Exato</h3>
            <?php if ($_REQUEST['area'] == 'Medicina') {
                        DadosExternos::exatoDECSComposto(Parser::parserQuery($_REQUEST));
                    }
                    ?>

            <h3>Wikipédia</h3>
            <?php DadosExternos::WikipediaComposto(Parser::parserQuery($_REQUEST));
                    ?>
            <?php endif; ?>

            <?php else: ?>

            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Não foi informado nenhum termo de busca</h4>
                <p>Você pode refazer sua busca</p>
                <hr>
                <div class="row justify-content-center central-container">
                    <form action="resultado.php" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar..." name="search">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                        <div class="row">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h4>Área principal de busca</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Medicina"
                                        value="Medicina" checked>
                                    <label class="form-check-label" for="Medicina">
                                        Medicina
                                    </label>
                                </div>
                                <!--
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Agricultura"
                                        value="Agricultura">
                                    <label class="form-check-label" for="Agricultura">
                                        Agricultura
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Outras" value="Outras">
                                    <label class="form-check-label" for="Outras">
                                        Outras áreas
                                    </label>
                                </div>
                                -->
                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h5>Usar Inteligência Artificial (ChatGPT)?</h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="ChatGPTSwitch"
                                        name="ChatGPT">
                                    <label class="form-check-label" for="ChatGPTSwitch">Sim</label>
                                </div>

                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <!--
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h5>Usar Inteligência Artificial (Llama 3.1)? (Pode aumentar bastante o tempo de
                                    resposta
                                    necessário)
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="LlamaSwitch"
                                        name="Lhama">
                                    <label class="form-check-label" for="LlamaSwitch">Sim</label>
                                </div>

                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h5>Usar Inteligência Artificial (Deep Seek R1-8B)? (Pode aumentar bastante o tempo de
                                    resposta necessário)
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="DeepSeekSwitch"
                                        name="DeepSeekR18B">
                                    <label class="form-check-label" for="DeepSeekSwitch">Sim</label>
                                </div>

                            </div>
                            <div class="col-sm"></div>
                        </div>
                        -->
                    </form>
                </div>
            </div>

            <?php endif; ?>

        </main>

        <?php include_once('inc/footer.php'); ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>