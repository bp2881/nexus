// Nexus — main.js

// Mobile nav toggle
const toggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
if (toggle) toggle.addEventListener('click', () => {
    navLinks.classList.toggle('hidden');
    navLinks.classList.toggle('flex');
});

// Animate on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            e.target.style.transition = `opacity 0.5s ${i * 0.05}s ease, transform 0.5s ${i * 0.05}s ease`;
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.card, .event-item, .material-row, .post-card, .stat-block').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    observer.observe(el);
});

// Comprehensive Pretext rendering
import { prepare } from 'https://esm.sh/@chenglou/pretext';

window.addEventListener('load', () => {
    const elementsToReplace = new Map();

    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_ELEMENT, {
        acceptNode: function(node) {
            const tag = node.tagName.toLowerCase();
            // Reject non-text tags and containers that shouldn't be touched directly
            if (['script', 'style', 'canvas', 'svg', 'img', 'input', 'textarea', 'select', 'option'].includes(tag)) {
                return NodeFilter.FILTER_REJECT;
            }
            
            // Accept leaf nodes or those containing only spans/text/br
            if (node.children.length === 0 || Array.from(node.children).every(c => ['BR', 'SPAN', 'B', 'I', 'STRONG'].includes(c.tagName))) {
                const text = node.textContent?.trim() || "";
                if (text.length > 0) {
                    // Ignore Material Symbols and Toggle Buttons
                    if (!node.closest('.nav-toggle') && !node.classList.contains('material-symbols-outlined') && !node.classList.contains('msi')) {
                        return NodeFilter.FILTER_ACCEPT;
                    }
                }
            }
            return NodeFilter.FILTER_SKIP;
        }
    });

    while (walker.nextNode()) {
        const el = walker.currentNode;
        elementsToReplace.set(el, el.innerText || el.textContent);
    }

    const resizeObserver = new ResizeObserver((entries) => {
        // Redraw only elements that resized
        requestAnimationFrame(() => {
            for (const entry of entries) {
                const parent = entry.target;
                // Find all tracked elements inside this parent
                for (const [el, text] of elementsToReplace.entries()) {
                    if (parent.contains(el)) {
                        renderPretext(el, text);
                    }
                }
            }
        });
    });

    // Observe body for large layout shifts
    resizeObserver.observe(document.body);

    elementsToReplace.forEach((text, el) => {
        const parent = el.parentElement;
        if (parent) resizeObserver.observe(parent);
        renderPretext(el, text);
    });
});

async function renderPretext(el, originalText) {
    if (el.offsetParent === null) return; // Skip hidden elements

    const styles = window.getComputedStyle(el);
    const font = `${styles.fontWeight} ${styles.fontSize} ${styles.fontFamily}`;
    
    // Determine available width. For block components it's clientWidth.
    // For inlines inside a tight flexbox, tracking parentElement gives breathing room for wrapping.
    let availableWidth = el.clientWidth;
    if (styles.display === 'inline' && el.parentElement) {
        availableWidth = el.parentElement.clientWidth;
    }
    if (availableWidth <= 0) availableWidth = document.body.clientWidth - 40;

    let lineHeight = parseInt(styles.lineHeight);
    if (isNaN(lineHeight)) lineHeight = parseInt(styles.fontSize) * 1.5;

    try {
        const prepared = prepare(originalText, font);
        
        // Dynamically import layoutWithLines to construct text fragments
        const { layoutWithLines } = await import('https://esm.sh/@chenglou/pretext?bundle');
        
        const { lines } = layoutWithLines(prepared, availableWidth, lineHeight);

        if (lines && lines.length > 0) {
            drawCanvas(el, lines, availableWidth, lineHeight, font, styles.color, styles.textAlign);
        }
    } catch (e) {
        console.warn('Pretext layout failed:', e);
    }
}

function drawCanvas(el, lines, availableWidth, lineHeight, font, color, textAlign) {
    const height = lines.length * lineHeight;
    
    let maxLineWidth = 0;
    lines.forEach(l => { if (l.width > maxLineWidth) maxLineWidth = l.width; });
    const width = Math.min(availableWidth, maxLineWidth);

    let canvas = el.querySelector('canvas');
    if (!canvas) {
        el.innerHTML = '';
        canvas = document.createElement('canvas');
        el.appendChild(canvas);
    }
    
    const dpr = window.devicePixelRatio || 1;
    // Add 1px padding for text kerning overlaps
    canvas.width = Math.ceil(width * dpr) + 2; 
    canvas.height = Math.ceil(height * dpr);
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
    canvas.style.display = 'inline-block';
    canvas.style.verticalAlign = 'top';
    
    const ctx = canvas.getContext('2d');
    ctx.scale(dpr, dpr);
    ctx.font = font;
    ctx.fillStyle = color;
    ctx.textBaseline = 'top';
    
    let y = 0;
    for (const line of lines) {
        let x = 0;
        if (textAlign === 'center') {
            x = (width - line.width) / 2;
        } else if (textAlign === 'right') {
            x = width - line.width;
        }
        ctx.fillText(line.text, Math.max(0, x), y + (lineHeight - parseInt(font))*0.3); // center vertically
        y += lineHeight;
    }
}
