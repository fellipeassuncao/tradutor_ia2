<!doctype html>
<html lang="pt-BR"> 
<link rel="shortcut icon" href="/tradutor_ia2/inc/images/favicon.ico" type="image/x-icon"/>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datalab UFRGS - Expansor de buscas - Projeto de pesquisa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include_once('inc/header.php'); ?>
        <div class="container">
            <main class="content">
                
                <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                    <img src="/tradutor_ia2/inc/images/expansor.png" height="120px">
                    <br></br>

                    <!--<h1 class="display-4 fw-normal text-body-emphasis">Expansor de buscas</h1>
                    <p class="fs-5 text-body-secondary">Software desenvolvido pelo Laboratório de dados, métricas institucionais e reprodutibilidade científica - Datalab da UFRGS, com o objetivo de expandir termos de buscas para ampliar os resultados obtidos em diversas bases de pesquisa.</p>
                    -->
                    <!-- Formulário Principal -->
                    <form action="resultado.php" method="post" id="searchForm">
                        <!-- Campo de Busca -->
                        <!--<div class="input-group mb-3 justify-content-center">
                            <input type="text" class="form-control" placeholder="Buscar..." name="search" required>
                            <button class="btn btn-primary" type="submit" id="button-search">Expandir e Buscar</button>
                            <div class="invalid-feedback">
                                Por favor, insira um termo de busca.
                            </div>
                        </div>
                    -->
                        <!-- Novo campo de Busca -->
                        <div class="input-group mb-3 justify-content-center">
                            <input type="text" class="form-control" placeholder="Buscar..." name="search" required aria-label="Campo de busca" id="searchField">
                            <button class="btn btn-primary" type="submit" id="button-search" aria-label="Expandir e Buscar">
                                <i class="fas fa-search"></i> <!-- Ícone de lupa -->
                            </button>
                            <div class="invalid-feedback">
                                Por favor, insira um termo de busca.
                            </div>
                        </div>

                        <!-- Mensagem de Expansão -->
                        <p class="text-muted mt-3 text-center">
                            Sua busca será expandida para incluir traduções e sinônimos em diferentes idiomas. 
                            Dica: pesquise por termos médicos preferidos em tesauros como o DeCS e MeSH                            
                        </p>

                        <!-- Área Principal de Busca -->
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <h4 class="text-center">Área principal de busca</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Medicina" value="Medicina" checked>
                                    <label class="form-check-label" for="Medicina">Medicina</label>
                                </div>
                                <!-- Comentado temporariamente -->
                                <!--
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Agricultura" value="Agricultura">
                                    <label class="form-check-label" for="Agricultura">Agricultura</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Outras" value="Outras">
                                    <label class="form-check-label" for="Outras">Outras áreas</label>
                                </div>
                                -->
                            </div>
                        </div>
                  
                        <!-- Comentado temporariamente -->
                        <!-- Seleção de Linguagens -->
                        <!--
                        <div class="row justify-content-center mt-4">
                            <div class="col-sm-6">
                                <h4 class="text-center">Escolha as linguagens</h4>
                                <div id="language-selection">
                                    <?php
                                    $opcoes = ['Português', 'Inglês', 'Espanhol', 'Francês', 'Alemão', 'Italiano', 'Latim', 'Russo'];
                                    foreach ($opcoes as $opcao) {
                                        $checked = $opcao === 'Português' ? 'checked' : '';
                                        echo "<div class='form-check'>
                                                <input class='form-check-input language-checkbox' type='checkbox' name='languages[]' id='$opcao'
                                                    value='$opcao' $checked aria-checked='" . ($checked ? "true" : "false") . "'>
                                                <label class='form-check-label' for='$opcao'>$opcao</label>
                                            </div>";
                                    }
                                    ?>
                                </div>
                                <div id="language-error" class="text-danger small d-none">Por favor, selecione pelo menos uma linguagem.</div>
                            </div>
                        </div>
                        -->
                    
                        <!-- ChatGPT code -->
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h4 class="text-center">Usar Inteligência Artificial?</h4>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="ChatGPTSwitch" name="ChatGPT">
                                    <label class="form-check-label" for="ChatGPTSwitch">Sim (ChatGPT)</label>
                                </div>
                            </div>
                            <div class="col-sm"></div>
                        </div>
    
                            
                        <!-- OLLAMA code -->
                        <!--
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h4>Usar Inteligência Artificial (Llama 3.1)? (Pode aumentar bastante o tempo de
                                    resposta necessário)</h4>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="LlamaSwitch"
                                        name="Lhama">
                                    <label class="form-check-label" for="LlamaSwitch">Sim</label>
                                </div>
                            </div>
                            <div class="col-sm"></div>
                        </div>
                        -->
                        <!-- DEEP SEEK code -->
                        <!-- 
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h4>Usar Inteligência Artificial (Deep Seek R1 - 8B)? (Pode aumentar bastante o tempo de
                                    resposta necessário)</h4>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="DeepSeekSwitch"
                                        name="DeepSeekR18B">
                                    <label class="form-check-label" for="DeepSeekSwitch">Sim</label>
                                </div>
                            </div>
                            <div class="col-sm"></div>
                        </div>
                        -->
                        
                        <!-- Script de Validação Linguagem-->
                        <!--
                        <script>
                            document.getElementById('searchForm').addEventListener('submit', function (event) {
                                const checkboxes = document.querySelectorAll('.language-checkbox');
                                let isChecked = false;

                                // Verifica se pelo menos uma linguagem foi selecionada
                                checkboxes.forEach(checkbox => {
                                    if (checkbox.checked) {
                                        isChecked = true;
                                    }
                                });

                                // Exibe mensagem de erro se nenhuma linguagem for selecionada
                                const errorDiv = document.getElementById('language-error');
                                if (!isChecked) {
                                    event.preventDefault(); // Impede o envio do formulário
                                    errorDiv.classList.remove('d-none');
                                } else {
                                    errorDiv.classList.add('d-none');
                                }
                            });
                        </script>
                        -->   

                        </form>
                    </div>
                </div>
            </main>
        </div>
        <?php include_once('inc/footer.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>