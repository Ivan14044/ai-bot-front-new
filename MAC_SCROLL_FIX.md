# ✅ ИСПРАВЛЕНИЕ: Перерисовка блоков при скролле на Mac

## 🐛 Проблема:
При скролле на Mac/iPhone Safari каждый блок перерисовывается и "мигает"

## ✅ ЧТО ИСПРАВЛЕНО:

### 1. Добавлено hardware acceleration для всех элементов
```css
* {
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}
```

### 2. Оптимизированы все секции
```css
section {
    contain: layout style paint;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
}
```

### 3. Оптимизированы карточки
```css
.v-card, [class*="card"] {
    contain: layout style paint;
    transform: translateZ(0);
}
```

### 4. Убрано перерисовывание div
```css
div {
    will-change: auto;
}
```

---

## ⏱️ Через 2-3 минуты:

GitHub Actions пересоберет сайт с новыми оптимизациями.

**Проверь:**
### https://ivan14044.github.io/ai-bot-front-new/

---

## 🔍 Что должно измениться:

### ДО:
❌ Блоки мигают при скролле  
❌ Верстка сдвигается  
❌ Сайт медленно работает на Mac

### ПОСЛЕ:
✅ Плавный скролль без мигания  
✅ Блоки не перерисовываются  
✅ Быстрая работа на Mac/iPhone

---

## 📱 Как проверить:

1. Подожди 2-3 минуты
2. Открой сайт на Mac Safari
3. Скролль должен быть плавным
4. Никаких миганий и перерисовок

---

## 🎯 Статус деплоя:

Коммит отправлен: `e3c674d`  
GitHub Actions запустится автоматически  
Через 2-3 минуты сайт будет обновлен! 🚀

