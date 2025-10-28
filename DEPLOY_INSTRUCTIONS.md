# 🚀 Инструкция по развертыванию на GitHub Pages

## Твой сайт будет доступен по адресу:
### https://USERNAME.github.io/ai-bot-front-main/

---

## 📝 Шаги для деплоя:

### 1️⃣ Создай репозиторий на GitHub

1. Открой https://github.com/new
2. Repository name: `ai-bot-front-main`
3. **Важно:** Сделай репозиторий **PUBLIC** (чтобы GitHub Pages работал бесплатно)
4. **Не ставь галочки** на "Add README", "Add .gitignore", "Choose a license"
5. Нажми "Create repository"

---

### 2️⃣ Сделай commit

Выполни команды:

```bash
# Создай ветку main
git branch -M main

# Сделай первый коммит
git commit -m "Initial commit - Optimized for iOS/Mac"

# Добавь remote (замени USERNAME на свой GitHub username)
git remote add origin https://github.com/USERNAME/ai-bot-front-main.git

# Отправь код на GitHub
git push -u origin main
```

---

### 3️⃣ Включи GitHub Pages

1. Зайди в Settings репозитория
2. Прокрути до раздела "Pages" в левом меню
3. Под "Build and deployment" -> "Source" выбери **"GitHub Actions"**
4. Сохрани изменения

---

### 4️⃣ Дождаться автодеплоя

GitHub Actions автоматически:
- Соберет проект (`npm run build`)
- Задеплоит на GitHub Pages

**Где посмотреть процесс:**
1. Зайди во вкладку **"Actions"** в репозитории
2. Увидишь процесс деплоя в реальном времени
3. Когда увидишь зеленую галочку ✅ - сайт готов!

Обычно занимает **2-3 минуты**

---

### 5️⃣ Открыть сайт

Когда деплой завершится, твой сайт будет доступен по адресу:

**https://USERNAME.github.io/ai-bot-front-main/**

Открой этот URL на Mac и iPhone Safari для проверки!

---

## ✅ Автоматический деплой

При каждом **push** в `main` ветку, GitHub автоматически пересоберет и задеплоит сайт.

### Как обновить сайт:
```bash
git add .
git commit -m "Update site"
git push
```

Через 2-3 минуты сайт обновится!

---

## 🐛 Возможные проблемы:

### Проблема: Сайт показывает 404
**Решение:**
- Убедись что репозиторий **public**
- Проверь что в Settings -> Pages выбран "GitHub Actions"
- Подожди 2-3 минуты после первого деплоя

### Проблема: Белый экран
**Решение:**
- Проверь что в `vite.config.js` base = `/ai-bot-front-main/`
- Открой консоль браузера (F12) и проверь ошибки

### Проблема: GitHub Actions не запускается
**Решение:**
- Проверь что файл `.github/workflows/deploy.yml` существует
- Проверь что он добавлен в git: `git add .github/workflows/deploy.yml`
- Убедись что код запушен на GitHub

---

## 🎨 Изменение имени репозитория

Если хочешь другой URL, просто переименуй репозиторий в Settings.

Измени `vite.config.js`:
```javascript
base: process.env.NODE_ENV === 'production' ? '/НОВОЕ-ИМЯ/' : '/',
```

---

## 📱 Тестирование на iPhone

1. Открой сайт на Mac: `https://USERNAME.github.io/ai-bot-front-main/`
2. Открой на iPhone Safari тот же URL
3. Проверь что:
   - ✅ Скролль плавный без прыжков
   - ✅ Мобильное меню работает
   - ✅ Все анимации плавные
   - ✅ Нет лагов

---

## 🎉 Готово!

Теперь у тебя есть сайт на бесплатном GitHub домене!
Деплой происходит автоматически при каждом push.

**URL твоего сайта:**
**https://USERNAME.github.io/ai-bot-front-main/**

