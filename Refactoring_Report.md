# Relatório de Refatoração - GlamourTime

## Introdução

Este relatório detalha a aplicação de Design Patterns no projeto GlamourTime, conforme o guia fornecido. O objetivo principal foi refatorar o código existente para melhorar a organização, manutenibilidade, testabilidade e escalabilidade da aplicação, seguindo os princípios dos padrões de projeto.

## Padrões de Projeto Aplicados

### 1. Repository Pattern

**Objetivo:** Isolar a lógica de acesso a dados, permitindo que a aplicação interaja com o armazenamento de dados de forma abstrata, sem se preocupar com os detalhes de implementação (e.g., MySQL, MongoDB, API externa).

**Aplicação no GlamourTime:**

Foram criadas interfaces e implementações de repositórios para as entidades `Appointment` (Agendamento) e `AvailableSlot` (Horário Disponível). Isso desacopla os Controllers e Services da lógica direta do Eloquent (ORM do Laravel), facilitando futuras mudanças no banco de dados ou na forma como os dados são acessados.

- `app/Repositories/AppointmentRepositoryInterface.php`
- `app/Repositories/AppointmentRepository.php`
- `app/Repositories/AvailableSlotRepositoryInterface.php`
- `app/Repositories/AvailableSlotRepository.php`

**Benefícios:**
- **Desacoplamento:** A lógica de negócio não precisa saber como os dados são persistidos.
- **Testabilidade:** Facilita a criação de testes unitários, pois os repositórios podem ser facilmente "mockados".
- **Manutenibilidade:** Mudanças no ORM ou no banco de dados afetam apenas a implementação do repositório, não o restante da aplicação.

### 2. Service Pattern

**Objetivo:** Centralizar regras de negócio complexas em classes de serviço dedicadas, promovendo a coesão e evitando a duplicação de código em Controllers ou Models.

**Aplicação no GlamourTime:**

As lógicas de agendamento, reagendamento, cancelamento, confirmação e rejeição de agendamentos, bem como a criação de horários disponíveis, foram movidas para classes de serviço.

- `app/Services/AppointmentService.php`
- `app/Services/AvailableSlotService.php`
- `app/Services/NotificationService.php`

**Benefícios:**
- **Organização:** Regras de negócio estão em um único local, facilitando a compreensão e manutenção.
- **Reusabilidade:** Os serviços podem ser injetados e utilizados em diferentes partes da aplicação.
- **Consistência:** Garante que as regras de negócio sejam aplicadas de forma uniforme.

### 3. Factory Pattern

**Objetivo:** Fornecer uma interface para criar objetos em uma superclasse, mas permitir que as subclasses alterem o tipo de objetos que serão criados. No contexto do GlamourTime, foi utilizado para criar diferentes tipos de notificações e dados de teste de forma centralizada.

**Aplicação no GlamourTime:**

Foi criada uma `NotificationFactory` para gerar arrays de dados padronizados para diferentes tipos de notificações (agendamento confirmado, cancelado, concluído, lembrete, novo horário disponível) e também para dados de teste de usuários e agendamentos.

- `app/Factories/NotificationFactory.php`

**Benefícios:**
- **Flexibilidade:** Adicionar novos tipos de notificações ou dados de teste é mais fácil, sem modificar o código cliente.
- **Desacoplamento:** O código que utiliza a fábrica não precisa conhecer os detalhes de como os objetos são criados.
- **Consistência:** Garante que os objetos sejam criados de forma padronizada.

### 4. Observer Pattern

**Objetivo:** Definir uma dependência um-para-muitos entre objetos, de modo que quando um objeto muda de estado, todos os seus dependentes são notificados e atualizados automaticamente.

**Aplicação no GlamourTime:**

Um `AppointmentObserver` foi implementado para "observar" as mudanças no modelo `Appointment`. Quando um agendamento é criado ou seu status é atualizado (para confirmado, cancelado ou concluído), o observador dispara notificações apropriadas usando o `NotificationService` e a `NotificationFactory`.

- `app/Observers/AppointmentObserver.php`

**Benefícios:**
- **Desacoplamento:** O objeto que muda de estado (Subject) não precisa saber quem são seus observadores.
- **Reatividade:** Permite que a aplicação reaja a eventos de forma organizada e extensível.
- **Manutenibilidade:** Adicionar novos comportamentos em resposta a eventos é simples, sem modificar o código existente.

### 5. Strategy Pattern

**Objetivo:** Definir uma família de algoritmos, encapsulá-los e torná-los intercambiáveis. Permite que o algoritmo varie independentemente dos clientes que o utilizam.

**Aplicação no GlamourTime:**

Foi implementado para gerenciar diferentes estratégias de cálculo de preços ou descontos. Uma interface `DiscountStrategy` foi criada, com implementações como `NoDiscountStrategy`, `FlexibleScheduleDiscountStrategy` e `LoyaltyDiscountStrategy`.

- `app/Strategies/DiscountStrategy.php`
- `app/Strategies/NoDiscountStrategy.php`
- `app/Strategies/FlexibleScheduleDiscountStrategy.php`
- `app/Strategies/LoyaltyDiscountStrategy.php`
- `app/Services/PricingService.php`

**Benefícios:**
- **Flexibilidade:** Novas estratégias de desconto podem ser adicionadas facilmente sem alterar o código principal de cálculo de preço.
- **Reusabilidade:** As estratégias podem ser reutilizadas em diferentes contextos.
- **Manutenibilidade:** A lógica de cada estratégia é isolada, facilitando a modificação e o teste.

## Configuração do Laravel

Para que os padrões de projeto funcionem corretamente, o `RepositoryServiceProvider` foi registrado no `AppServiceProvider` do Laravel, garantindo que as dependências sejam resolvidas automaticamente pelo contêiner de serviços.

Além disso, o `AppointmentObserver` precisa ser registrado no `App/Providers/EventServiceProvider.php` para que o Laravel possa "escutar" os eventos do modelo `Appointment`.

## Conclusão

A aplicação desses Design Patterns no projeto GlamourTime resultou em uma arquitetura mais robusta e organizada. O código agora é mais fácil de entender, manter, testar e estender, preparando a aplicação para futuras funcionalidades e mudanças tecnológicas. As responsabilidades foram claramente separadas, e a dependência entre os módulos foi reduzida, seguindo as melhores práticas de desenvolvimento de software.
