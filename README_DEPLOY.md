# 🚀 Развертывание на GitHub Pages

## Как развернуть сайт на GitHub Pages

### Шаг 1: Создай репозиторий на GitHub

1. Зайди на [GitHub](https://github.com)
2. Нажми "New repository"
3. Имя: `ai-bot-front-main`
4. Оставь публичным (чтобы GitHub Pages работал бесплатно)
5. Не добавляй README/ignore/license (у нас уже есть)

### Шаг 2: Запуши код

```bash
# Добавить все файлы
git add .

# Коммит
git commit -m "Initial commit"

# Добавить remote (замени USERNAME на свой GitHub username)
git remote add origin https://github.com/USERNAME/ai-bot-front-main.git

# Запушить на GitHub
git push -u origin main
```

### Шаг 3: Включить GitHub Pages

1. Зайди в Settings репозитория
2. Прокрути до "Pages" в левом меню
3. Под "Source" выбери "GitHub Actions"
4. Сохрани

### Шаг 4: Дождаться деплоя

GitHub Actions автоматически:
1. Соберет сайт
2. Задеплоит на GitHub Pages

Когда завершится (обычно 2-3 минуты), твой сайт будет доступен по адресу:

**https://USERNAME.github.io/ai-bot-front-main/**

---

## 🔄 Автоматический деплой

При каждом push в `main` ветку, GitHub автоматически пересобирает и задеплоит сайт.

### Проверить статус деплоя:
1. Зайди в "Actions" в репозитории
2. Увидишь процесс деплоя в реальном времени

---

## ⚙️ Настройка для своего домена

Если хочешь использовать `username.github.io` (корневой URL):

1. Измени имя репозитория на `USERNAME.github.io`
2. Измени в `vite.config.js`:
```javascript
base: '/',  // вместо '/ai-bot-front-main/'
```

---

## 🐛 Если что-то не работает

### Проблема: Сайт не загружается
**Решение:** 
- Убедись что репозиторий публичный
- Проверь что GitHub Actions завершился успешно
- Подожди 1-2 минуты после деплоя

### Проблема: White screen
**Решение:**
- Проверь `vite.config.js` - base должен быть `/ai-bot-front-main/`
- Проверь консоль браузера на ошибки

### Проблема: API не работает
**Решение:**
- Проверь что API доступен с GitHub Pages
- Возможно нужен CORS для API

---

## 📱 Как проверить на iPhone/Mac

1. Зайди на сайт с Mac: `https://USERNAME.github.io/ai-bot-front-main/`
2. Открой на iPhone Safari
3. Проверь что скролль плавный без прыжков
4. Протестируй мобильное меню

---

## 🎉 Готово!

Твой сайт теперь доступен по адресу:
**https://USERNAME.github.io/ai-bot-front-main/**

Он будет автоматически обновляться при каждом push в main! 🚀

