# Roteiro de demonstração do Grupo C

Proposta 3: abrir a UOSCC para o público. Versão de 15/10/2026, para ensaio. Os integrantes, duração e divisão de falas precisam ser confirmados.

## Antes de apresentar

1. Estar na branch `feature/frontend-uoscc` e conferir `git status`.
2. Abrir o terminal na pasta `uoscc-app`, onde está o arquivo `artisan`.
3. Executar `npm run build` caso CSS ou JavaScript tenham mudado.
4. Executar `php artisan serve` e manter esse terminal aberto. Usar o endereço exibido por ele.
5. Conferir a data do computador: os prazos dos exemplos são de outubro/novembro de 2026. Inscrições abertas hoje podem estar encerradas no dia da apresentação. Atualizar os dados de demonstração em conjunto ou explicar os estados exibidos; não alterar o relógio do computador.
6. Abrir os slides e separar as capturas da pasta `../01-revisao` como alternativa caso o ambiente local falhe.
7. Usar somente dados fictícios nos formulários. Não ativar serviços externos nem fazer push durante a apresentação.

## Ensaio sugerido de 8 minutos

| Tempo aproximado | Tela | Fala e ação |
| --- | --- | --- |
| 0:00 a 0:50 | Slide de contexto | Explicar a divulgação dispersa e o objetivo de centralizar a consulta pública |
| 0:50 a 1:30 | Início | Mostrar navegação sem login e contadores do catálogo demonstrativo |
| 1:30 a 3:00 | Eventos | Combinar inscrições abertas com região; buscar um termo inexistente; limpar filtros; distinguir evento e projeto |
| 3:00 a 4:00 | Detalhe de atividade | Mostrar data, prazo, vagas e o retorno de simulação; comparar com atividade encerrada e lotada |
| 4:00 a 5:10 | Espaços | Filtrar capacidade mínima 40 + Wi-Fi; mostrar estrutura; abrir detalhe e explicar que solicitar não garante reserva |
| 5:10 a 6:00 | Organizações | Mostrar perfil, atividades e espaços vinculados; explicar que contatos oficiais aguardam confirmação |
| 6:00 a 6:40 | Apoiadores | Diferenciar consulta pública de área restrita ainda em preparação |
| 6:40 a 8:00 | Slides finais | Apresentar verificações realizadas, limites do protótipo e próximos passos para cumprir o PDF |

## Cenários prontos

### A. Descoberta de atividades

- Em `/eventos`, marcar “Somente inscrições abertas” e escolher região Orla.
- Em 15/10/2026, aparecem Oficina de Inclusão Digital e Trilhas Digitais.
- Digitar “nao existe” na busca: exibir o estado vazio.
- Limpar filtros: retornar aos cinco exemplos.
- Os resultados mudam quando os prazos vencem; explicar o comportamento, sem prometer contagem fixa para outra data.

### B. Formulário de inscrição

- Abrir Feira de Cidadania enquanto o prazo estiver aberto.
- Enviar vazio para mostrar os campos obrigatórios.
- Testar nome “Ab” para mostrar o erro de nome curto.
- Usar “Pessoa de Teste” e `qa@example.invalid` para o cenário válido.
- Ler a mensagem: a simulação valida os campos, mas não registra inscrição nem ocupa vaga.
- Mutirão Verde demonstra prazo encerrado; Encontro de Juventudes demonstra lotação na data da revisão.

### C. Consulta e solicitação de espaço

- Capacidade mínima 40 + Wi-Fi: dois espaços na amostra atual.
- Adicionar Centro: nenhum resultado. Limpar.
- Abrir Espaço Conecta, mostrar capacidade e estrutura.
- Testar uma data passada e depois uma futura, finalidade com ao menos 10 caracteres e contato fictício.
- Explicar o retorno de simulação e que uma confirmação real dependerá da análise da OSC e da integração.

### D. Perfil público e acesso

- Abrir Conecta Comunidade e mostrar somente suas atividades e espaço.
- Mostrar contato oficial “a confirmar” e a ausência de redes sociais verificadas.
- Abrir Apoiadores: não há login ou informações privadas nesta versão.
- No celular, abrir o menu, fechar com Esc e navegar com Tab para demonstrar foco e atalho ao conteúdo.

## Se ocorrer um problema

- Conexão recusada: conferir se `php artisan serve` está rodando.
- “Could not open input file: artisan”: entrar em `uoscc-app`.
- Estilo antigo: executar build e atualizar com Ctrl + F5.
- Filtro sem resultados: conferir data, prazos e combinação escolhida; usar Limpar filtros.
- Não reinstalar dependências, apagar banco ou alterar configuração às pressas. Usar as capturas e explicar o estado do protótipo.

## Perguntas esperadas

**O sistema já registra inscrições e reservas?** Não. A interface valida e demonstra os fluxos; a persistência é uma próxima etapa.

**As organizações e os contatos são oficiais?** Não foram confirmados. Há quatro exemplos originais; a proposta prevê seis OSCs e a equipe organizará o conteúdo real.

**A área de apoiadores já protege informações?** Não há conteúdo privado, login ou autorização implementados.

**O que foi verificado?** Páginas em dois tamanhos de viewport, filtros combinados, menu mobile e formulários demonstrativos, além dos testes automatizados documentados na revisão.

**Quais decisões a equipe ainda precisa tomar?** Conteúdo restrito, aprovação e conflito de reservas, duplicidades, cancelamento e lista de espera, conforme a ficha de regras.
