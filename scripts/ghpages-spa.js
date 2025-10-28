// Simple SPA fallback for GitHub Pages: copy dist/index.html to dist/404.html
import { copyFileSync, existsSync, mkdirSync } from 'node:fs';
import { dirname, resolve } from 'node:path';

try {
    const root = resolve(process.cwd());
    const indexPath = resolve(root, 'dist/index.html');
    const notFoundPath = resolve(root, 'dist/404.html');
    const dir = dirname(notFoundPath);
    if (!existsSync(dir)) mkdirSync(dir, { recursive: true });
    copyFileSync(indexPath, notFoundPath);
    console.log('Created dist/404.html for GitHub Pages SPA fallback');
} catch (e) {
    console.warn('Failed to create 404.html:', e);
}


