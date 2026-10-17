# Estado da entrega em relação à proposta

Fonte de requisitos: documento orientador do Grupo C, Proposta 3, especialmente páginas 4 a 7. Este é um protótipo de front, não a conclusão do sistema funcional solicitado no PDF.

| Item do documento | O que podemos demonstrar | O que falta |
| --- | --- | --- |
| Consulta pública sem cadastro | Navegação por todas as páginas públicas | Validar conteúdo oficial |
| Eventos e projetos das seis OSCs | Cinco atividades fictícias e quatro perfis originais | Receber as seis OSCs reais e seus conteúdos |
| Data, local e descrição | Cartões e detalhes de atividades | Manutenção e dados confirmados |
| Inscrição aberta, prazo e vagas | Estados e filtro de inscrições abertas | Atualização baseada em inscrições persistidas |
| Inscrever visitante | Formulário, validações e retorno de simulação | Gravação, confirmação, concorrência por vagas e regra de duplicidade |
| Espaços, capacidade e estrutura | Catálogo, filtros e página detalhada | Cadastro e manutenção oficial |
| Solicitar reserva com data | Formulário e validações existentes | Persistir pedido, agenda, aprovação e acompanhamento |
| Busca por OSC, tipo, data ou região | Filtros combinados de atividades | Integrar dados cadastrados mantendo a experiência |
| Público versus restrito | Identificação pública e explicação para apoiadores | Definir conteúdo exclusivo e implementar autenticação/permissões |
| Mini-perfil com contato e redes | Estrutura de perfil, atividades e espaços | Contatos e redes sociais oficiais, imagens e autorização |

## Concluído nesta frente

- Estrutura de páginas reaproveitada da branch original.
- Busca e filtros combinados, contagem, limpeza e estados sem resultados.
- Estados de inscrição e formulários demonstrativos com mensagens explícitas.
- Navegação por teclado, menu mobile e foco de erros.
- Consulta de perfis e vínculos de atividades e espaços.
- Revisão responsiva e evidências de telas.
- Ficha de dados e propostas de regras para validação.

## Fora desta rodada

Não houve integração nova com banco, APIs, autenticação, pagamento, mensagens externas, e-mail ou WhatsApp. Não implementamos cadastro administrativo ou envio real de pedidos. O ambiente Laravel existente serve a demonstração local, como antes.

## Decisões e materiais pendentes

1. Conteúdo e identidade oficial: aguardando a coleta solicitada ao Saulo.
2. Seis OSCs: aguardando relação validada. Não recriar perfis fictícios removidos.
3. Regras finais: pendentes de confirmação da equipe e, quando necessário, do professor/OSC.
4. Dados acadêmicos e tempo de fala: necessários para personalizar a apresentação.
5. Entrega integrada: depende da implementação posterior do back-end e de testes de ponta a ponta.

## Antes de integrar à branch principal

- Conferir cada commit e executar a suíte de testes completa.
- Testar visualmente nos navegadores e dispositivos que a equipe decidiu suportar.
- Confirmar que nenhum arquivo `.env`, banco local, senha ou dado pessoal está no commit.
- Revisar as limitações e não apresentar simulação como inscrição ou reserva efetivada.
- Abrir Pull Request conforme o fluxo combinado com a equipe. Não houve push nesta rodada.
