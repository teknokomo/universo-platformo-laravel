# start-srv — Бэкенд-пакет стартовых страниц

Бэкенд-пакет для стартовых страниц Universo Platformo. Обеспечивает прокси-аутентификацию через Supabase и маршрутизацию стартовых страниц через Laravel-бэкенд.

## Обзор

Этот пакет отвечает за:

- **Прокси-аутентификацию Supabase** — вход, регистрация, выход, обновление токена, пользовательская сессия
- **Управление сессиями** — хранит токены Supabase в Laravel-сессиях (на стороне сервера)
- **API-маршруты** — RESTful-эндпоинты, используемые фронтенд-пакетом `start-frt`

## Структура пакета

```
start-srv/
├── base/                          # Основная реализация
│   ├── src/
│   │   ├── Controllers/
│   │   │   └── AuthController.php # API-эндпоинты аутентификации
│   │   ├── Services/
│   │   │   └── SupabaseAuthService.php # Клиент Supabase REST API
│   │   └── Providers/
│   │       └── StartServiceProvider.php # Регистрация пакета
│   ├── routes/
│   │   └── api.php                # Определения API-маршрутов
│   ├── tests/
│   │   └── Feature/
│   │       └── AuthControllerTest.php
│   └── composer.json
├── README.md
└── README-RU.md
```

## API-эндпоинты

Все маршруты имеют префикс `/api/v1/auth`.

| Метод | Путь | Описание |
|-------|------|----------|
| `POST` | `/api/v1/auth/login` | Вход с email и паролем |
| `POST` | `/api/v1/auth/register` | Регистрация нового аккаунта |
| `POST` | `/api/v1/auth/logout` | Выход из текущей сессии |
| `GET` | `/api/v1/auth/user` | Получение текущего аутентифицированного пользователя |
| `POST` | `/api/v1/auth/refresh` | Обновление токена доступа |

### Примеры запросов/ответов

**POST /api/v1/auth/login**

```json
// Запрос
{
    "email": "user@example.com",
    "password": "secret123"
}

// Ответ 200
{
    "user": { "id": "...", "email": "user@example.com" },
    "authenticated": true
}

// Ответ 401 (неверные учётные данные)
{
    "error": "Invalid login credentials"
}
```

**GET /api/v1/auth/user**

```json
// Ответ 200 (аутентифицирован)
{
    "user": { "id": "...", "email": "user@example.com" },
    "authenticated": true
}

// Ответ 200 (не аутентифицирован)
{
    "user": null,
    "authenticated": false
}
```

## Конфигурация

Добавьте учётные данные Supabase в файл `.env`:

```env
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-supabase-anon-key
SUPABASE_SERVICE_KEY=your-supabase-service-key
```

## Регистрация

Пакет регистрируется автоматически через механизм обнаружения пакетов Laravel. Объявлен в корневом `composer.json`:

```json
{
    "repositories": [
        { "type": "path", "url": "packages/start-srv/base" }
    ],
    "require": {
        "universo/start-srv": "@dev"
    }
}
```

## Процесс аутентификации

1. **Фронтенд** отправляет учётные данные на `/api/v1/auth/login`
2. **AuthController** перенаправляет их в Supabase REST API через `SupabaseAuthService`
3. **Supabase** возвращает токен доступа, токен обновления и данные пользователя
4. **AuthController** сохраняет токены в Laravel-сессии и возвращает на фронтенд только очищённые данные пользователя
5. **Фронтенд** может вызвать `/api/v1/auth/user` в любое время для проверки статуса аутентификации
6. Если токен доступа истёк, бэкенд автоматически пытается обновить его, используя сохранённый токен обновления

## Тестирование

```bash
php artisan test --filter AuthControllerTest
```
