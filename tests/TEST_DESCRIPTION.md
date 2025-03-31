# Test Description
## Pagina Inicial
> [] Implementar varias linguagens (avaliar abordagem SCIELO)
> [] Testar funcionamento da IA Chat GPT
> [] Testar funcionamento da IA Llama e outros
> [] Avaliar tratamento de erros

## Resultados
> [] Avaliar a composição dos termos e a relevancia do resultados em cada uma das bases de dados
> [x] Testar prompt de comando para retornar resultados da IA com menos ruído
> [x] Testar se a IA retorna diferentes definições / traduções / sinônimos para um mesmo termo.
> [x] Incluir qual é o termo no bloco de resultados da IA
> [x] Incluir prompt e strings de busca para sinônimos da IA
> [x] Colocar sinônimos no tesauro
> [x] Repensar prompts da IA
> [x] Ver bugs na Scielo
> [] Analisar documentos trazidos
> [] Escolha de 20 termos médicos aleatórios para testes
> [x] Avaliar limites de busca (tamanho da string ou quantidade de termos) para cada base de dados
> [x] Avaliar priorizar os termos de busca apenas nos idiomas mais relevantes na Scielo (devido ao limite de busca de cada base)
> [] Avaliar quais os idiomas mais relevantes nas outras bases de dados

## Funções 
> [x] Desenvolver funções para recuperar as traduções e definições dos termos por idiomas, conforme seleção na página inicial
> [x] Recuperar apenas os tesauros de idiomas
> [x] Desenvolver regra para definições e traduções para o MESH em cada uma das linguagens
> [] Criar função com blocos dos links para cada base de dados
> [] Avaliar o &quot (aspas duplas nas strings de busca) e o melhor tratamento.

## Strings de Busca
> *URL BASE:* $baseUrl = "https://uts-ws.nlm.nih.gov/rest";
> *MESH API KEY:* e0043acd-f023-4680-8a7c-60fe6e0e1e2b

> *Traduções:*
* $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?sabs={$idioma}&termType=MH&ttys=MH&apiKey={$this->MESH_API_KEY}"
* Infecção Urinária: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0042029/atoms?termType=MH&ttys=MH&apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b
* Coração: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0018787/atoms?termType=MH&ttys=MH&apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b

> *Definições:* 
* $url = "{$this->baseUrl}/content/current/CUI/{$cui}/definitions?apiKey={$this->MESH_API_KEY}";
* Infecção Urinária: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0042029/definitions?apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b
* Coração: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0018787/definitions?apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b

> *Sinônimos:* 
* $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?sabs={$codigoIdioma}&termType=MH&ttys=ET&apiKey={$this->MESH_API_KEY}";
* $url = "{$this->baseUrl}/content/current/CUI/{$cui}/atoms?ttys=ET&apiKey={$this->MESH_API_KEY}";
* Epitélio: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0014609/atoms?ttys=ET&apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b
* Infecção Urinária: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0042029/atoms?ttys=ET&apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b
* Coração: https://uts-ws.nlm.nih.gov/rest/content/current/CUI/C0018787/atoms?ttys=ET&apiKey=e0043acd-f023-4680-8a7c-60fe6e0e1e2b


>> *Considerações sobre sinônimos e outros termos:*
>* ET (Entry Term): Sinônimos
>* SY (Synonym): Termos equivalentes
>* XM (Cross Mapping): Mapeamentos cruzados entre diferentes vocabulários
>* PREF (Preferred Term): Termo preferido

## Melhorias Ferrramenta em geral: 

Melhorias:
- Colocar sinônimos no tesauro
- Repensar prompts da IA
- Ver bugs na Scielo
- Analisar documentos trazidos
- Escolha de 20 termos médicos aleatórios para testes

Observações:
- Sinonimos já estão implementados para os tesauros, mas para a IA, merece testar novos prompts e avaliar a relevancia dos termos apresentados.
- Cada base de dados se comporta de maneira diferente em relação a quantidade de termos ou de caracteres suportados na busca. Exemplo: Scielo pode funcionar bem com 20 termos e PUBMED pode funcionar bem com até 256 caracteres de busca. Muitos termos pode gerar problemas como a não recuperação, falhas e lentidão, a depender do horário, quantidade de usuários simultaneos, etc.
- Sugiro estabelecer um limite de termos para formar a string de busca, que pode ser um limite geral para todas as bases de dados (ex. 20 termos);
- Pelo que estou pesquisando as plataformas não descrevem seus limites de busca, o que exige que façamos testes por vias proprias para achar uma quantidade de termos adequada;
- Sugiro que não seja criada duas strings de busca pois no melhor caso, deveriamos achar os termos dos idiomas mais  para recuperação da informação;
- Os termos podem ser considerados a partir dos idiomas mais frequentes / relevantes em cada uma das bases de dados. Por exemplo: termos árabes são realmente relevantes ou são ruídos quando comparado a vasta quantidade de artigos em outros idiomas? Na ciência de dados, desconsideramos os ruidos, outliers, pois não representam a característica frequente do todo. Toda supressão de dados é uma tomada de decisão que envolve perdas e ganhos de dados em prol do funcionamento do todo.

- Ao diminuir a quantidade de idiomas de busca no Mesh, com maior frequencia ele retornará nenhum resultado.

Muito a se pensar.

