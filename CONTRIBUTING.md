# Contributing to Universo Platformo Laravel

Thank you for your interest in contributing to Universo Platformo Laravel! This document provides guidelines and instructions for contributing to the project.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and collaborative environment. We welcome contributions from developers of all skill levels.

## How to Contribute

### Reporting Issues

1. **Check existing issues** to avoid duplicates
2. **Use the issue template** available in `.github/ISSUE_TEMPLATE/` (if available)
3. **Follow bilingual format**: Create issues in English with Russian translation in spoiler tags
4. **Include details**: Provide clear steps to reproduce, expected behavior, and actual behavior
5. **Add appropriate labels**: Follow `.github/instructions/github-labels.md`

Example issue format:

```markdown
# Issue Title in English

Description of the issue in English.

## Steps to Reproduce

1. Step one
2. Step two
3. Step three

## Expected Behavior

What should happen.

## Actual Behavior

What actually happens.

<details>
<summary>In Russian</summary>

# Заголовок Issue на русском

Описание проблемы на русском языке.

## Шаги для воспроизведения

1. Шаг первый
2. Шаг второй
3. Шаг третий

## Ожидаемое поведение

Что должно происходить.

## Фактическое поведение

Что на самом деле происходит.
</details>
```

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Follow naming conventions**: Use descriptive branch names (e.g., `feature/clusters-api`, `fix/auth-bug`)
3. **Make focused changes**: Keep PRs small and focused on a single feature or fix
4. **Write tests**: Include tests for new functionality
5. **Update documentation**: Keep README files and documentation in sync
6. **Follow bilingual format**: See `.github/instructions/github-pr.md`

Pull Request checklist:

- [ ] Code follows Laravel conventions and PSR-12 standards
- [ ] All tests pass (`php artisan test`)
- [ ] Documentation is updated (both English and Russian)
- [ ] Commit messages are clear and descriptive
- [ ] PR title starts with issue number (e.g., `GH123 Add clusters functionality`)
- [ ] PR description includes English text and Russian in spoiler tags

### Development Workflow

1. **Clone the repository**
   ```bash
   git clone https://github.com/teknokomo/universo-platformo-laravel.git
   cd universo-platformo-laravel
   ```

2. **Create a new branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Make your changes**
   - Write code following Laravel best practices
   - Add tests for new functionality
   - Update documentation

6. **Run tests**
   ```bash
   php artisan test
   ./vendor/bin/pint  # Code style fixer
   ```

7. **Commit your changes**
   ```bash
   git add .
   git commit -m "GH123 Add feature description"
   ```

8. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

9. **Create Pull Request**
   - Go to GitHub and create a PR from your fork
   - Fill in the PR template with English and Russian descriptions
   - Link related issues
   - Request review

## Coding Standards

### PHP Code Style

- Follow **PSR-12** coding standards
- Use **Laravel conventions** for naming and structure
- Use **type hints** for method parameters and return types
- Write **descriptive variable names** and **clear comments**

Example:

```php
<?php

namespace App\Services;

use App\Models\Cluster;
use Illuminate\Support\Collection;

class ClusterService
{
    /**
     * Get all clusters for the authenticated user.
     *
     * @return Collection<Cluster>
     */
    public function getUserClusters(): Collection
    {
        return auth()->user()->clusters()
            ->with(['domains', 'resources'])
            ->get();
    }
}
```

### Package Structure

When creating a new package:

```
packages/
└── feature-name-srv/
    ├── base/
    │   ├── src/
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   ├── Services/
    │   │   └── Providers/
    │   ├── routes/
    │   │   └── api.php
    │   ├── database/
    │   │   └── migrations/
    │   ├── tests/
    │   │   └── Feature/
    │   └── composer.json
    ├── README.md
    └── README.ru.md
```

### Database Migrations

- Use descriptive migration names
- Include both `up()` and `down()` methods
- Use Laravel's schema builder
- Add appropriate indexes

Example:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};
```

### Testing

- Write **Feature tests** for API endpoints
- Write **Unit tests** for services and utilities
- Use **factories** for test data
- Aim for **high test coverage**

Example test:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cluster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClusterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_clusters(): void
    {
        $user = User::factory()->create();
        $clusters = Cluster::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/clusters');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
```

## Documentation

### Bilingual Documentation Requirements

All documentation must be provided in both English and Russian:

- **README.md** (English) and **README.ru.md** (Russian)
- **Identical structure**: Same sections, same number of lines
- **Complete translation**: Not just summaries, full translation
- **Synchronized updates**: Update both versions together

See `.github/instructions/i18n-docs.md` for detailed guidelines.

### Documentation Style

- Use clear, concise language
- Include code examples
- Provide context and rationale
- Keep formatting consistent
- Use proper Markdown syntax

## Package Development

### Creating a New Package

1. **Plan the package structure**
   - Determine if frontend (-frt) or backend (-srv)
   - Design the API and data models
   - Plan dependencies

2. **Create package directories**
   ```bash
   mkdir -p packages/myfeature-srv/base/{src,routes,database,tests}
   ```

3. **Create composer.json**
   ```json
   {
     "name": "universo/myfeature-srv",
     "type": "library",
     "require": {
       "php": "^8.2",
       "laravel/framework": "^11.0"
     },
     "autoload": {
       "psr-4": {
         "Universo\\MyFeature\\": "base/src/"
       }
     }
   }
   ```

4. **Register in root composer.json**
   ```json
   {
     "repositories": [
       {
         "type": "path",
         "url": "packages/myfeature-srv"
       }
     ],
     "require": {
       "universo/myfeature-srv": "@dev"
     }
   }
   ```

5. **Create documentation**
   - packages/myfeature-srv/README.md
   - packages/myfeature-srv/README.ru.md

6. **Implement functionality**
   - Models, Controllers, Services
   - Routes and API endpoints
   - Migrations
   - Tests

7. **Update package README** in packages directory

## Communication

- **Issues**: For bug reports and feature requests
- **Pull Requests**: For code contributions
- **Discussions**: For questions and general discussion
- **Telegram**: [@Vladimir_Levadnij](https://t.me/Vladimir_Levadnij)
- **Email**: [universo.pro@yandex.com](mailto:universo.pro@yandex.com)

## License

By contributing to Universo Platformo Laravel, you agree that your contributions will be licensed under the MIT License.

## Questions?

If you have questions about contributing, please:

1. Check existing documentation
2. Search closed issues for similar questions
3. Ask in Discussions or contact the team

Thank you for contributing to Universo Platformo Laravel!

<details>
<summary>In Russian</summary>

# Участие в разработке Universo Platformo Laravel

Спасибо за ваш интерес к участию в разработке Universo Platformo Laravel! Этот документ содержит руководства и инструкции по участию в проекте.

## Кодекс поведения

Участвуя в этом проекте, вы соглашаетесь поддерживать уважительную и совместную среду. Мы приветствуем вклад от разработчиков всех уровней квалификации.

## Как внести вклад

### Сообщение о проблемах

1. **Проверьте существующие issue**, чтобы избежать дубликатов
2. **Используйте шаблон issue**, доступный в `.github/ISSUE_TEMPLATE/` (если доступен)
3. **Следуйте двуязычному формату**: Создавайте issue на английском с переводом на русский в спойлер-тегах
4. **Включайте детали**: Предоставляйте чёткие шаги для воспроизведения, ожидаемое поведение и фактическое поведение
5. **Добавляйте соответствующие метки**: Следуйте `.github/instructions/github-labels.md`

Пример формата issue:

```markdown
# Заголовок Issue на английском

Описание проблемы на английском.

## Шаги для воспроизведения

1. Шаг первый
2. Шаг второй
3. Шаг третий

## Ожидаемое поведение

Что должно происходить.

## Фактическое поведение

Что на самом деле происходит.

<details>
<summary>In Russian</summary>

# Заголовок Issue на русском

Описание проблемы на русском языке.

## Шаги для воспроизведения

1. Шаг первый
2. Шаг второй
3. Шаг третий

## Ожидаемое поведение

Что должно происходить.

## Фактическое поведение

Что на самом деле происходит.
</details>
```

### Pull Request'ы

1. **Форкните репозиторий** и создайте свою ветку от `main`
2. **Следуйте соглашениям об именовании**: Используйте описательные имена веток (например, `feature/clusters-api`, `fix/auth-bug`)
3. **Делайте сфокусированные изменения**: Делайте PR небольшими и сосредоточенными на одной функции или исправлении
4. **Пишите тесты**: Включайте тесты для новой функциональности
5. **Обновляйте документацию**: Синхронизируйте файлы README и документацию
6. **Следуйте двуязычному формату**: См. `.github/instructions/github-pr.md`

Чек-лист для Pull Request:

- [ ] Код следует соглашениям Laravel и стандартам PSR-12
- [ ] Все тесты проходят (`php artisan test`)
- [ ] Документация обновлена (на английском и русском)
- [ ] Сообщения коммитов ясные и описательные
- [ ] Заголовок PR начинается с номера issue (например, `GH123 Add clusters functionality`)
- [ ] Описание PR включает английский текст и русский в спойлер-тегах

### Рабочий процесс разработки

1. **Клонируйте репозиторий**
   ```bash
   git clone https://github.com/teknokomo/universo-platformo-laravel.git
   cd universo-platformo-laravel
   ```

2. **Создайте новую ветку**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Установите зависимости**
   ```bash
   composer install
   npm install
   ```

4. **Настройте окружение**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Внесите свои изменения**
   - Пишите код, следуя лучшим практикам Laravel
   - Добавляйте тесты для новой функциональности
   - Обновляйте документацию

6. **Запустите тесты**
   ```bash
   php artisan test
   ./vendor/bin/pint  # Исправление стиля кода
   ```

7. **Закоммитьте свои изменения**
   ```bash
   git add .
   git commit -m "GH123 Add feature description"
   ```

8. **Отправьте в свой форк**
   ```bash
   git push origin feature/your-feature-name
   ```

9. **Создайте Pull Request**
   - Перейдите на GitHub и создайте PR из своего форка
   - Заполните шаблон PR с описаниями на английском и русском
   - Свяжите связанные issue
   - Запросите проверку

## Стандарты кодирования

### Стиль кода PHP

- Следуйте стандартам кодирования **PSR-12**
- Используйте **соглашения Laravel** для именования и структуры
- Используйте **типизацию** для параметров методов и возвращаемых типов
- Пишите **описательные имена переменных** и **чёткие комментарии**

Пример:

```php
<?php

namespace App\Services;

use App\Models\Cluster;
use Illuminate\Support\Collection;

class ClusterService
{
    /**
     * Получить все кластеры для аутентифицированного пользователя.
     *
     * @return Collection<Cluster>
     */
    public function getUserClusters(): Collection
    {
        return auth()->user()->clusters()
            ->with(['domains', 'resources'])
            ->get();
    }
}
```

### Структура пакета

При создании нового пакета:

```
packages/
└── feature-name-srv/
    ├── base/
    │   ├── src/
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   ├── Services/
    │   │   └── Providers/
    │   ├── routes/
    │   │   └── api.php
    │   ├── database/
    │   │   └── migrations/
    │   ├── tests/
    │   │   └── Feature/
    │   └── composer.json
    ├── README.md
    └── README.ru.md
```

### Миграции базы данных

- Используйте описательные имена миграций
- Включайте методы `up()` и `down()`
- Используйте построитель схем Laravel
- Добавляйте соответствующие индексы

Пример:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};
```

### Тестирование

- Пишите **Feature тесты** для API endpoints
- Пишите **Unit тесты** для сервисов и утилит
- Используйте **фабрики** для тестовых данных
- Стремитесь к **высокому покрытию тестами**

Пример теста:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cluster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClusterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_clusters(): void
    {
        $user = User::factory()->create();
        $clusters = Cluster::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/clusters');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
```

## Документация

### Требования к двуязычной документации

Вся документация должна быть предоставлена на английском и русском языках:

- **README.md** (английский) и **README.ru.md** (русский)
- **Идентичная структура**: Те же разделы, то же количество строк
- **Полный перевод**: Не просто резюме, полный перевод
- **Синхронизированные обновления**: Обновляйте обе версии вместе

См. `.github/instructions/i18n-docs.md` для подробных руководств.

### Стиль документации

- Используйте чёткий, лаконичный язык
- Включайте примеры кода
- Предоставляйте контекст и обоснование
- Сохраняйте последовательное форматирование
- Используйте правильный синтаксис Markdown

## Разработка пакетов

### Создание нового пакета

1. **Спланируйте структуру пакета**
   - Определите, фронтенд (-frt) или бэкенд (-srv)
   - Спроектируйте API и модели данных
   - Спланируйте зависимости

2. **Создайте каталоги пакета**
   ```bash
   mkdir -p packages/myfeature-srv/base/{src,routes,database,tests}
   ```

3. **Создайте composer.json**
   ```json
   {
     "name": "universo/myfeature-srv",
     "type": "library",
     "require": {
       "php": "^8.2",
       "laravel/framework": "^11.0"
     },
     "autoload": {
       "psr-4": {
         "Universo\\MyFeature\\": "base/src/"
       }
     }
   }
   ```

4. **Зарегистрируйте в корневом composer.json**
   ```json
   {
     "repositories": [
       {
         "type": "path",
         "url": "packages/myfeature-srv"
       }
     ],
     "require": {
       "universo/myfeature-srv": "@dev"
     }
   }
   ```

5. **Создайте документацию**
   - packages/myfeature-srv/README.md
   - packages/myfeature-srv/README.ru.md

6. **Реализуйте функциональность**
   - Модели, контроллеры, сервисы
   - Маршруты и API endpoints
   - Миграции
   - Тесты

7. **Обновите README пакета** в каталоге packages

## Коммуникация

- **Issues**: Для отчётов об ошибках и запросов функций
- **Pull Requests**: Для вклада в код
- **Discussions**: Для вопросов и общего обсуждения
- **Telegram**: [@Vladimir_Levadnij](https://t.me/Vladimir_Levadnij)
- **Email**: [universo.pro@yandex.com](mailto:universo.pro@yandex.com)

## Лицензия

Участвуя в разработке Universo Platformo Laravel, вы соглашаетесь, что ваш вклад будет лицензирован под лицензией MIT.

## Вопросы?

Если у вас есть вопросы по участию, пожалуйста:

1. Проверьте существующую документацию
2. Поищите в закрытых issues похожие вопросы
3. Спросите в Discussions или свяжитесь с командой

Спасибо за участие в разработке Universo Platformo Laravel!
</details>
