<!DOCTYPE HTML>
<html lang="pt-br">
<link rel="shortcut icon" href="/tradutor_ia2/inc/images/favicon.ico" type="image/x-icon"/>
<head>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
    <title>Expansor de buscas</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="description" content="Expansor de buscas" />
    <meta name="keywords" content="Produção acadêmica, lattes, ORCID" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .p-about-team {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            justify-items: center;
            margin-top: 2rem;
        }

        .team-member {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            background-color: #f9f9f9;
            text-align: center;
            transition: transform 0.3s;
        }

        .team-member:hover {
            transform: scale(1.05);
        }

        .c-who-photo {
            height: 120px;
            width: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .c-who-name {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .c-who-title {
            color: #777;
            font-size: 1rem;
        }

        .c-who-link {
            display: block;
            color: #007bff;
            text-decoration: none;
            margin-top: 1rem;
        }

        .p-about-section2 {
            margin-top: 3rem;
        }

        .section-header {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .p-about-logos {
            display: flex;
            justify-content: center; /* Centraliza horizontalmente */
            align-items: center; /* Alinha verticalmente */
            gap: 20px; /* Espaço entre as logos */
            flex-wrap: wrap; /* Garante que as imagens se ajustem em telas menores */
        }
        .content-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .content-box {
            flex: 1;
            min-width: 300px; /* Para garantir responsividade */
        }

        @media (max-width: 768px) {
            .content-container {
                flex-direction: column;
            }
        }
        .p-about-section2-gray {
            background-color: #f5f5f5; /* Cor cinza clara */
            padding: 20px 0; /* Adiciona um espaçamento interno para melhorar a aparência */
        }

        /* Se quiser adicionar bordas ou sombras para mais destaque */
        .p-about-section2-gray {
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }
    </style>
</head>

<body>
    <?php include_once('inc/header_secondary.php'); ?>
    
    <main class="container p-about">
        <section class="p-about-section1">
            <h1 class="t t-h2 u-my-10">Quebre a barreira do idioma na busca científica</h1>
            <p class="p-about-text">
                O <strong>Tradutor - Expansor de Buscas</strong> é uma ferramenta desenvolvida pelo <strong>Laboratório de Dados, Métricas Institucionais e Reprodutibilidade Científica (Datalab) da
                UFRGS</strong>, visando resolver um dos principais limitadores quanto à recuperação de informação científica na contemporaneidade: a barreira linguística. A partir da utilização
                de diferentes ferramentas para a tradução de termos da área médica, especialmente o tesauro multilíngue Medical Subject Headings (MeSH) e Inteligência Artificial, esta ferramenta
                 objetiva traduzir os termos inseridos na barra de pesquisa e expandir os resultados de busca incluindo as traduções nos mais diversos idiomas. No momento é uma ferramenta
                aplicada para a área das ciências médicas, ao usar tesauros e serviços desta área, enquanto serve também como protótipo que poderá ser adaptado para outras bases de dados,
                serviços de busca e áreas do conhecimento.            
            </p>
            
        <section class="p-about-section2 p-about-section2-gray">
                <div class="content-container">
                    <!-- Coluna 1: Como funciona? -->
                    <div class="content-box">
                        <h2 class="t t-h3 u-my-10">Como funciona?</h2>
                        <p class="p-about-text">A ferramenta traduz automaticamente termos científicos, utilizando bases como:</p>
                        <ul class="p-about-list">
                            <li><strong>Medical Subject Headings (MeSH)</strong> - Tesauro multilíngue da área médica.</li>
                            <li><strong>Wikipedia</strong> - Para reforço da contextualização dos termos.</li>
                            <li><strong>Modelos de linguagem avançados</strong> (ChatGPT 4o-mini).</li>
                        </ul>
                        <p class="p-about-text">Atualmente, o Expansor de Buscas está focado na área médica, mas pode ser adaptado para outras disciplinas e bases de dados.</p>
                    </div>

                    <!-- Coluna 2: Código Aberto e Gratuito -->
                    <div class="content-box">
                        <h2 class="t t-h3 u-my-10">Código Aberto e Gratuito</h2>
                        <p class="p-about-text">
                            O projeto é <strong>livre e open-source</strong>! Contribua ou explore o código no nosso repositório do GitHub:
                        </p>
                        <a href="https://github.com/maryelisa2000/Tradutor_IA" target="_blank" class="p-about-link">
                            <p class="t t-a">Visite o nosso repositório Github</p>
                            <svg title="Github"
                            alt="Acesse o repositório Github" class="p-about-ico" xmlns="https://www.w3.org/2000/svg"
                            viewBox="0 0 64 64" width="64px" height="64px">
                            <path
                                d="M32 6C17.641 6 6 17.641 6 32c0 12.277 8.512 22.56 19.955 25.286-.592-.141-1.179-.299-1.755-.479V50.85c0 0-.975.325-2.275.325-3.637 0-5.148-3.245-5.525-4.875-.229-.993-.827-1.934-1.469-2.509-.767-.684-1.126-.686-1.131-.92-.01-.491.658-.471.975-.471 1.625 0 2.857 1.729 3.429 2.623 1.417 2.207 2.938 2.577 3.721 2.577.975 0 1.817-.146 2.397-.426.268-1.888 1.108-3.57 2.478-4.774-6.097-1.219-10.4-4.716-10.4-10.4 0-2.928 1.175-5.619 3.133-7.792C19.333 23.641 19 22.494 19 20.625c0-1.235.086-2.751.65-4.225 0 0 3.708.026 7.205 3.338C28.469 19.268 30.196 19 32 19s3.531.268 5.145.738c3.497-3.312 7.205-3.338 7.205-3.338.567 1.474.65 2.99.65 4.225 0 2.015-.268 3.19-.432 3.697C46.466 26.475 47.6 29.124 47.6 32c0 5.684-4.303 9.181-10.4 10.4 1.628 1.43 2.6 3.513 2.6 5.85v8.557c-.576.181-1.162.338-1.755.479C49.488 54.56 58 44.277 58 32 58 17.641 46.359 6 32 6zM33.813 57.93C33.214 57.972 32.61 58 32 58 32.61 58 33.213 57.971 33.813 57.93zM37.786 57.346c-1.164.265-2.357.451-3.575.554C35.429 57.797 36.622 57.61 37.786 57.346zM32 58c-.61 0-1.214-.028-1.813-.07C30.787 57.971 31.39 58 32 58zM29.788 57.9c-1.217-.103-2.411-.289-3.574-.554C27.378 57.61 28.571 57.797 29.788 57.9z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
            <section class="p-about-section2">
            <h3 class="section-header">Orientações de Uso</h3>
                <p class="p-about-text">
                    O <strong>Tradutor - Expansor de Buscas</strong> foi desenvolvido visando a utilização por pessoas da área de medicina, e por tal é priorizada a utilização com
                    base nos termos médicos comumente utilizados. Por exemplo, "doenças gastrointestinais" é um sinônimo para a palavra "Gastroenteropatias", que é o termo preferido no
                    contexto médico. A busca se centra nos termos preferidos, dado que esses são os termos indicados pelos tesauros da área médica. Alternativamente, quando pesquisado
                    por um sinônimo, a busca irá redirecionar o usuário para o termo preferido segundo os tesauros da área médica, garantindo que os termos estejam sendo utilizadas de
                    forma correta e mantendo a confiabilidade na recuperação da informação.
                </p>
            </section>    

            <section class="p-about-section3">
                <h3 class="section-header">Apoio e Realização</h3>
                <p class="p-about-text">
                    Esta ferramenta teve financiamento do <strong>CNPq</strong> e foi desenvolvida pelo 
                    <strong>Datalab/UFRGS</strong> (Laboratório de Dados, Métricas Institucionais e Reprodutibilidade Científica).
                </p>
                <p class="t t-md">Universidade Federal do Rio Grande do Sul</p>
                <div class="p-about-logos">
                    <a href="https://cnpq.br/" target="_blank">
                        <img src="inc\images\cnpqlogo.svg" alt="Logo do CNPq" height="60px">
                    </a>
                    <a href="https://www.ufrgs.br/datalab/" target="_blank">
                        <img src="inc\images\datalab.svg" alt="Logo do Datalab" height="100px">
                    </a>
                    <a href="https://www.ufrgs.br" target="_blank">
                        <img src="inc\images\ufrgs.svg" alt="Logo da UFRGS" height="100px">
                    </a>
                </div>
            </section>

            
            <h3 class="section-header">Equipe</h3>
            <div class="p-about-team">

                <div class="team-member">
                    <img class="c-who-photo" src="http://servicosweb.cnpq.br/wspessoa/servletrecuperafoto?tipo=1&id=K4509021Y6" />
                    <div class="c-who-name"><b>Fabiano Couto Corrêa</b></div>
                    <div class="c-who-title">Pesquisador Principal</div>
                    <a href="http://lattes.cnpq.br/4635807083312321" class="c-who-link" target="_blank">Lattes</a>
                </div>

                <div class="team-member">
                    <img class='c-who-photo u-grayscale' src="https://avatars.githubusercontent.com/u/170115359?v=4" />
                    <div class="c-who-name"><b>Maria Elizabeth Vasconcellos Monteiro</b></div>
                    <div class="c-who-title">Pesquisadora Associada</div>
                    <a href="http://lattes.cnpq.br/5403684310321604" class="c-who-link" target="_blank">Lattes</a>
                </div>


                <div class="team-member">
                    <img class='c-who-photo u-grayscale' src="https://avatars.githubusercontent.com/u/499115?v=4" />
                    <div class="c-who-name"><b>Tiago Rodrigo Marçal Murakami (in memorian)</b></div>
                    <div class="c-who-title">Pesquisador e Desenvolvedor</div>
                    <a href="http://lattes.cnpq.br/0306160176168674" class="c-who-link" target="_blank">Lattes</a>
                </div>

                <div class="team-member">
                    <img class="c-who-photo" src="https://avatars.githubusercontent.com/u/29359805?v=4" />
                    <div class="c-who-name"><b>Felipe Moreira de Assunção</b></div>
                    <div class="c-who-title">Pesquisador e Desenvolvedor</div>
                    <a href="https://lattes.cnpq.br/4926043479130073" class="c-who-link" target="_blank">Lattes</a>
                </div>

            </div>
        </section>
    </main>

    <footer>
        <?php include_once('inc/footer.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JJ3kR7uCbaY6f5M4tzzDcbRYnP2W3jUnhHgg6FxwRZf5uOd8wvlaKvWlRhy4f8xk"
        crossorigin="anonymous"></script>
</body>

</html>
