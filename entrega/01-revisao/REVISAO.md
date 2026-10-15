# Revisão do front

Data: 15/10/2026. Branch: `feature/frontend-uoscc`.

## Escopo e evidências

Revisão no navegador Chromium do Codex, com viewport de 1440 x 1000 e 390 x 844. Foram percorridas 17 páginas em cada largura: início; listagem e cinco detalhes de atividades; listagem e três detalhes de espaços; listagem e quatro perfis de organizações; informações para apoiadores. Houve uma conferência adicional da listagem de eventos em 320 x 740.

As capturas JPG nesta pasta registram cada página. `verificacao-paginas.json` registra títulos, presença de H1 e larguras na primeira passagem. Os painéis ajudam a comparar a disposição geral; as capturas individuais permitem ampliar cada tela. As páginas afetadas pelos ajustes foram capturadas novamente. Os painéis representam a passagem inicial.

Nenhuma página apresentou o erro de aplicação observado anteriormente ou rolagem horizontal nas duas larguras principais. Nenhum erro de console foi registrado na sessão inspecionada. Isso não certifica todos os navegadores ou dispositivos reais.

## Interações verificadas

| Cenário | Resultado observado |
| --- | --- |
| Eventos: inscrições abertas + região Orla | Dois resultados na data da revisão |
| Eventos: termo inexistente | Mensagem de ausência de atividades |
| Limpeza de filtros de eventos | Retorno aos cinco resultados |
| Espaços: capacidade 40 + Wi-Fi | Dois resultados |
| Espaços: mesmos filtros + Centro | Mensagem sem resultados |
| Limpeza de filtros de espaços | Retorno aos três resultados |
| Organizações: busca sem acento + região incompatível | Contagem atualizada e estado vazio |
| Limpeza de filtros de organizações | Retorno aos quatro perfis |
| Menu mobile | Abertura e fechamento com Esc |
| Inscrição vazia | Campos obrigatórios identificados pelo navegador |
| Inscrição com dados fictícios válidos | Mensagem explícita de simulação, sem reserva de vaga |
| Nome curto em inscrição | Erro em português e foco no resumo |
| Reserva com data passada | Campo de data inválido |
| Reserva fictícia com data futura | Mensagem explícita de simulação, sem pedido enviado |

Os envios usaram somente os endpoints demonstrativos locais já existentes e dados fictícios (`qa@example.invalid`). Nenhum serviço externo foi acionado, nenhuma persistência nova foi implementada e nenhuma regra de back-end foi alterada.

## Ajustes de front nesta revisão

- Cartões de atividades podem crescer no cabeçalho para acomodar textos maiores.
- Botão de detalhes e vagas ficam empilhados em celulares para melhorar a leitura.
- Campos usam 16 px em telas pequenas; telas maiores mantêm o tamanho anterior.
- O atributo HTML de nome mínimo de três caracteres acompanha a validação existente.

## Verificações técnicas

`npm run build`: concluído. Os três arquivos de testes de funcionalidades do protótipo: 42 testes e 134 assertions aprovados. Formatação e revisão de espaços em branco concluídas.

O aviso opcional de otimização de fontes (`fontaine`) permanece e não impediu a compilação. Não foram instaladas novas dependências.

## Limites da revisão

- Teste responsivo por viewport não equivale a teste em um iPhone ou Android físico.
- Safari, Firefox, leitores de tela e zoom do sistema precisam de verificação específica antes da entrega final.
- Prazos dos dados fictícios dependem da data atual. Os números observados acima não são expectativas permanentes.
- Inscrições, reservas, autenticação e conteúdo oficial permanecem pendentes da equipe e da integração futura.
