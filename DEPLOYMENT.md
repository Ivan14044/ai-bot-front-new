# Инструкция по деплою сайта

## Быстрый деплой на Netlify (бесплатно)

### Вариант 1: Через GitHub

1. Перейдите на https://app.netlify.com
2. Нажмите "Add new site" → "Import an existing project"
3. Выберите GitHub и авторизуйтесь
4. Выберите репозиторий `ai-bot-front-new`
5. Настройки:
   - **Build command**: `npm run build`
   - **Publish directory**: `dist`
6. Нажмите "Deploy site"
7. Ваш сайт будет доступен по ссылке вида: `https://ai-bot-front-new.netlify.app`

### Вариант 2: Через Netlify CLI (локально)

```bash
# Установите Netlify CLI
npm install -g netlify-cli

# Войдите в аккаунт
netlify login

# Деплой
netlify deploy --prod
```

## Альтернативные платформы

### Vercel
1. Перейдите на https://vercel.com
2. Import проект из GitHub
3. Автоматически определит настройки Vite
4. Сайт будет доступен по ссылке вида: `https://ai-bot-front-new.vercel.app`

### GitHub Pages
Требует дополнительной настройки для SPA.

## Локальный запуск для тестирования

```bash
# Установка зависимостей (если еще не установлены)
npm install

# Разработка
npm run dev

# Сборка
npm run build

# Превью production сборки
npm run preview
```

## Переменные окружения

Создайте файл `.env` в корне проекта:
```
VITE_API_BASE=https://api.subcloudy.com
VITE_EXTENSION_ID=your-extension-id
```

## Примечание по Safari

Оптимизации для Safari уже включены в код:
- Использование `100dvh` вместо `100vh`
- Префиксы `-webkit-backdrop-filter`
- Safari-безопасные утилиты для viewport

