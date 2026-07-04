/* Portfolio — export full page as PDF & PowerPoint */
(function () {
    'use strict';

    const data = window.portfolioExport || {};
    const pdfBtns = ['exportPortfolioPdf', 'exportPortfolioPdfFooter'];
    const pptBtns = ['exportPortfolioPpt', 'exportPortfolioPptFooter'];
    const CAPTURE_WIDTH = 1200;
    const PDF_MARGIN_MM = 6;
    const VENDOR = {
        html2canvas: 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js',
        jspdf: 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js',
        pptxgen: 'https://cdn.jsdelivr.net/npm/pptxgenjs@3.12.0/dist/pptxgen.bundle.js',
    };

    function isMobile() {
        return window.matchMedia('(max-width: 768px)').matches
            || /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    }

    function setBusy(busy) {
        document.body.classList.toggle('is-exporting', busy);
        [...pdfBtns, ...pptBtns].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.disabled = busy;
        });
    }

    function loadScript(src) {
        return new Promise((resolve, reject) => {
            const existing = document.querySelector('script[src="' + src + '"]');
            if (existing) {
                if (existing.dataset.loaded === '1') {
                    resolve();
                    return;
                }
                existing.addEventListener('load', () => resolve(), { once: true });
                existing.addEventListener('error', () => reject(new Error('script error')), { once: true });
                return;
            }
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            script.onload = () => {
                script.dataset.loaded = '1';
                resolve();
            };
            script.onerror = () => reject(new Error('script error: ' + src));
            document.head.appendChild(script);
        });
    }

    async function waitFor(testFn, timeoutMs) {
        const deadline = Date.now() + timeoutMs;
        while (Date.now() < deadline) {
            if (testFn()) return;
            await new Promise(r => setTimeout(r, 80));
        }
        throw new Error('export libs timeout');
    }

    function getJsPDF() {
        if (window.jspdf && window.jspdf.jsPDF) return window.jspdf.jsPDF;
        if (window.jsPDF) return window.jsPDF;
        return null;
    }

    async function ensurePdfLibs() {
        if (!getJsPDF()) await loadScript(VENDOR.jspdf);
        if (typeof html2canvas === 'undefined') await loadScript(VENDOR.html2canvas);
        await waitFor(() => getJsPDF() && typeof html2canvas !== 'undefined', 12000);
    }

    async function ensurePptLibs() {
        if (typeof PptxGenJS === 'undefined') await loadScript(VENDOR.pptxgen);
        await waitFor(() => typeof PptxGenJS !== 'undefined', 12000);
    }

    function downloadBlob(blob, fileName) {
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        link.rel = 'noopener';
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        setTimeout(() => {
            URL.revokeObjectURL(url);
            link.remove();
        }, 1500);
    }

    function prepareClone(clonedDoc) {
        clonedDoc.documentElement.classList.add('is-exporting');
        clonedDoc.body.classList.add('is-exporting');

        const root = clonedDoc.getElementById('portfolio-export');
        if (root) {
            root.classList.add('pdf-capture-clone');
            root.style.width = CAPTURE_WIDTH + 'px';
        }

        clonedDoc.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('is-visible');
        });

        clonedDoc.querySelectorAll('.resume-export-actions, .footer-export-bar').forEach(el => {
            el.style.display = 'none';
        });
    }

    function exportPdfText(JsPDF) {
        const pdf = new JsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4', compress: true });
        const margin = 14;
        const pageW = pdf.internal.pageSize.getWidth();
        const maxW = pageW - margin * 2;
        let y = margin;

        const addLine = (text, size, style) => {
            pdf.setFont('helvetica', style || 'normal');
            pdf.setFontSize(size);
            const lines = pdf.splitTextToSize(text, maxW);
            lines.forEach(line => {
                if (y > 280) {
                    pdf.addPage();
                    y = margin;
                }
                pdf.text(line, margin, y);
                y += size * 0.42;
            });
            y += 2;
        };

        addLine(data.name || 'Muzaki Abdullah Irsyad', 20, 'bold');
        addLine('Full Stack Developer · Freelancer · Content Creator', 11);
        addLine((data.years || '4+') + ' ' + (data.yearsLabel || 'Tahun Pengalaman') + ' · 9+ ' + (data.projectsLabel || 'Proyek'), 10);
        addLine(data.taglineShort || data.tagline || '', 10);
        y += 4;

        addLine(data.experienceTitle || 'Pengalaman Kerja', 14, 'bold');
        (data.experience || []).forEach((item, i) => {
            addLine(String(i + 1).padStart(2, '0') + '. ' + (item.role || ''), 11, 'bold');
            if (item.meta) addLine(item.meta, 9);
            if (item.desc) addLine(item.desc, 9);
            y += 2;
        });

        addLine(data.skillsTitle || 'Keahlian', 14, 'bold');
        addLine((data.skills || []).join(' · '), 10);
        y += 2;

        addLine(data.projectsTitle || 'Karya', 14, 'bold');
        (data.projects || []).forEach((title, i) => {
            addLine(String(i + 1).padStart(2, '0') + '. ' + title, 10);
        });
        y += 2;

        addLine(data.contactTitle || 'Kontak', 14, 'bold');
        addLine('Email: ' + (data.contactEmail || ''), 10);
        addLine('LinkedIn: ' + (data.contactLinkedIn || ''), 10);
        addLine('GitHub: ' + (data.contactGithub || ''), 10);
        addLine('Website: ' + (data.website || ''), 10);

        pdf.save('Muzaki-Abdullah-Irsyad-Portfolio.pdf');
    }

    async function exportPdfVisual(JsPDF) {
        const root = document.getElementById('portfolio-export');
        if (!root) throw new Error('missing root');

        const scale = isMobile() ? 1 : 2;
        root.classList.add('pdf-capture-ready');
        root.style.width = CAPTURE_WIDTH + 'px';

        const prevScroll = window.scrollY;
        window.scrollTo(0, 0);
        await new Promise(r => setTimeout(r, 300));

        try {
            const canvas = await html2canvas(root, {
                scale,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#000000',
                windowWidth: CAPTURE_WIDTH,
                scrollX: 0,
                scrollY: 0,
                logging: false,
                onclone: prepareClone,
            });

            const pdf = new JsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
                compress: true,
            });

            const pageW = pdf.internal.pageSize.getWidth();
            const pageH = pdf.internal.pageSize.getHeight();
            const contentW = pageW - PDF_MARGIN_MM * 2;
            const contentH = pageH - PDF_MARGIN_MM * 2;
            const imgW = contentW;
            const imgH = (canvas.height * imgW) / canvas.width;
            const imgData = canvas.toDataURL('image/jpeg', 0.92);
            const totalPages = Math.max(1, Math.ceil(imgH / contentH));

            for (let i = 0; i < totalPages; i++) {
                if (i > 0) pdf.addPage();
                pdf.addImage(
                    imgData,
                    'JPEG',
                    PDF_MARGIN_MM,
                    PDF_MARGIN_MM - i * contentH,
                    imgW,
                    imgH,
                    undefined,
                    'FAST'
                );
            }

            pdf.save('Muzaki-Abdullah-Irsyad-Portfolio.pdf');
        } finally {
            root.classList.remove('pdf-capture-ready');
            root.style.width = '';
            window.scrollTo(0, prevScroll);
        }
    }

    async function exportPdf() {
        const pdfButtons = pdfBtns.map(id => document.getElementById(id)).filter(Boolean);
        const prevLabels = pdfButtons.map(btn => btn.innerHTML);

        setBusy(true);
        pdfButtons.forEach(btn => {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (data.exportLoading || 'Preparing…');
        });

        try {
            await ensurePdfLibs();
            const JsPDF = getJsPDF();
            if (!JsPDF) throw new Error('jsPDF missing');

            if (isMobile()) {
                exportPdfText(JsPDF);
            } else {
                try {
                    await exportPdfVisual(JsPDF);
                } catch (visualErr) {
                    console.warn('Visual PDF failed, using text fallback', visualErr);
                    exportPdfText(JsPDF);
                }
            }
        } catch (err) {
            console.error(err);
            alert(data.exportError || 'Export failed. Please try again.');
        } finally {
            pdfButtons.forEach((btn, i) => {
                if (prevLabels[i]) btn.innerHTML = prevLabels[i];
            });
            setBusy(false);
        }
    }

    function addSlideTitle(slide, pptx, title, subtitle) {
        const maroon = 'A31D1D';
        slide.background = { color: '000000' };
        slide.addText(title, {
            x: 0.6, y: 1.1, w: 8.8, h: 1.2,
            fontSize: 32, bold: true, color: 'FEF9E1', fontFace: 'Arial',
        });
        if (subtitle) {
            slide.addText(subtitle, {
                x: 0.6, y: 2.2, w: 8.8, h: 0.8,
                fontSize: 14, color: 'E5D0CA', fontFace: 'Arial',
            });
        }
        const shape = (pptx.ShapeType && pptx.ShapeType.rect) ? pptx.ShapeType.rect : 'rect';
        slide.addShape(shape, {
            x: 0.6, y: 3.1, w: 1.2, h: 0.08, fill: { color: maroon },
        });
    }

    async function exportPpt() {
        setBusy(true);
        try {
            await ensurePptLibs();
            const pptx = new PptxGenJS();
            pptx.layout = 'LAYOUT_16x9';
            pptx.author = data.name || 'Muzaki Abdullah Irsyad';
            pptx.title = 'Portfolio — ' + (data.name || 'Muzaki');

            const maroon = 'A31D1D';
            const cream = 'FEF9E1';
            const blush = 'E5D0CA';

            let slide = pptx.addSlide();
            slide.background = { color: '000000' };
            slide.addText(data.name || 'Muzaki Abdullah Irsyad', {
                x: 0.7, y: 1.0, w: 8.6, h: 1.0,
                fontSize: 36, bold: true, color: cream,
            });
            slide.addText('Full Stack Developer · Freelancer · Content Creator', {
                x: 0.7, y: 2.05, w: 8.6, h: 0.6,
                fontSize: 16, color: blush,
            });
            slide.addText((data.years || '4+') + ' ' + (data.yearsLabel || 'Years Experience') + '  ·  9+ ' + (data.projectsLabel || 'Projects') + '  ·  3 ' + (data.rolesLabel || 'Core Roles'), {
                x: 0.7, y: 2.85, w: 8.6, h: 0.5,
                fontSize: 12, color: maroon, bold: true,
            });
            slide.addText(data.taglineShort || data.tagline || '', {
                x: 0.7, y: 3.55, w: 8.6, h: 1.0,
                fontSize: 13, color: blush,
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, pptx, 'About', data.taglineShort || '');
            slide.addText(data.about || '', {
                x: 0.6, y: 3.5, w: 8.8, h: 2.2,
                fontSize: 13, color: blush, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, pptx, data.experienceTitle || 'Work Experience', '');
            const expRows = (data.experience || []).map((item, i) => [
                { text: String(i + 1).padStart(2, '0'), options: { color: maroon, bold: true, fontSize: 11 } },
                { text: item.role || '', options: { bold: true, color: cream, fontSize: 14 } },
                { text: (item.meta || '') + '\n' + (item.desc || ''), options: { color: blush, fontSize: 11 } },
            ]);
            if (expRows.length) {
                slide.addTable(expRows, {
                    x: 0.6, y: 3.4, w: 8.8,
                    colW: [0.5, 2.4, 5.9],
                    border: { type: 'none' },
                    margin: [0.08, 0.08, 0.08, 0.08],
                });
            }

            slide = pptx.addSlide();
            addSlideTitle(slide, pptx, data.skillsTitle || 'Skills', 'Core stack');
            slide.addText((data.skills || []).join('  ·  '), {
                x: 0.6, y: 3.5, w: 8.8, h: 2.5,
                fontSize: 14, color: cream, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, pptx, data.projectsTitle || 'Projects', '9+ shipped');
            const projectLines = (data.projects || []).map((title, i) =>
                `${String(i + 1).padStart(2, '0')}  ${title}`
            ).join('\n');
            slide.addText(projectLines, {
                x: 0.6, y: 3.4, w: 8.8, h: 3.0,
                fontSize: 12, color: blush, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, pptx, data.contactTitle || 'Contact', data.website || '');
            slide.addText([
                { text: 'Email: ', options: { bold: true, color: cream } },
                { text: data.contactEmail || '', options: { color: blush } },
                { text: '\nLinkedIn: ', options: { bold: true, color: cream } },
                { text: data.contactLinkedIn || '', options: { color: blush } },
                { text: '\nGitHub: ', options: { bold: true, color: cream } },
                { text: data.contactGithub || '', options: { color: blush } },
                { text: '\nWebsite: ', options: { bold: true, color: cream } },
                { text: data.website || '', options: { color: blush } },
            ], {
                x: 0.6, y: 3.5, w: 8.8, h: 2.0,
                fontSize: 14, valign: 'top',
            });

            const fileName = 'Muzaki-Abdullah-Irsyad-Portfolio.pptx';
            try {
                await pptx.writeFile({ fileName });
            } catch (writeErr) {
                console.warn('writeFile failed, using blob fallback', writeErr);
                const blob = await pptx.write({ outputType: 'blob' });
                downloadBlob(blob, fileName);
            }
        } catch (err) {
            console.error(err);
            alert(data.exportError || 'Export failed. Please try again.');
        } finally {
            setBusy(false);
        }
    }

    function bind(id, handler) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', handler);
    }

    function init() {
        pdfBtns.forEach(id => bind(id, exportPdf));
        pptBtns.forEach(id => bind(id, exportPpt));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
