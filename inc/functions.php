<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class UMLSMeSHService {
    private $MESH_API_KEY;
    private $baseUrl = "https://uts-ws.nlm.nih.gov/rest";

    public function __construct($MESH_API_KEY) {
        $this->MESH_API_KEY = $MESH_API_KEY;
    }

    /**
     * Função para achatar um array (quando contém sub-arrays)
     */
    public function achatarArray($array) {
        $resultado = [];
        foreach ($array as $elemento) {
            if (is_array($elemento)) {
                $resultado = array_merge($resultado, $this->achatarArray($elemento)); // Usa o método da própria classe
            } else {
                $resultado[] = (string) $elemento;
            }
        }
        return $resultado;
    }

    /**
     * Busca o CUI do termo médico no UMLS/MeSH.
     */
    public function buscarCUI($termo) {
        $url = "{$this->baseUrl}/search/current?string=" . urlencode($termo) . "&searchType=exact&apiKey={$this->MESH_API_KEY}";
        $resultado = $this->fazerRequisicao($url);

        if (!empty($resultado['result']['results'])) {
            foreach ($resultado['result']['results'] as $item) {
                if (!empty($item['ui']) && $item['ui'] !== "NONE") {
                    return $item['ui'];
                }
            }
        }
        return null;
    }

    /**
     * Obtém as traduções do termo em diferentes idiomas no MeSH.
     */
    public function buscarTraducoes($cui) {
        // Mapeamento dos códigos para os nomes dos idiomas
        $idiomas = [
            "MSH"    => "Inglês",
            "MSHSPA" => "Espanhol",
            "MSHFRE" => "Francês",
            "MSHPOR" => "Português",
        ];

        $traducoes = [];

        foreach ($idiomas as $codigoIdioma => $nomeIdioma) {
            $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?sabs={$codigoIdioma}&termType=MH&ttys=MH&apiKey={$this->MESH_API_KEY}";
            $resultado = $this->fazerRequisicao($url);

            if (!empty($resultado['result'])) {
                foreach ($resultado['result'] as $item) {
                    if (!empty($item['name'])) {
                        $traducoes[$nomeIdioma][] = $item['name']; // Usa o nome do idioma em vez do código
                    }
                }
            }
        }

        return $traducoes;
    }
    
    /**
     * Obtém as definições do termo no MeSH, filtradas por idiomas escolhidos.
     */
    public function buscarDefinicoes($cui) {
        // Mapeamento de rootSource para nomes de idiomas legíveis
        $idiomas = [
            "MSH"    => "Inglês",
            "MSHSPA" => "Espanhol",
            "MSHFRE" => "Francês",
            "MSHPOR" => "Português",
        ];

        // Defina quais idiomas devem aparecer no resultado
        $idiomasPermitidos = [
            "Inglês",
            "Espanhol",
            "Francês",
            "Português",
        ];

        $url = "{$this->baseUrl}/content/current/CUI/{$cui}/definitions?apiKey={$this->MESH_API_KEY}";
        $resultado = $this->fazerRequisicao($url);

        $definicoes = [];

        if (!empty($resultado['result'])) {
            foreach ($resultado['result'] as $index => $item) {
                if (!empty($item['value']) && !empty($item['rootSource'])) {
                    $rootSource = $item['rootSource'];
                    $idioma = $idiomas[$rootSource] ?? $rootSource; // Usa nome amigável ou rootSource como fallback
                    
                    // Só adiciona se estiver na lista permitida
                    if (in_array($idioma, $idiomasPermitidos)) {
                        $definicoes[] = "<strong>{$idioma}: </strong>" . $item['value']; 
                    }
                }
            }
        }

        return $definicoes;
    }

    public function buscarSinonimos($cui) {
        // Mapeamento dos códigos para os nomes dos idiomas
        $idiomas = [
            "MSH"    => "Inglês",
            "MSHSPA" => "Espanhol",
            "MSHFRE" => "Francês",
            "MSHPOR" => "Português",
        ];

        $sinonimos = [];

        foreach ($idiomas as $codigoIdioma => $nomeIdioma) {
            $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?sabs={$codigoIdioma}&termType=MH&ttys=ET&apiKey={$this->MESH_API_KEY}";
            $resultado = $this->fazerRequisicao($url);

            if (!empty($resultado['result'])) {
                foreach ($resultado['result'] as $item) {
                    if (!empty($item['name'])) {
                        $sinonimos[$nomeIdioma][] = $item['name']; // Usa o nome do idioma em vez do código
                    }
                }
            }
        }

        return $sinonimos;
    }

    /**
     * Função auxiliar para fazer requisições HTTP e retornar os dados decodificados.
     */
    private function fazerRequisicao($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $resposta = curl_exec($ch);
        curl_close($ch);

        return json_decode($resposta, true);
    }

    /*
    public function buscarTraducoes1($cui) {

        $idiomas = [
            "MSH",    // Inglês
            "MSHSPA", // Espanhol
            "MSHFRE", // Francês
            "MSHGER", // Alemão
            "MSHITA", // Italiano
            "MSHDUT", // Holandês
            "MSHPOR", // Português
            "MSHRUS", // Russo
            "MSHCHI", // Chinês
            "MSHJPN", // Japonês
            "MSHKOR", // Coreano
            "MSHCZE", // Tcheco
            "MSHPOL", // Polonês
            "MSHTUR", // Turco
            "MSHDAN", // Dinamarquês
            "MSHFIN", // Finlandês
            "MSHHUN", // Húngaro
            "MSHNOR", // Norueguês
            "MSHSWE", // Sueco
            "MSHGRE", // Grego
            "MSHSCR"  // Croata
        ];
        
        $traducoes = [];

        foreach ($idiomas as $idioma) {
            $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?sabs={$idioma}&termType=MH&ttys=MH&apiKey={$this->MESH_API_KEY}";
            $resultado = $this->fazerRequisicao($url);

            if (!empty($resultado['result'])) {
                foreach ($resultado['result'] as $item) {
                    if (!empty($item['name'])) {
                        $traducoes[$idioma][] = $item['name']; // Agora acumula traduções
                    }
                }
            }
        }
        return $traducoes;
    }
    
    /**
     * Obtém as definições do termo no MeSH.
     */
    /*
    public function buscarDefinicoes1($cui) {
        $url = "{$this->baseUrl}/content/current/CUI/{$cui}/definitions?apiKey={$this->MESH_API_KEY}";
        $resultado = $this->fazerRequisicao($url);

        $definicoes = [];
        if (!empty($resultado['result'])) {
            foreach ($resultado['result'] as $item) {
                if (!empty($item['value'])) {
                    $definicoes[] = $item['value'];
                }
            }
        }
        return $definicoes;
    }
        /**
     * Obtém os sinônimos do termo no MeSH.
     */
     /*
    public function buscarSinonimos1($cui) {
        $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?ttys=ET&apiKey={$this->MESH_API_KEY}";
        $resultado = $this->fazerRequisicao($url);
    
        $sinonimos = [];
        if (!empty($resultado['result'])) {
            foreach ($resultado['result'] as $item) {
                if (!empty($item['name'])) {
                    $sinonimos[] = $item['name'];
                }
            }
        }
        return array_unique($sinonimos);
    }     
    **/
}

class DadosExternos
{
    static function DECS($termo)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?words=' . trim(urlencode($termo)) . '',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $xml_extracted = simplexml_load_string($resp);
        $data = json_decode(json_encode($xml_extracted), true);
        if (isset($data['decsws_response']['tree'])) {
            ProcessaRegistros::DECS($data['decsws_response']);
        } elseif (isset($data['decsws_response'])) {
            if (count($data['decsws_response'])  > 0) {
                foreach ($data['decsws_response'] as $record) {
                    ProcessaRegistros::DECS($record);
                }
            }
        }
    }
    static function exatoDECS($termo)
    {

        // URL que você deseja acessar
        $url = 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?bool=107%20' . trim(urlencode($termo)) . '';

        // Inicializa a sessão cURL
        $ch = curl_init();

        // Configurações da sessão cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9'
        ]);

        // Executa a sessão cURL e armazena a resposta
        $response = curl_exec($ch);

        // Verifica se houve algum erro
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
        } else {
            // Exibe a resposta
            $xml_extracted = simplexml_load_string($response);
            $data = json_decode(json_encode($xml_extracted), true);
            //echo "<pre>" . print_r($data, true) . "</pre>";
            if (isset($data['decsws_response']['tree'])) {
                ProcessaRegistros::DECS($data['decsws_response']);
            } elseif (isset($data['decsws_response'])) {
                if (count($data['decsws_response'])  > 0) {
                    foreach ($data['decsws_response'] as $record) {
                        ProcessaRegistros::DECS($record);
                    }
                }
            }
        }

        // Fecha a sessão cURL
        curl_close($ch);
    }

    static function exatoDECSComposto($termos)
    {
        echo '<div class="card-group">';
        foreach ($termos as $termo) {
            //var_dump($termo);
            // URL que você deseja acessar
            $url = 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?bool=107%20' . urlencode(trim($termo)) . '';

            // Inicializa a sessão cURL
            $ch = curl_init();

            // Configurações da sessão cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9'
            ]);

            // Executa a sessão cURL e armazena a resposta
            $response = curl_exec($ch);

            // Verifica se houve algum erro
            if (curl_errno($ch)) {
                echo 'Erro: ' . curl_error($ch);
            } else {
                // Exibe a resposta
                $xml_extracted = simplexml_load_string($response);
                $data = json_decode(json_encode($xml_extracted), true);
                //echo "<pre>" . print_r($data, true) . "</pre>";
                if (isset($data['decsws_response']['tree'])) {
                    //ProcessaRegistros::DECSComposto($data['decsws_response']);

                    echo '<div class="card mb-3">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $data['decsws_response']['tree']['self']['term_list']['term'] . '</h5>';
                    echo '<p class="card-text"><b>Definição:</b> ' . $data['decsws_response']['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';

                    //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
                    echo '<b>Consulta por traduções:</b> ' . implode(' OR ', $data['decsws_response']['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
                    if (count($data['decsws_response']['record_list']['record']['synonym_list']) > 0) {
                        if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                            $sinonimoArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['synonym_list']['synonym']) . ')';
                        } else {
                            if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                                $sinonimoArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['synonym_list']['synonym']) . ')';
                            }
                        }
                    }
                    if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                        echo '<b>Consulta por termos relacionados:</b> ' . implode(' OR ', $sinonimoArray) . '</p>';
                    }


                    $consultaArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['descriptor_list']['descriptor']) . ')';

                    echo '</div>';
                    echo '</div>';
                } elseif (isset($data['decsws_response'])) {
                    if (count($data['decsws_response'])  > 0) {
                        foreach ($data['decsws_response'] as $record) {
                            ProcessaRegistros::DECSComposto($record);
                        }
                    }
                } else {
                    echo "A pesquisa não retornou nenhum dado";
                }
            }

            // Fecha a sessão cURL
            curl_close($ch);
        }

        echo "</div>";




        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";

        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar por traduções: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $consultaArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $consultaArray) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $consultaArray) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=' . implode(' AND ', $consultaArray) . ';" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $consultaArray) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $consultaArray) . '" target="_blank">Zenodo</a>

        </small></br>';
        if (count($data['decsws_response']['record_list']['record']['synonym_list']) > 0) {
            if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar por termos relacioandos: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $sinonimoArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode(' AND ', $sinonimoArray) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                if (isset($sinonimoArray)) {
                    echo '<small class="text-body-secondary">Pesquisar por termos relacionados: 
                    <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $sinonimoArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                    <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Pubmed</a> 
                    <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Scopus</a>
                    <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode('AND ', $sinonimoArray) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                    <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Mendeley Data</a>
                    <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Zenodo</a>
    
                    </small></p>';
                }
            }
        }
    }

    static function Wikipedia($termo)
    {
        $url = "https://pt.wikipedia.org/w/api.php?action=query&format=json&prop=langlinks&lllimit=500&titles=" . trim(urlencode(strtolower($termo))) . "";
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            'http' => [
                'ignore_errors' => true,
            ]
        ]);
        $resp = file_get_contents($url);
        $data = json_decode($resp, true);
        ProcessaRegistros::Wikipedia($data);
        //echo "<pre>" . print_r($data, true) . "</pre>";
    }

    static function WikipediaComposto($termos)
    {
        echo '<div class="card-group">';
        foreach ($termos as $termo) {
            $url = "https://pt.wikipedia.org/w/api.php?action=query&format=json&prop=langlinks&lllimit=500&titles=" . trim(urlencode(strtolower($termo))) . "";
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'ignore_errors' => true,
                ]
            ]);
            $resp = file_get_contents($url);
            $data = json_decode($resp, true);
            foreach ($data['query']['pages'] as $page) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title"><a href="https://pt.wikipedia.org/?curid=' . $page['pageid'] . '">' . $page['title'] . '</a></h5>';
                $sinonimoArray = [];
                foreach ($page['langlinks'] as $langlink) {
                    $sinonimoArray[] = $langlink['*'];
                }
                echo '<p class="card-text"><b>Consulta por traduções:</b> ' . implode(' OR ', $sinonimoArray) . '</p>';
                $sinonimosArray[] =  '(' . implode(' OR ', $sinonimoArray) . ')';

                echo '</div>';
                echo '</div>';
            }
            echo '</div">';
        }

        echo '</div">';


        echo '</div><div><p><small class="text-body-secondary">Pesquisar por traduções: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimosArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=&search_form_submit=&q=' . implode(' OR ', $sinonimosArray) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Zenodo</a>


        </small></p></div>';
        //echo "<pre>" . print_r($data, true) . "</pre>";
    }

    static function AGROVOC($termo)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://agrovoc.fao.org/agrovoc/rest/v1/search/?lang=pt-BR&query=' . $termo . '',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $data = json_decode($resp, true);
        foreach ($data['results'] as $result) {
            //print("<pre>" . print_r($data['results'][0], true) . "</pre>");
            $uri = $result['uri'];
            // Get cURL resource
            $ch = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://agrovoc.fao.org/agrovoc/rest/v1/data/?format=application/json&uri=' . $uri . '',
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
                )
            );
            // Send the request & save response to $resp
            $resp_ch = curl_exec($ch);
            $data_ch = json_decode($resp_ch, true);
            if (isset($data_ch['graph'][3])) {
                ProcessaRegistros::AGROVOC($data_ch['graph'][3], $termo);
            }
        }
    }
}

class ProcessaRegistros
{
    static function DECS($record)
    {
        $sinonimo_array = [];
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $record['tree']['self']['term_list']['term'] . '</h5>';
        echo '<p class="card-text"><b>Definição:</b> ' . $record['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';
        echo '<b>Consulta por traduções:</b> ' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
        //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                $sinonimo_array = $record['record_list']['record']['synonym_list']['synonym'];
            } else {
                $sinonimo_array[] = $record['record_list']['record']['synonym_list']['synonym'];
            }
        }

        $sinonimo_array[] = $record['tree']['self']['term_list']['term'];
        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";
        echo '<b>Consulta por termos relacionados:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar por traduções: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Zenodo</a>

        </small></br>';

        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar por termos relacionados: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                echo '<small class="text-body-secondary">Pesquisar por termos relacionados: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a>
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            }
        }
        echo '</div>';
        echo '</div>';
        //echo "<pre>" . print_r($record, true) . "</pre>";
    }

    static function DECSComposto($record)
    {
        $sinonimo_array = [];
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $record['tree']['self']['term_list']['term'] . '</h5>';
        echo '<p class="card-text"><b>Definição:</b> ' . $record['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';
        echo '<b>Consulta por traduções:</b> ' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
        //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                $sinonimo_array = $record['record_list']['record']['synonym_list']['synonym'];
            } else {
                $sinonimo_array[] = $record['record_list']['record']['synonym_list']['synonym'];
            }
        }

        $sinonimo_array[] = $record['tree']['self']['term_list']['term'];
        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";
        echo '<b>Consulta por termos relacionados: :</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar por traduções: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Zenodo</a>

        </small></br>';

        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar por termos relacionados: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                echo '<small class="text-body-secondary">Pesquisar por termos relacionados: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a>
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            }
        }
        echo '</div>';
        echo '</div>';
        //echo "<pre>" . print_r($record, true) . "</pre>";
    }
    static function Wikipedia($record)
    {
        // Verifica se 'query' e 'pages' existem no registro
        if (!isset($record['query']['pages']) || !is_array($record['query']['pages'])) {
            echo 'Sem resultados';
            return;
        }
    
        foreach ($record['query']['pages'] as $page) {
            if (isset($page['pageid'])) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title"><a href="https://pt.wikipedia.org/?curid=' . htmlspecialchars($page['pageid']) . '">' . htmlspecialchars($page['title']) . '</a></h5>';
    
                // Verifica se 'langlinks' existe e é um array
                $sinonimo_array = [];
                if (isset($page['langlinks']) && is_array($page['langlinks'])) {
                    foreach ($page['langlinks'] as $langlink) {
                        if (isset($langlink['*'])) {
                            $sinonimo_array[] = htmlspecialchars($langlink['*']);
                        }
                    }
                }
    
                // Exibe os sinônimos apenas se houver algum
                if (!empty($sinonimo_array)) {
                    echo '<p class="card-text"><b>Consulta por traduções:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
                    echo '<p><small class="text-body-secondary">Pesquisar por traduções: 
                    <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                    <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                    <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                    <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                    <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                    <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>
                    </small></p>';
                } else {
                    echo '<p class="card-text"><b>Consulta por traduções:</b> Sem traduções disponíveis.</p>';
                }
    
                echo '</div>';
                echo '</div>';
            } else {
                echo 'Sem resultados';
            }
        }
    }
    
    static function AGROVOC($record, $termo)
    {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title"><a href="' . $record['uri'] . '">' . $termo . '</a></h5>';
        $sinonimo_array = [];
        foreach ($record['prefLabel'] as $prefLabel) {
            $sinonimo_array[] = $prefLabel['value'];
        }
        echo '<p class="card-text"><b>Consulta por traduções:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p><small class="text-body-secondary">Pesquisar por traduções: 
            <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=(' . implode(') OR (', $sinonimo_array) . ')&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
            <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
            <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
            <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

            </small></p>';

        echo '</div></div>';
    }
}

class Parser
{
    static function parserQueryExists($request)
    {
        if (!empty($request['search'])) {
            return true;
        } else {
            return false;
        }
    }

    static function parserQuery($request)
    {
        $terms = explode("AND", $request['search']);
        return $terms;
    }
}