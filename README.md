# Full-stack application for invoice management.

## Quick Start

To get the project running locally, follow these steps:

1. **Ensure Docker is installed and running.**
2. **Configure environment variables**: Check the `.env` file in the root directory. Ensure all necessary variables (like `POSTGRES_VERSION`, `API_PORT`, etc.) are set.
```bash
cp .env.dev .env
```
3. **Start the environment**:
   ```bash
   docker compose up -d
   ```
4. **Access the application**: The application will be available via the domain/port specified in your `.env` file (configured through Nginx).
