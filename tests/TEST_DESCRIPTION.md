# Test Description
## Pagina Inicial
> [] Implementar varias linguagens (avaliar abordagem SCIELO)
> [] Testar funcionamento da IA Chat GPT
> [] Testar funcionamento da IA Llama e outros
> [] Avaliar tratamento de erros

## Resultados
> [] Avaliar a composição dos termos e a relevancia do resultados em cada uma das bases de dados
> [x] Testar prompt de comando para retornar resultados da IA com menos ruído
> [] Testar se a IA retorna diferentes definições / traduções / sinônimos para um mesmo termo.
> [x] Incluir qual é o termo no bloco de resultados da IA
> [x] Incluir prompt e strings de busca para sinônimos da IA

## Funções 
> [] Desenvolver funções para recuperar as traduções e definições dos termos por idiomas, conforme seleção na página inicial
> [] Densenvolver regra para apresentar apenas a maior definição do idioma escolhido
> [] Desenvolver regra para definições e traduções para o MESH (MSHSPA=>Espanhol)

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


