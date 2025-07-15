# Symfony Backend

## Setup Instructions

1. Run `composer install`
2. (Optional) setup database within a docker container:
   ```
   docker compose up -d
   ```
3. Configure `.env.local` with your DB credentials
4. Run:
   ```
   php bin/console doctrine:database:create (not required if database was created within docker container)
   php bin/console doctrine:migrations:migrate
   symfony serve
   ```

## API Endpoints

- `GET /api/tracks`
- `POST /api/tracks`
- `PUT /api/tracks/{id}`
