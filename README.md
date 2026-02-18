# Платформа сбора работ на конкурс
## я требую вот это

- PHP 8.1+
- Composer
- MySQL 5.7+ / MariaDB 10.2+
- OpenServer / XAMPP / Laravel Herd
- S3-совместимое хранилище (Beget / MinIO / Amazon S3)

### База данных (`.env`)
```env
DB_CONNECTION=mysql
DB_HOST=MySQL-8.0
DB_PORT=3306
DB_DATABASE=contest_platform
DB_USERNAME=root
DB_PASSWORD=
```
Проект использует очередь:

```bash
# Создать таблицу для очередей
php artisan queue:table
php artisan migrate

# Запустить воркер
php artisan queue:work
```

## Тестовые пользователи

После запуска миграций с сидами создаются пользователи:

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор | admin@example.com | admin123123 |
| Жюри | jury@example.com | jury123123123 |
| Участник | participant@example.com | participant123123 |

## Запуск проекта

```bash
# 1. Запустить миграции и сиды
php artisan migrate --seed

# 2. Очистить кэш
php artisan optimize:clear

# 3. Запустить воркер очередей (в отдельном окне)
php artisan queue:work

# 4. Запустить сервер
php artisan serve
```

После запуска сайт доступен по адресу: `http://contest-platform` или `http://localhost:8000`

### Публичные
- `GET /` - главная страница
- `GET /login/{role}` - быстрый вход под ролью (для теста)

### Требуют авторизации
- `GET /dashboard` - личный кабинет
- `GET /submissions/create` - создание заявки
- `POST /submissions` - сохранение заявки
- `GET /submissions/{id}` - просмотр заявки
- `POST /submissions/{id}/attachments` - загрузка файла
- `POST /submissions/{id}/submit` - отправка на проверку
- `POST /submissions/{id}/status` - смена статуса (жюри)
- `POST /submissions/{id}/comments` - добавление комментария
- `GET /attachments/{id}/download` - скачивание файла

### Админка
- `GET /admin/contests` - управление конкурсами
- `GET /admin/users` - управление пользователями

**Правила:**
- Редактирование только в статусах `draft` и `needs_fix`
- Для отправки нужен минимум 1 файл со статусом `scanned`
- Максимум 3 файла на заявку
- Максимальный размер файла: 10MB
- Разрешённые типы: pdf, zip, png, jpg
