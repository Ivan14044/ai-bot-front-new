/*
 Light-weight sanitize helper. If DOMPurify is available in node_modules, it will be used.
 Otherwise falls back to a conservative stripper removing script/style/event handlers.
*/

// Optional import type to avoid hard dependency at compile time
// eslint-disable-next-line @typescript-eslint/no-explicit-any
let dompurify: any | null = null;
try {
    // eslint-disable-next-line @typescript-eslint/no-var-requires
    dompurify = require('dompurify');
    // If used in browser ESM, DOMPurify might be default export
    dompurify = dompurify?.default || dompurify;
} catch (_) {
    dompurify = null;
}

export function sanitizeHtml(input: string | null | undefined): string {
    if (typeof input !== 'string' || input.length === 0) return '';
    if (dompurify && typeof dompurify.sanitize === 'function') {
        return dompurify.sanitize(input, { USE_PROFILES: { html: true } });
    }

    // Fallback sanitizer: remove <script>, <style>, on* attributes, javascript: urls
    const parser = new DOMParser();
    const doc = parser.parseFromString(`<div>${input}</div>`, 'text/html');
    const container = doc.body.firstElementChild as HTMLElement | null;
    if (!container) return '';

    const walker = doc.createTreeWalker(container, NodeFilter.SHOW_ELEMENT, null);
    // Remove disallowed elements and attributes
    const toRemove: Element[] = [];
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    function cleanseEl(el: any) {
        const tag = String(el.tagName || '').toLowerCase();
        if (tag === 'script' || tag === 'style' || tag === 'iframe' || tag === 'object') {
            toRemove.push(el);
            return;
        }
        // Remove event handlers and javascript: urls
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        for (const attr of Array.from((el as any).attributes || [])) {
            const name = String(attr.name || '').toLowerCase();
            const val = String(attr.value || '');
            if (name.startsWith('on')) {
                el.removeAttribute(attr.name);
            } else if ((name === 'href' || name === 'src') && /^\s*javascript:/i.test(val)) {
                el.removeAttribute(attr.name);
            }
        }
    }
    // Clean root first
    cleanseEl(container);
    // Walk and clean
    while (walker.nextNode()) {
        const el = walker.currentNode as Element;
        cleanseEl(el);
    }
    // Remove collected nodes
    for (const el of toRemove) el.remove();
    return container.innerHTML;
}


