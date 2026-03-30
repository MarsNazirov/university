# Университет - Модуль «Общежитие»

Модульная ERP‑система для автоматизации общежития вуза. Разработана на **Laravel 11** с использованием **Docker**, **PostgreSQL**, **Redis** и **FilamentPHP**.

## Технологический стек

- **Backend:** PHP 8.2+, Laravel 11
- **Database:** PostgreSQL
- **Admin Panel:** FilamentPHP v3
- **Infrastructure:** Custom Docker (Nginx, PHP‑FPM, Postgres, Redis)
- **Testing:** Pest

## Ключевые архитектурные решения

1. **Транзакционная бизнес‑логика**  
   Реализован алгоритм заселения/выселения студентов в сервисе `RoomService`. Использованы транзакции БД для предотвращения гонки состояний и перенаселения комнат.

2. **СУБД PostgreSQL**  
   Спроектирована реляционная структура: студенты, комнаты, история заселений. Статусы комнат обновляются автоматически в зависимости от заполненности.

3. **Кастомный Docker**  
   Окружение собрано вручную через Docker(Nginx, PHP‑FPM, Postgres, Redis).

4. **Админ‑панель Filament**  
   Быстрое развертывание CRUD‑интерфейсов, кастомных фильтров и действий для роли коменданта.

5. **Автоматическое тестирование**  
   Ключевые сценарии (заселение в переполненную комнату, смена статусов, логирование истории) покрыты функциональными тестами на **Pest**.

## Установка и запуск

```bash
# 1. Клонирование репозитория
git clone https://github.com/MarsNazirov/university-2.0.git
cd university-2.0

# 2. Подготовка .env
cp .env.example .env

# 3. Сборка и запуск контейнеров
docker-compose up -d --build

# 4. Установка зависимостей
docker-compose exec php composer install

# 5. Генерация ключа и миграции
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate --seed
docker-compose exec php php artisan storage:link

# 6. Создание администратора Filament
docker-compose exec php php artisan make:filament-user

# 7. Доступные адреса
# http://localhost/students        - кастомная страница студентов
# http://localhost/rooms           - кастомная страница комнат
# http://localhost/history         - история заселений
# http://localhost/admin           - админ‑панель Filament

# 8. Запуск тестов
docker-compose exec php php artisan test
