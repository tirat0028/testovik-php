# Task Manager API

RESTful API для управления списком задач с аутентификацией и уведомлениями.

## Требования

*   PHP 8.1 или выше
*   Composer
*   PostgreSQL

## Установка

1.  Создайте новый проект Laravel (если еще не создан):
    ```bash
    composer create-project laravel/laravel task_app
    cd task_app
    ```

2.  Перенесите исходные файлы из папки `task_api_source` в корень вашего проекта.

3.  Установите зависимости:
    ```bash
    composer install
    ```

4.  Настройте подключение к базе данных в файле `.env`:
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=task_app
    DB_USERNAME=postgres
    DB_PASSWORD=your_password
    ```

5.  Запустите миграции:
    ```bash
    php artisan migrate
    ```

6.  Запустите локальный сервер:
    ```bash
    php artisan serve
    ```

API будет доступно по адресу `http://localhost:8000/api`.

## Функционал

### Аутентификация
*   `POST /api/login` - Получение API токена (Sanctum)
*   `POST /api/logout` - Отзыв токена

### Задачи
*   `GET /api/tasks` - Получить список задач (пагинация)
*   `POST /api/tasks` - Создать новую задачу
*   `GET /api/tasks/{id}` - Просмотр задачи
*   `PUT /api/tasks/{id}` - Обновление задачи
*   `DELETE /api/tasks/{id}` - Удаление задачи

### Консольные команды
Проверка просроченных задач:
```bash
php artisan tasks:check-overdue
```

## Тестирование
Для запуска автотестов выполните:
```bash
php artisan test
```
