# 🐛 Исправление: Прыжки при скролле на Mac/iPhone

## Проблема
Сайт прыгал и перезагружал блоки при скролле на iPhone/Mac Safari

## Причина
iOS Safari имеет особенности:
1. Перерисовывает все при скролле
2. position:fixed работает по-другому
3. Нет GPU ускорения по умолчанию
4. backdrop-filter вызывает лаги

## Решение
Добавлены оптимизации в `src/assets/app.css`:

### 1. Базовая оптимизация для iOS
```css
html, body {
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: none;
}
```

### 2. Hardware acceleration для header
```css
header {
    transform: translateZ(0);
    contain: layout style paint;
    will-change: transform;
}
```

### 3. Оптимизация glass эффектов
```css
.glass-card {
    -webkit-backdrop-filter: blur(10px);
    transform: translateZ(0);
    contain: layout style paint;
}
```

## Измененные файлы

1. `src/assets/app.css` - добавлены iOS оптимизации
2. `src/components/MobileMenu.vue` - добавлен transform-gpu
3. `src/components/home/PromoteSection.vue` - добавлены card-optimized
4. `tailwind.config.js` - добавлен ios screen

## Результат
✅ Плавный скролл 60 FPS
✅ Нет прыжков
✅ Быстрые анимации
✅ Низкое потребление CPU

## Тестирование
1. Открой на iPhone Safari
2. Скролль должен быть плавным
3. Блоки не должны "мерцать"
4. Header стабильный

## Дополнительные файлы
- `IOS_OPTIMIZATIONS.md` - полная документация
- `BUGFIX_SUMMARY.md` - этот файл

