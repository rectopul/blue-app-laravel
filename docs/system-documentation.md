# Documentacao do Sistema de Investimentos

## 1. Visao geral

Este projeto e uma aplicacao Laravel orientada a investimento, carteira, compra de planos, deposito PIX, saque PIX, comissoes de indicacao e mecanicas de gamificacao.

O produto mistura tres pilares principais:

1. Carteira e transacoes financeiras.
2. Planos de investimento com rendimento diario.
3. Task system e bonus de ovos, usados para engajamento e liberacao de rendimento.

No estado atual do sistema, o usuario:

- deposita saldo via PIX;
- compra um plano de investimento;
- passa a ter direito a uma quantidade diaria de tasks de video;
- conclui essas tasks para liberar o rendimento diario previsto no plano;
- pode solicitar saque via PIX, sujeito a regras de elegibilidade e antifraude;
- pode coletar ovos de gamificacao conforme regras de compra ativa e indicacoes.

## 2. Stack e organizacao

### Backend

- Laravel / PHP
- Controllers em `app/Http/Controllers`
- Services em `app/Services`
- Modulo de gamificacao em `app/Modules/Gamification`
- Models em `app/Models`
- Rotas web em `routes/user.php`
- Rotas API em `routes/api.php`

### Frontend

- Blade
- Tailwind via CDN no layout `blueapp`
- Alpine.js via CDN
- QRCode via CDN para PIX
- Material Symbols para iconografia

### Layouts relevantes

- `resources/views/layouts/blueapp.blade.php`: layout principal do app mobile-like
- `resources/views/layouts/dmk.blade.php`: outro layout visual existente
- `resources/views/app/...`: legado antigo
- `resources/views/blue-app/...`: experiencia atual mais moderna

## 3. Modulos principais

### 3.1 Deposito

Fluxo principal:

- rota web: `/deposit`
- controller: `App\Http\Controllers\user\UserController::recharge`
- view real da rota: `resources/views/blue-app/deposit.blade.php`
- endpoint API: `POST /api/deposit`
- controller API: `UserController::depositStore`

Resumo do fluxo:

- a tela gera valor e envia para a API autenticada por Sanctum;
- o backend usa `PaymentGatewayFactory::create()`;
- o gateway gera `paymentCode`, `paymentCodeBase64` e `transaction_id`;
- um registro e criado em `deposits` com status `pending`;
- o webhook aprova o deposito e incrementa o saldo do usuario;
- o ledger e registrado.

Campos importantes observados em deposito:

- `user_id`
- `method_name`
- `address`
- `transaction_id`
- `amount`
- `security_hash`
- `webhook_data`
- `status`

### 3.2 Saque

Fluxo principal:

- rota web: `/withdraw`
- controller web: `App\Http\Controllers\user\WithdrawController::withdraw`
- view real da rota: `resources/views/blue-app/withdraw/index.blade.php`
- endpoint API: `POST /api/withdraw`
- controller API: `WithdrawController::apiWithdraww`

Regras atuais de saque:

- usuario precisa ter saldo suficiente;
- usuario precisa ter ao menos um investimento realizado;
- usuario precisa ter dados PIX configurados;
- sistema aplica taxa percentual de saque;
- antifraude avalia risco do usuario e do saque;
- status inicial pode variar entre `pending`, `under_review` e `blocked`.

Campos importantes observados em saque:

- `method_name`
- `name`
- `cpf`
- `pix_type`
- `pix_key`
- `amount`
- `charge`
- `final_amount`
- `status`

### 3.3 Compra de planos / investimentos

Rotas principais:

- web: `purchase/confirmation/{id}`
- api: `POST /api/purchase/confirmation/{id}`

Controller principal:

- `App\Http\Controllers\user\PurchaseController`

Comportamento:

- valida pacote ativo;
- valida saldo;
- impede compra duplicada do mesmo pacote ativo;
- desconta saldo da carteira;
- cria `purchase` com status `active`;
- processa comissoes de indicacao;
- marca usuario como investidor.

### 3.4 Sistema de tasks dos planos

Rotas:

- `GET /tasks`
- `GET /tasks/{id}`
- `POST /tasks/complete/{id}`

Arquivos centrais:

- `app/Http/Controllers/user/TaskController.php`
- `app/Services/TaskService.php`
- `app/Models/Task.php`
- `app/Models/UserTaskCompletion.php`
- `resources/views/blue-app/tasks/index.blade.php`
- `resources/views/blue-app/tasks/show.blade.php`

Regra funcional:

- o plano ativo do usuario define `daily_tasks_limit`;
- o plano ativo do usuario define `daily_reward`;
- o sistema calcula `reward_per_task = daily_reward / daily_tasks_limit`;
- o usuario precisa concluir as tasks do dia para receber os creditos diarios;
- cada conclusao gera entrada em `user_task_completions` e `user_ledgers`.

### 3.5 Gamificacao dos ovos

Arquivos centrais:

- `app/Modules/Gamification/Services/GamificationService.php`
- `app/Modules/Gamification/Models/GamificationSetting.php`
- `app/Modules/Gamification/Models/UserGamificationEgg.php`

Regra observada:

- usuario precisa ter compra ativa;
- ovos elegiveis dependem de quantidade de indicacoes diretas;
- regras ativas ficam em `gamification_settings`;
- ovos coletados pelo usuario ficam em `user_gamification_eggs`.

## 4. Estrutura de dados relevante

### 4.1 Tabela `packages`

Campos de negocio relevantes observados:

- `name`
- `price`
- `validity`
- `commission_with_avg_amount`
- `status`
- `featured`
- `daily_tasks_limit`
- `daily_reward`

Uso:

- representa o plano de investimento;
- governa quantidade diaria de tasks;
- governa o total diario de recompensa de tasks.

### 4.2 Tabela `purchases`

Uso:

- representa a adesao do usuario a um plano;
- e a principal referencia de plano ativo;
- determina se o usuario esta habilitado para certas regras operacionais, inclusive saque e gamificacao.

### 4.3 Tabela `tasks`

Antes do ajuste:

- titulo e video eram superficiais;
- a tela de task assumia 30 segundos fixos;
- nao havia metadados suficientes para governar a experiencia.

Apos o ajuste aplicado:

- `title`
- `description`
- `video_url`
- `watch_seconds`
- `sort_order`
- `icon`
- `is_active`

### 4.4 Tabela `user_task_completions`

Uso:

- registra conclusao diaria por usuario e task;
- guarda `reward_amount`;
- guarda `completion_date`.

Apos o ajuste aplicado:

- indice unico diario por `user_id + task_id + completion_date` para reduzir duplicidade acidental.

### 4.5 Tabela `user_ledgers`

Uso:

- trilha contabil do sistema;
- registra deposito aprovado, reward de task, comissao e outros creditos/debitos.

## 5. Regras de negocio das tasks

## 5.1 Estado anterior

Antes das melhorias, o sistema possuia estes comportamentos:

- a tela mostrava contador local de 30 segundos;
- o backend nao validava se a task realmente havia sido aberta antes da confirmacao;
- a duracao do video nao era governada pela task;
- tarefas podiam ficar superficiais demais para um modulo considerado critico.

## 5.2 Melhorias aplicadas agora

Foram implementadas as seguintes melhorias:

- adicao de metadados na task:
  - `description`
  - `watch_seconds`
  - `sort_order`
  - `icon`
- ordenacao consistente das tasks no `TaskService`;
- criacao de sessao de visualizacao ao abrir a task;
- validacao server-side de tempo minimo antes de permitir a conclusao;
- remocao de dependencia de contador fixo na view;
- criacao de indice unico diario em `user_task_completions`;
- melhoria da tela admin para cadastrar tempo minimo, descricao, icone e ordem.

## 5.3 Limitacoes que ainda existem

Mesmo com o reforco atual, ainda existem pontos importantes a considerar:

- o sistema ainda nao prova que o usuario assistiu de fato ao video inteiro, apenas que abriu a tela e aguardou o tempo minimo;
- se for necessario nivel de auditoria mais forte, o ideal e evoluir para tracking por player, heartbeat e validacao de eventos de video;
- o reward por task continua sendo derivado do plano, nao da task;
- se o produto quiser campanhas com tasks de valor diferente, sera preciso evoluir a regra.

## 6. Melhorias de UX/UI aplicadas

### 6.1 Deposito

Arquivo alterado:

- `resources/views/blue-app/deposit.blade.php`

Melhorias:

- alinhamento visual ao `blue-app`;
- cabecalho, cards e blocos informativos consistentes com o dashboard;
- fluxo mais claro para gerar PIX;
- modal de QR Code mais organizado;
- substituicao de aparencia improvisada por componentes mais consistentes;
- troca de sinais visuais por Material Symbols.

### 6.2 Saque

Arquivo alterado:

- `resources/views/blue-app/withdraw/index.blade.php`

Melhorias:

- padronizacao visual com o restante do sistema;
- exibicao mais clara de carteira PIX, taxa e minimo;
- resumo de valor solicitado, taxa e valor liquido;
- reforco de regras de elegibilidade;
- iconografia profissional em vez de emojis.

### 6.3 Dashboard e tasks

Arquivos alterados:

- `resources/views/blue-app/dashboard.blade.php`
- `resources/views/blue-app/tasks/index.blade.php`
- `resources/views/blue-app/tasks/show.blade.php`

Melhorias:

- troca de emojis por Material Symbols;
- visual mais consistente com contexto financeiro/gamificado;
- task viewer mais claro e mais conectado a regra real da task;
- atalhos do dashboard mais profissionais.

### 6.4 Layout global

Arquivo alterado:

- `resources/views/layouts/blueapp.blade.php`

Melhoria:

- Material Symbols agora carregado globalmente no layout `blueapp`, evitando o problema de icones nao exibidos nas telas que dependem dele.

## 7. API e rotas relevantes

### Web

Principais rotas do usuario em `routes/user.php`:

- `/home`
- `/profile`
- `/deposit`
- `/withdraw`
- `/tasks`
- `/tasks/{id}`
- `/packages`
- `/history`
- `/invite`
- `/my-team`
- `/gamification/collect/{id}`

### API autenticada por Sanctum

Definidas em `routes/api.php`:

- `POST /api/deposit`
- `POST /api/withdraw`
- `POST /api/purchase/confirmation/{id}`
- endpoints de antifraude em `/api/fraud/...`

### Webhooks

- `/api/webhook/pixup/confirm_deposits`
- `/api/webhook/valorion/confirm_deposits`
- `/api/webhook/vizionpay/{type}`

## 8. Painel administrativo

### Tasks

Arquivos:

- `app/Http/Controllers/Admin/TaskController.php`
- `resources/views/admin/pages/task/index.blade.php`
- `resources/views/admin/pages/task/insert.blade.php`

Capacidades apos o ajuste:

- cadastrar titulo;
- cadastrar descricao;
- cadastrar video embed;
- definir tempo minimo;
- definir ordem de exibicao;
- definir icone Material;
- ativar/inativar task.

## 9. Pontos de atencao tecnicos

### 9.1 Inconsistencia entre camadas antigas e novas

O projeto ainda possui coexistencia entre views antigas em `resources/views/app/...` e views novas em `resources/views/blue-app/...`.

Impacto:

- dificulta manutencao;
- pode gerar confusao sobre qual view e a rota real;
- aumenta chance de ajustes feitos no arquivo errado.

Recomendacao:

- mapear e aposentar views legadas nao utilizadas;
- consolidar UX no `blue-app`.

### 9.2 Fluxos duplicados de compra

Existem dois caminhos relevantes em `PurchaseController`:

- `purchaseStore` / `purchaseConfirmation`
- `purchaseConfirmationWeb`

Recomendacao:

- concentrar a regra final em service unico, reduzindo divergencia futura.

### 9.3 Tipagem monetaria

O sistema mistura floats e campos de saldo. Houve um ajuste para permitir credito decimal com mais seguranca no model `User`, mas idealmente valores monetarios deveriam convergir para estrategia unica.

Recomendacao:

- padronizar uso de decimal no banco e no dominio;
- revisar metodos que ainda assumem inteiro em comentarios ou nomes legados.

### 9.4 Validacao de task baseada em tempo minimo

A melhoria atual resolve o problema de confirmacao imediata sem abrir a task, mas nao substitui validacao real do player.

Recomendacao:

- se esse modulo for central para o negocio, evoluir para validacao por evento de video e heartbeat.

## 10. Arquivos alterados nesta entrega

### Backend

- `app/Models/User.php`
- `app/Models/Task.php`
- `app/Services/TaskService.php`
- `app/Http/Controllers/user/TaskController.php`
- `app/Http/Controllers/Admin/TaskController.php`
- `database/migrations/2026_03_26_000001_expand_task_metadata_and_guards.php`

### Frontend

- `resources/views/layouts/blueapp.blade.php`
- `resources/views/blue-app/deposit.blade.php`
- `resources/views/blue-app/withdraw/index.blade.php`
- `resources/views/blue-app/tasks/index.blade.php`
- `resources/views/blue-app/tasks/show.blade.php`
- `resources/views/blue-app/dashboard.blade.php`
- `resources/views/admin/pages/task/index.blade.php`
- `resources/views/admin/pages/task/insert.blade.php`

## 11. Proximo passo recomendado

Se quisermos levar esse sistema para um nivel mais robusto, a proxima rodada deveria focar em:

1. consolidacao das views legadas e novas;
2. endurecimento antifraude e conciliacao financeira;
3. validacao real de consumo de video nas tasks;
4. padronizacao monetaria e de ledger;
5. testes automatizados dos fluxos criticos.
