/* Portfolio — export full page as PDF & PowerPoint */
(function () {
    'use strict';

    const data = window.portfolioExport || {};
    const pdfBtns = ['exportPortfolioPdf', 'exportPortfolioPdfFooter'];
    const pptBtns = ['exportPortfolioPpt', 'exportPortfolioPptFooter'];
    const CAPTURE_WIDTH = 1200;
    const PDF_MARGIN_MM = 6;

    function setBusy(busy) {
        document.body.classList.toggle('is-exporting', busy);
        [...pdfBtns, ...pptBtns].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.disabled = busy;
        });
    }

    function waitForPdfLibs() {
        return new Promise((resolve, reject) => {
            let tries = 0;
            const tick = () => {
                if (typeof html2canvas !== 'undefined' && getJsPDF()) {
                    resolve();
                    return;
                }
                if (++tries > 100) {
                    reject(new Error('export libs timeout'));
                    return;
                }
                setTimeout(tick, 100);
            };
            tick();
        });
    }

    function waitForPptLibs() {
        return new Promise((resolve, reject) => {
            let tries = 0;
            const tick = () => {
                if (typeof PptxGenJS !== 'undefined') {
                    resolve();
                    return;
                }
                if (++tries > 100) {
                    reject(new Error('export libs timeout'));
                    return;
                }
                setTimeout(tick, 100);
            };
            tick();
        });
    }

    function getJsPDF() {
        if (window.jspdf && window.jspdf.jsPDF) return window.jspdf.jsPDF;
        if (window.jsPDF) return window.jsPDF;
        return null;
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

    async function exportPdf() {
        const root = document.getElementById('portfolio-export');
        if (!root) return;

        const JsPDF = getJsPDF();
        if (!JsPDF) {
            alert(data.exportError || 'Export failed. Please try again.');
            return;
        }

        setBusy(true);
        root.classList.add('pdf-capture-ready');
        root.style.width = CAPTURE_WIDTH + 'px';

        const prevScroll = window.scrollY;
        window.scrollTo(0, 0);

        const pdfButtons = pdfBtns.map(id => document.getElementById(id)).filter(Boolean);
        const prevLabels = pdfButtons.map(btn => btn.innerHTML);
        pdfButtons.forEach(btn => {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (data.exportLoading || 'Preparing…');
        });

        try {
            await waitForPdfLibs();
            await new Promise(r => setTimeout(r, 400));

            const canvas = await html2canvas(root, {
                scale: 2,
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
            const imgData = canvas.toDataURL('image/jpeg', 0.94);

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
        } catch (err) {
            console.error(err);
            alert(data.exportError || 'Export failed. Please try again.');
        } finally {
            root.classList.remove('pdf-capture-ready');
            root.style.width = '';
            pdfButtons.forEach((btn, i) => {
                if (prevLabels[i]) btn.innerHTML = prevLabels[i];
            });
            window.scrollTo(0, prevScroll);
            setBusy(false);
        }
    }

    function addSlideTitle(slide, title, subtitle) {
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
        slide.addShape(PptxGenJS.ShapeType.rect, {
            x: 0.6, y: 3.1, w: 1.2, h: 0.08, fill: { color: 'A31D1D' },
        });
    }

    async function exportPpt() {
        setBusy(true);
        try {
            await waitForPptLibs();
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
            addSlideTitle(slide, 'About', data.taglineShort || '');
            slide.addText(data.about || '', {
                x: 0.6, y: 3.5, w: 8.8, h: 2.2,
                fontSize: 13, color: blush, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, data.experienceTitle || 'Work Experience', '');
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
            addSlideTitle(slide, data.skillsTitle || 'Skills', 'Core stack');
            slide.addText((data.skills || []).join('  ·  '), {
                x: 0.6, y: 3.5, w: 8.8, h: 2.5,
                fontSize: 14, color: cream, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, data.projectsTitle || 'Projects', '9+ shipped');
            const projectLines = (data.projects || []).map((title, i) =>
                `${String(i + 1).padStart(2, '0')}  ${title}`
            ).join('\n');
            slide.addText(projectLines, {
                x: 0.6, y: 3.4, w: 8.8, h: 3.0,
                fontSize: 12, color: blush, valign: 'top',
            });

            slide = pptx.addSlide();
            addSlideTitle(slide, data.contactTitle || 'Contact', data.website || '');
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

            await pptx.writeFile({ fileName: 'Muzaki-Abdullah-Irsyad-Portfolio.pptx' });
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

    pdfBtns.forEach(id => bind(id, exportPdf));
    pptBtns.forEach(id => bind(id, exportPpt));
})();
