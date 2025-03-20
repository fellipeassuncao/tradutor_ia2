# Tradutor IA

O **Tradutor - Expansor de Buscas** é um software desenvolvido pelo **Laboratório de Dados, Métricas Institucionais e Reprodutibilidade Científica (Datalab/UFRGS)**. Seu objetivo é aprimorar buscas em bases de pesquisa, expandindo automaticamente os termos utilizados para aumentar a abrangência e relevância dos resultados.

## Como funciona?

O Tradutor - Expansor de Buscas foi desenvolvido inicialmente visando a utilização para pesquisas da área de medicina, e por tal é priorizada a utilização com base nos termos médicos comumente utilizados a partir de uma caixa de busca na página inicial. Por exemplo, "doenças gastrointestinais" é um sinônimo para a palavra "Gastroenteropatias", que é o termo preferido no contexto médico. A busca se centra nos termos preferidos, dado que esses são os termos indicados pelos tesauros da área médica. Alternativamente, quando pesquisado por um sinônimo, a busca irá redirecionar o usuário para o termo preferido segundo os tesauros da área médica, garantindo que os termos estejam sendo utilizadas de forma correta e mantendo a confiabilidade na recuperação da informação. Os resultados são apresentados na página a partir da recuperação das informação de dados externos, obtidos por meio do uso de APIs de Thesaurus e Inteligência Artifical como o MESH, DESC, Wikipedia e ChatGPT. Os resultados da obtenção da tradução e sinônimos dos termos possibilitam a criação de strings de busca automáticas para bases de dados como a Scielo, PUBMED, Scopus, Mendley Data e Zenodo. 

## 1. Instalação e Configuração de LLMs

### 1.1 Instalar Ollama

Para instalar o **Ollama**, execute:

```sh
curl -fsSL https://ollama.com/install.sh | sh
```

### 1.2 Instalar Composer

```sh
composer require modelflow-ai/ollama
```

### 1.3 Verificar instalação do Ollama

Após a instalação, acesse pelo navegador:

🔗 [http://localhost:11434](http://localhost:11434)

Ou execute os seguintes comandos no terminal:

```sh
curl http://localhost:11434/api/tags
ollama list
```

Se nenhum modelo estiver disponível, será necessário baixá-los.

## 2. Download e Execução de Modelos

### 2.1 Baixar e Rodar LLaMA

#### Baixar LLaMA 3.1 (4.9 GB)

```sh
ollama pull llama3.1
```

#### Baixar LLaMA 3.3 (42 GB) – Requer pelo menos 45,5 GB de RAM

```sh
ollama pull llama3.3
```

### 2.2 Testar um modelo manualmente via Bash

```sh
curl -X POST http://localhost:11434/api/chat \  
-H "Content-Type: application/json" \  
-d '{  
    "model": "llama3.1",  
    "messages": [  
        {"role": "user", "content": "Defina inteligência artificial"}  
    ]  
}'
```

## 3. Baixar e Executar DeepSeek-R1 8B

### 3.1 Baixar o DeepSeek-R1 8B

```sh
ollama pull deepseek-r1:8b
```

### 3.2 Rodar o modelo

```sh
ollama run deepseek-r1:8b
```

### 3.3 Testar o modelo manualmente via Bash

```sh
curl -X POST http://localhost:11434/api/chat \  
-H "Content-Type: application/json" \  
-d '{  
    "model": "deepseek-r1:8b",  
    "messages": [  
        {"role": "user", "content": "Defina inteligência artificial"}  
    ]  
}'
```

## 4. APIs

### Testar Endpoint do Mesh

```sh
curl "https://id.nlm.nih.gov/mesh/lookup/descriptor?label=diabetes&match=exact&lang=pt"
```

🔗 [Testar no navegador](https://id.nlm.nih.gov/mesh/lookup/descriptor?label=diabetes&match=exact&lang=pt)
