# Full-stack application for invoice management.

A modern full-stack web application featuring a **PHP API (Symfony) backend** and a **Nuxt.js frontend**, completely containerized using **Docker**.

## Project Structure

The root directory is organized as follows:

```text
.
├── compose.yml              # Main Docker Compose configuration
├── compose.override.yml     # Local environment overrides
├── compose.dev.yml          # Development-specific Docker configuration
│
├── docker/                  # Docker infrastructure configurations
│   ├── backend/
│   │   └── Dockerfile       # Production/dev image setup for Symfony
│   ├── frontend/
│   │   └── Dockerfile       # Production/dev image setup for Nuxt.js
│   └── nginx/
│       ├── default.conf     # Nginx reverse proxy / web server configuration
│       └── Dockerfile       # Custom Nginx image setup
│
├── backend/                 # PHP API (Symfony Framework)
└── frontend/                # Web User Interface (Nuxt.js)
```

### Backend Structure
The backend is built on Symfony using a **Layered Architecture**. The primary goal of this design is to decouple HTTP request handling from core business logic, ensuring the codebase remains highly testable, scalable, and easy to maintain.
#### Key Architectural Decisions:

* **Controller Layer (Thin Controllers)**:

    Controllers function strictly as orchestrators. They intercept the incoming HTTP request, pass the data directly to the service layer, and return a JSON response with the appropriate status code. All business logic is kept entirely out of this layer.
* **Service Layer (Business Logic)**:

    The `InvoiceService` is responsible for all invoice-related operations. This layer encapsulates critical business rules, including:
  * Automatic calculation of `gross_amount (net + vat)`.
  * State checks (e.g., preventing edits unless the invoice status is `pending`).
  * Date consistency validations (`due_date >= issue_date`).

      Extracting this logic makes it fully reusable across other parts of the system—such as CLI console commands or message queues—independently of the HTTP lifecycle.
* **Data Transfer Objects (DTOs) & Validation**:

    Incoming request bodies (`POST`, `PUT`) are mapped to dedicated **Data Transfer Objects (DTOs)**. This approach provides several key benefits:
    * Explicitly defines the exact schema and fields expected by the API.
    * Leverages the native **Symfony Validator** (via attributes) to enforce strict types, required fields, and unique invoice numbers.
    * Prevents direct, unsafe mapping of raw JSON requests straight onto database entities.
* **Entity & Repository (Data Layer)**:
    Powered by Doctrine ORM, the `Invoice` entity remains a clean data model containing only field mappings, basic getters, and setters. Complex filtering, custom queries, and data retrieval logic are strictly isolated within the `InvoiceRepository`.

#### Data Flow:
`Request` $\rightarrow$ `Controller` $\rightarrow$ `DTO (Validation)` $\rightarrow$ `InvoiceService (Business Logic)` $\rightarrow$ `Entity` $\rightarrow$ `Database`

#### Directory Structure:
```
backend/src/
├── Controller/
│   └── InvoiceController.php    # Route handling and HTTP responses
├── Dto/
│   └── InvoiceRequestDto.php    # Incoming data validation via Symfony Validator
├── Entity/
│   └── Invoice.php              # Data model (Doctrine Entity)
├── Repository/
│   └── InvoiceRepository.php    # Database queries and custom filtering
└── Service/
    └── InvoiceService.php       # Business logic and financial calculations
```

### Frontend Structure 

For the frontend application, I adopted a modular approach aligned with the **Nuxt 4 directory convention** to enforce a strict **Separation of Concerns**.

#### Key Architectural Decisions:

*  **Data & Logic Layers (Composables):**

    All API orchestration and frontend business logic are completely decoupled into `composables/`. For instance, `useInvoices.ts` encapsulates methods for fetching lists, retrieving single records, and performing updates. This setup:

    *   Eliminates code duplication across multiple pages.
    *   Makes reactive logic easily testable independently of the UI.
    *   Keeps components "thin" and focused solely on rendering data and capturing user interactions.

* **Component Organization:**

    Components are cleanly split into two distinct categories:

    *   `components/ui/` – Generic, atomic, and highly reusable design system elements (buttons, inputs, badges) completely isolated from business rules.
    *   `components/invoice/` – Feature-specific components dedicated strictly to the invoice domain (e.g., `InvoiceForm.vue`, `InvoiceTable.vue`).

* **Validation & Types (Zod + Vee-Validate):**

    To ensure strict runtime data integrity, I implemented a validation layer using **Zod** schemas inside `utils/validation.ts`. These schemas serve as a single source of truth-defining domain types while simultaneously driving complex form validation rules via **Vee-Validate**.

* **State Management:**

    Given the focused scope of the application, I leveraged Nuxt’s native `useState` mechanism to track global loading indicators and error states, ensuring instant reactivity across page transitions without introducing heavy state libraries.

* **API Interception & Handling:**

    Data fetching relies on native `useFetch` and `$fetch` utilities. Every request and response payload is strongly typed to guarantee end-to-end **Type-Safety** from the Symfony API response straight into the UI components.

#### Directory Structure:

```
frontend/app/
├── components/
│   ├── ui/              # Generic UI components (Button, Input, Badge)
│   └── invoice/         # Feature-specific components (InvoiceForm, InvoiceList)
├── composables/
│   └── useInvoices.ts   # API calls & business logic for invoices
├── pages/
│   └── invoices/
│       ├── index.vue     # List page
│       └── [id].vue      # Details & Edit page
├── utils/
│   ├── validation.ts    # Zod schemas for invoice validation
│   └── formatters.ts    # Currency and date formatting helpers
└── types/
    └── invoice.ts       # TypeScript interfaces
```

## Tech Stack

*   **Backend:** PHP, Symfony
*   **Frontend:** Nuxt.js (Vue.js), TypeScript
*   **Database:** PostgreSQL
*   **Containerization:** Docker, Docker Compose

## Getting Started

### Prerequisites

Ensure you have the following installed on your local machine:
*   [Docker](https://docker.com) (including Docker Compose)

### Installation & Local Development

1. **Clone the repository:**
```bash
git clone https://github.com/szelikov/invoices
cd invoices
```

2. **Configure environment variables:**
**Configure environment variables**: Check the `.env` file in the root directory. Ensure all necessary variables (like `POSTGRES_VERSION`, `API_PORT`, etc.) are set.
```bash
cp .env.dev .env
cp compose.dev.yml compose.override.yml
docker compose build
docker compose run --rm --no-deps backend composer install
docker compose run --rm --no-deps frontend pnpm install
```

3. **Start the Docker containers:**
For development, run the containers using the development configuration override:
```bash
docker compose up -d
docker compose exec backend php bin/console doctrine:migrations:migrate
```
*Optional. fill the db*
```bash
docker compose exec backend php bin/console doctrine:fixtures:load
```
4. **Verify the services:**
The application will be available via the domain/port (e.g., `http://localhost:8088`) specified in your `.env` file (configured through Nginx).

## Docker Management

*   **Stop containers:**
```bash
docker compose down
```
*   **View logs:**
```bash
docker compose logs -f
```
