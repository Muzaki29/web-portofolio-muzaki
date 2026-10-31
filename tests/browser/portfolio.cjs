const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const os = require('node:os');
const { execFileSync } = require('node:child_process');
let playwright;
try { playwright = require(process.env.PLAYWRIGHT_MODULE || 'playwright'); }
catch (error) {
    if (process.env.PLAYWRIGHT_MODULE) throw error;
    playwright = require(path.join(os.homedir(), '.agents/skills/playwright-skill/node_modules/playwright'));
}
const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const output = process.env.OUTPUT_DIR ? path.resolve(process.env.OUTPUT_DIR) : path.resolve(__dirname, '../../docs/motion');
const shots = path.join(output, 'screenshots');
fs.mkdirSync(shots, { recursive: true });
const results = { baseUrl: BASE_URL, testedAt: new Date().toISOString(), checks: [], pageErrors: [], failedRequests: [], consoleErrors: [] };
async function check(name, run) {
    try { const detail = await run(); results.checks.push({name, status:'passed', detail}); console.log('PASS',name); }
    catch(error) { results.checks.push({name, status:'failed', error:error.message}); console.error('FAIL',name,error.message); }
}
(async () => {
    const browser = await playwright.chromium.launch({ channel: process.env.BROWSER_CHANNEL || 'chrome', headless: process.env.HEADLESS === '1' });
    try {
        if (process.env.SKIP_BASELINE !== '1') await check('git HEAD before screenshots',async()=>{
            const root=path.resolve(__dirname,'../..');
            const temporary=fs.mkdtempSync(path.join(os.tmpdir(),'portfolio-baseline-'));
            let baseline;
            try {
                const oldView=execFileSync('git',['show','HEAD:resources/views/portfolio.blade.php'],{cwd:root,encoding:'utf8'});
                fs.writeFileSync(path.join(temporary,'portfolio.blade.php'),oldView);
                const phpPath=value=>"'"+value.replace(/\\/g,'/').replace(/'/g,"\\'")+"'";
                const renderer=`<?php
require ${phpPath(path.join(root,'vendor/autoload.php'))};
$app = require ${phpPath(path.join(root,'bootstrap/app.php'))};
$kernel = $app->make(Illuminate\\Contracts\\Http\\Kernel::class);
$kernel->bootstrap();
$app['view.finder']->setPaths([${phpPath(temporary)}]);
$response = $kernel->handle(Illuminate\\Http\\Request::create('${BASE_URL}'));
echo $response->getContent();
`;
                const renderPath=path.join(temporary,'render.php');fs.writeFileSync(renderPath,renderer);
                const html=execFileSync('php',[renderPath],{cwd:root,encoding:'utf8',maxBuffer:4*1024*1024});
                assert.match(html,/<section class="hero/);
                baseline=await browser.newContext({viewport:{width:1440,height:1000}});
                await baseline.route('**/*',async route=>{
                    const url=new URL(route.request().url());
                    if(url.origin===new URL(BASE_URL).origin && url.pathname==='/') return route.fulfill({contentType:'text/html',body:html});
                    const asset={'/css/portfolio.css':'public/css/portfolio.css','/js/portfolio.js':'public/js/portfolio.js'}[url.pathname];
                    if(asset) return route.fulfill({contentType:asset.endsWith('.css')?'text/css':'text/javascript',body:execFileSync('git',['show',`HEAD:${asset}`],{cwd:root,encoding:'utf8'})});
                    return route.continue();
                });
                const before=await baseline.newPage();await before.goto(BASE_URL,{waitUntil:'domcontentloaded',timeout:45000});
                await before.waitForFunction(()=>document.querySelector('#tw-text')?.textContent.length>3);
                await before.screenshot({path:path.join(shots,'before-desktop.png')});
                await before.setViewportSize({width:390,height:844});
                await before.screenshot({path:path.join(shots,'before-mobile.png')});
                return {source:'git HEAD Blade/CSS/JS rendered by Laravel in temporary view directory'};
            } finally {
                await baseline?.close();
                fs.rmSync(temporary,{recursive:true,force:true});
            }
        });
        const context = await browser.newContext({ viewport:{width:1440,height:1000}, acceptDownloads:true });
        await context.addInitScript(()=>{
            window.__portfolioLab={lcp:null,cls:0};
            if(PerformanceObserver.supportedEntryTypes.includes('largest-contentful-paint')) new PerformanceObserver(list=>{
                const entries=list.getEntries();window.__portfolioLab.lcp=entries[entries.length-1].startTime;
            }).observe({type:'largest-contentful-paint',buffered:true});
            if(PerformanceObserver.supportedEntryTypes.includes('layout-shift')) new PerformanceObserver(list=>{
                for(const entry of list.getEntries()) if(!entry.hadRecentInput) window.__portfolioLab.cls+=entry.value;
            }).observe({type:'layout-shift',buffered:true});
        });
        const page = await context.newPage();
        page.setDefaultTimeout(20000);
        page.setDefaultNavigationTimeout(45000);
        page.on('pageerror', error => results.pageErrors.push(error.message));
        page.on('console', message => { if(message.type()==='error') results.consoleErrors.push(message.text()); });
        page.on('requestfailed', request => results.failedRequests.push({url:request.url(),error:request.failure()?.errorText}));
        await check('homepage response',async()=>{
            const response=await page.goto(BASE_URL,{waitUntil:'domcontentloaded'});
            assert.equal(response.status(),200);
            await page.waitForFunction(()=>document.querySelector('.tab-btn')?.getAttribute('role')==='tab');
        });
        await check('homepage and cinematic hero', async () => {
            assert.match(await page.title(),/Muzaki/);
            await page.locator('h1.hero-name').waitFor({state:'visible'});
            await page.waitForFunction(()=> [...document.querySelectorAll('.hero-line > span')].every(el=> Math.abs(new DOMMatrix(getComputedStyle(el).transform).m42)<1));
            assert.equal(await page.locator('h1').count(),1);
            const dimensions = await page.locator('.hero-media img').evaluate(img=>({width:img.naturalWidth,height:img.naturalHeight}));
            assert.ok(dimensions.width>0);
            await page.screenshot({path:path.join(shots,'after-desktop.png')});
            await page.evaluate(()=>new Promise(resolve=>requestAnimationFrame(()=>requestAnimationFrame(resolve))));
            results.lab=await page.evaluate(()=>({
                ...window.__portfolioLab,viewport:{width:innerWidth,height:innerHeight},userAgent:navigator.userAgent,
                motionBundle:[...document.scripts].map(script=>script.src).find(src=>src.includes('/build/assets/index-')),
                navigation:performance.getEntriesByType('navigation').map(entry=>({responseStart:entry.responseStart,domContentLoaded:entry.domContentLoadedEventEnd}))[0],
            }));
            results.lab.note='Local unthrottled Chromium lab observation before interaction; uncontrolled cache/network. Not field CWV. INP not measured.';
            return dimensions;
        });
        for(const width of [390,768,1024,1440,1920]) {
            await check(`responsive ${width}px, images and overflow`, async()=> {
                await page.setViewportSize({width,height:width===390?844:1000});
                await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
                await page.waitForFunction(()=>document.documentElement.scrollWidth <= innerWidth+1);
                const clipped=await page.locator('.hero-line > span').evaluateAll(lines=>lines.map(el=>{
                    const range=document.createRange();range.selectNodeContents(el);const rect=range.getBoundingClientRect();
                    return {text:el.textContent.trim(),left:rect.left,right:rect.right};
                }).filter(rect=>rect.left < -1 || rect.right > innerWidth+1));
                assert.deepEqual(clipped,[],'hero heading text must fit viewport without clipping');
                for(const section of await page.locator('section[id]').all()) await section.scrollIntoViewIfNeeded();
                await page.locator('footer').scrollIntoViewIfNeeded();
                const broken = await page.locator('img').evaluateAll(async images=>{
                    const visible=images.filter(img=> img.getAttribute('src') && img.getClientRects().length);
                    await Promise.all(visible.map(img=>img.decode().catch(()=>{})));
                    return visible.filter(img=>img.naturalWidth===0).map(img=>img.src);
                });
                assert.deepEqual(broken,[]);
                const bounds = await page.evaluate(()=>({width:innerWidth,scrollWidth:document.documentElement.scrollWidth}));
                assert.ok(bounds.scrollWidth<=width+1);
                await page.emulateMedia({reducedMotion:'reduce'});
                await page.locator('body').evaluate(()=>document.activeElement?.blur());
                await page.screenshot({path:path.join(shots,`final-page-${width}.png`),fullPage:true});
                await page.emulateMedia({reducedMotion:'no-preference'});
                await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
                if(width===390) {
                    const overlap=await page.evaluate(()=>{
                        const a=document.querySelector('.hero-footnote a').getBoundingClientRect();
                        const b=document.querySelector('#aiChatBubble').getBoundingClientRect();
                        return a.left<b.right && a.right>b.left && a.top<b.bottom && a.bottom>b.top;
                    });
                    assert.equal(overlap,false,'explore link must not overlap chatbot');
                    await page.waitForFunction(()=>scrollY===0 && Number(getComputedStyle(document.querySelector('#backTop')).opacity)<0.05);
                    await page.waitForFunction(()=>document.querySelector('#tw-text').textContent.length>6 && Number(getComputedStyle(document.querySelector('.hero-role-line')).opacity)>0.9);
                    await page.locator('body').evaluate(()=>document.activeElement?.blur());
                    await page.screenshot({path:path.join(shots,'after-mobile.png')});
                }
                return bounds;
            });
        }
        await page.setViewportSize({width:1440,height:1000});
        await check('theme persistence', async()=> {
            const before=await page.locator('html').getAttribute('data-theme');
            await page.locator('#themeToggle').click();
            assert.notEqual(await page.locator('html').getAttribute('data-theme'),before);
            await page.reload({waitUntil:'domcontentloaded'});
            await page.waitForFunction(()=>document.querySelector('#tab-languages'));
            assert.notEqual(await page.locator('html').getAttribute('data-theme'),before);
            await page.locator('#themeToggle').click();
        });
        await check('locale ID and EN', async()=> {
            await page.locator('a.lang-option', {hasText:'EN'}).click();
            await page.waitForFunction(()=>document.documentElement.lang==='en' && document.querySelector('#tab-languages'));
            await page.locator('a.lang-option', {hasText:'ID'}).click();
            await page.waitForFunction(()=>document.documentElement.lang==='id' && document.querySelector('#tab-languages'));
        });
        await check('skip link keyboard moves focus to content',async()=>{
            await page.goto(BASE_URL,{waitUntil:'domcontentloaded'});
            await page.waitForFunction(()=>document.querySelector('#tab-languages'));
            await page.keyboard.press('Tab');
            assert.equal(await page.locator('.skip-link').evaluate(el=>el===document.activeElement),true);
            await page.keyboard.press('Enter');
            await page.waitForFunction(()=>document.activeElement===document.querySelector('#about'));
        });
        await check('skills tabs keyboard and ARIA', async()=> {
            const first=page.locator('.tab-btn').first();
            await first.focus();await page.keyboard.press('ArrowRight');
            assert.equal(await page.locator('#tab-frontend').getAttribute('aria-selected'),'true');
            assert.equal(await page.locator('#panel-frontend').isVisible(),true);
            assert.equal(await page.locator('#panel-languages').isVisible(),false);
            await page.keyboard.press('End');
            assert.equal(await page.locator('#tab-deployment').getAttribute('aria-selected'),'true');
            await page.keyboard.press('Home');
            assert.equal(await first.getAttribute('aria-selected'),'true');
        });
        await check('project modal focus trap and restore', async()=> {
            const trigger=page.locator('.btn-view-details').first();
            await trigger.click();
            assert.equal(await page.locator('#projectModal').getAttribute('aria-hidden'),'false');
            assert.equal(await page.locator('#pmCloseBtn').evaluate(el=>el===document.activeElement),true);
            await page.keyboard.press('Shift+Tab');
            assert.equal(await page.evaluate(()=>Boolean(document.activeElement.closest('#projectModal'))),true);
            await page.keyboard.press('Tab');
            assert.equal(await page.locator('#pmCloseBtn').evaluate(el=>el===document.activeElement),true);
            assert.ok((await page.locator('#pmModalBody').textContent()).trim().length>30);
            await page.keyboard.press('Escape');
            assert.equal(await page.locator('#projectModal').getAttribute('aria-hidden'),'true');
            assert.equal(await trigger.evaluate(el=>el===document.activeElement),true);
            for(const image of await page.locator('#works img').all()) {
                await image.scrollIntoViewIfNeeded();await image.evaluate(img=>img.decode());
            }
            await page.locator('body').evaluate(()=>document.activeElement?.blur());
            await page.emulateMedia({reducedMotion:'reduce'});
            await page.mouse.move(0,0);
            await page.locator('#works').scrollIntoViewIfNeeded();
            await page.waitForFunction(()=>[...document.querySelectorAll('#works .project-grid-card')].every(card=>Number(getComputedStyle(card).opacity)>0.99));
            await page.locator('#works').screenshot({path:path.join(shots,'after-projects.png'),style:'#navbar,.skip-link,.cursor,.cursor-dot,#backTop,#aiChatWidget,#scroll-bar{visibility:hidden!important}'});
            results.projectScreenshotNote='Full section capture uses static reduced-motion mode and hides fixed navigation, skip link, cursor and floating controls to inspect gallery content.';
            await page.emulateMedia({reducedMotion:'no-preference'});
        });
        await check('certificate keyboard dialog and close',async()=>{
            const card=page.locator('.cert-open').first();await card.focus();await page.keyboard.press('Enter');
            assert.equal(await page.locator('#certLightbox').getAttribute('aria-hidden'),'false');
            await page.locator('#lightboxImg').waitFor({state:'visible'});
            await page.waitForFunction(()=>document.querySelector('#lightboxImg').naturalWidth>0);
            await page.keyboard.press('Escape');
            assert.equal(await page.locator('#certLightbox').getAttribute('aria-hidden'),'true');
            assert.equal(await card.evaluate(el=>el===document.activeElement),true);
        });
        await check('mobile navigation keyboard',async()=> {
            await page.setViewportSize({width:390,height:844});
            await page.locator('#ham').waitFor({state:'visible'});
            await page.locator('#ham').focus();
            assert.equal(await page.locator('#ham').evaluate(el=>el===document.activeElement),true);
            await page.keyboard.press('Enter');
            assert.equal(await page.locator('#ham').getAttribute('aria-expanded'),'true');
            await page.keyboard.press('Escape');
            assert.equal(await page.locator('#ham').getAttribute('aria-expanded'),'false');
            assert.equal(await page.locator('#ham').evaluate(el=>el===document.activeElement),true);
            await page.locator('#ham').click();await page.locator('.nav-link[href="#about"]').click();
            assert.equal(await page.locator('#ham').getAttribute('aria-expanded'),'false');
        });
        await check('dynamic reduced motion and restore',async()=>{
            await page.evaluate(()=>{
                const context=document.querySelector('#bg-canvas').getContext('2d');
                const clear=context.clearRect.bind(context);
                window.__canvasFrames=0;
                context.clearRect=(...args)=>{window.__canvasFrames++;return clear(...args);};
            });
            await page.emulateMedia({reducedMotion:'reduce'});
            await page.waitForFunction(()=>getComputedStyle(document.querySelector('#bg-canvas')).display==='none');
            const frameCount=await page.evaluate(()=>window.__canvasFrames);
            await page.waitForTimeout(200);
            assert.equal(await page.evaluate(()=>window.__canvasFrames),frameCount,'reduced motion must stop canvas redraws');
            const role=await page.locator('#tw-text').textContent();
            assert.equal(role,'Full Stack Developer');
            await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
            const transitions=await page.locator('h1').evaluate(el=>getComputedStyle(el).animationDuration);
            await page.screenshot({path:path.join(shots,'after-reduced-motion.png')});
            await page.emulateMedia({reducedMotion:'no-preference'});
            await page.waitForFunction(()=>getComputedStyle(document.querySelector('#bg-canvas')).display!=='none');
            await page.waitForFunction(before=>window.__canvasFrames>before,frameCount);
            return {role,headingAnimation:transitions};
        });
        await page.setViewportSize({width:1440,height:1000});
        await check('MAI favicon PNG resource',async()=>{
            const href=await page.locator('link[rel="icon"][sizes="32x32"]').getAttribute('href');
            const response=await context.request.get(href);assert.equal(response.status(),200);
            const bytes=await response.body();assert.equal(bytes.subarray(1,4).toString(),'PNG');
            assert.equal(bytes.readUInt32BE(16),32);assert.equal(bytes.readUInt32BE(20),32);
        });
        await check('CV download resource',async()=>{
            const href=await page.locator('a[download][href*="cv/"]').first().getAttribute('href');
            const response=await context.request.get(href);
            assert.equal(response.status(),200);const buffer=await response.body();assert.equal(buffer.subarray(0,4).toString(),'%PDF');return {bytes:buffer.length};
        });
        await check('MAI social preview metadata and original image resource',async()=>{
            assert.equal(await page.locator('meta[property="og:type"]').getAttribute('content'),'website');
            assert.equal(await page.locator('meta[name="twitter:card"]').getAttribute('content'),'summary_large_image');
            const image=await page.locator('meta[property="og:image"]').getAttribute('content');
            assert.match(image,/\/images\/mai-social\.png$/);
            assert.equal(await page.locator('meta[name="twitter:image"]').getAttribute('content'),image);
            const canonical=await page.locator('link[rel="canonical"]').getAttribute('href');
            assert.equal(await page.locator('meta[property="og:url"]').getAttribute('content'),canonical);
            const response=await context.request.get(image);assert.equal(response.status(),200);
            const bytes=await response.body();assert.equal(bytes.subarray(1,4).toString(),'PNG');
            assert.equal(bytes.readUInt32BE(16),1536);assert.equal(bytes.readUInt32BE(20),1024);
            return {image,width:1536,height:1024,bytes:bytes.length};
        });
        for(const format of ['Pdf','Ppt']) await check(`${format} actual export download`,async()=>{
            const [download]=await Promise.all([
                page.waitForEvent('download',{timeout:45000}),
                page.locator(`#exportPortfolio${format}`).click(),
            ]);
            assert.equal(await download.failure(),null);
            const file=await download.path();const bytes=fs.readFileSync(file);
            assert.ok(bytes.length>1000);
            assert.equal(bytes.subarray(0,format==='Pdf'?4:2).toString(),format==='Pdf'?'%PDF':'PK');
            return {filename:download.suggestedFilename(),bytes:bytes.length};
        });
        await check('contact invalid captcha rejected without email',async()=>{
            await page.locator('#contactForm [name="name"]').fill('Browser Regression');
            await page.locator('#contactForm [name="email"]').fill('regression@example.com');
            await page.locator('#contactForm [name="subject"]').fill('Invalid CAPTCHA regression');
            await page.locator('#contactForm [name="message"]').fill('Must never send email: invalid CAPTCHA.');
            await page.locator('#contactForm [name="captcha"]').fill('-999');
            const responsePromise=page.waitForResponse(response=>response.url()===`${BASE_URL}/contact` && response.request().method()==='POST');
            await page.locator('#submitBtn').click();const response=await responsePromise;
            assert.equal(response.status(),422);assert.equal((await response.json()).success,false);
            await page.locator('#formMessage').waitFor({state:'visible'});
            assert.equal(await page.locator('#submitBtn').isEnabled(),true);
        });
        await check('chatbot mocked response HTML and URL safety',async()=>{
            await page.route('**/api/chatbot', route=>route.fulfill({contentType:'application/json',body:JSON.stringify({success:true,message:'<img src=x onerror="window.__xss=1"> **Safe** [bad](javascript:alert%281%29) [good](https://example.com) <br>Done'})}));
            assert.equal(await page.locator('#aiChatWindow').evaluate(el=>el.inert),true);
            await page.locator('#aiChatBubble').click();
            assert.equal(await page.locator('#aiChatBubble').getAttribute('aria-expanded'),'true');
            assert.equal(await page.locator('#aiChatWindow').evaluate(el=>el.inert),false);
            await page.locator('#aiChatInput').fill('<img src=x onerror="window.__xss=2">');
            await page.locator('#aiChatForm').evaluate(form=>form.requestSubmit());
            await page.waitForFunction(()=>[...document.querySelectorAll('.ai-msg.bot')].some(el=>el.textContent.includes('Done')));
            assert.equal(await page.locator('.ai-msg img').count(),0);
            assert.equal(await page.locator('.ai-msg a[href^="javascript"]').count(),0);
            assert.equal(await page.locator('.ai-msg.bot strong', {hasText:'Safe'}).count(),1);
            assert.equal(await page.evaluate(()=>window.__xss),undefined);
            assert.match(await page.locator('.ai-msg.user').last().textContent(),/<img/);
            await page.keyboard.press('Escape');
            assert.equal(await page.locator('#aiChatWindow').evaluate(el=>el.inert),true);
            assert.equal(await page.locator('#aiChatBubble').getAttribute('aria-expanded'),'false');
            assert.equal(await page.locator('#aiChatBubble').evaluate(el=>el===document.activeElement),true);
            await page.unroute('**/api/chatbot');
        });
        for(const scenario of ['invalid JSON','success false']) await check(`contact HTTP 200 ${scenario} preserves user input`,async()=>{
            const isolated=await browser.newContext();
            try {
                const testPage=await isolated.newPage();
                await testPage.route('**/contact',route=>route.fulfill({status:200,contentType:'application/json',body:scenario==='invalid JSON'?'invalid json':JSON.stringify({success:false,message:'Safe test rejection'})}));
                await testPage.goto(BASE_URL,{waitUntil:'domcontentloaded'});
                await testPage.waitForFunction(()=>document.querySelector('#tab-languages'));
                for(const [name,value] of Object.entries({name:'Preserved Name',email:'regression@example.com',subject:'Preserved subject',message:'Preserved message',captcha:'4'})) await testPage.locator(`#contactForm [name="${name}"]`).fill(value);
                await testPage.locator('#submitBtn').click();
                await testPage.waitForFunction(()=>document.querySelector('#formMessage').style.display==='block' && !document.querySelector('#submitBtn').disabled);
                assert.equal(await testPage.locator('#formMessage').evaluate(el=>el.classList.contains('error')),true);
                assert.equal(await testPage.locator('#contactForm [name="name"]').inputValue(),'Preserved Name');
                assert.equal(await testPage.locator('#contactForm [name="message"]').inputValue(),'Preserved message');
                assert.equal(await testPage.locator('#submitBtn').isEnabled(),true);
            } finally { await isolated.close(); }
        });
        await check('external project links have safe protocols and opener protection',async()=>{
            const links=await page.locator('#works a[target="_blank"]').evaluateAll(elements=>elements.map(el=>({href:el.href,rel:el.rel})));
            assert.ok(links.length>0);
            for(const link of links) {
                assert.equal(new URL(link.href).protocol,'https:');
                assert.match(link.rel,/noopener|noreferrer/);
            }
            return {checked:links.length,note:'Read-only href validation; remote project uptime not asserted.'};
        });
        for(const scenario of ['429','500','network','malformed']) await check(`chatbot recovers from ${scenario} response`,async()=>{
            const isolated=await browser.newContext();
            try {
                const testPage=await isolated.newPage();
                await testPage.route('**/api/chatbot',route=>{
                    if(scenario==='network') return route.abort('failed');
                    const status=scenario==='429'?429:scenario==='500'?500:200;
                    const body=scenario==='malformed'?{success:true}:{success:false,message:`Safe ${scenario} error`};
                    return route.fulfill({status,contentType:'application/json',body:JSON.stringify(body)});
                });
                await testPage.goto(BASE_URL,{waitUntil:'domcontentloaded'});
                await testPage.waitForFunction(()=>document.querySelector('#tab-languages'));
                await testPage.locator('#aiChatBubble').click();
                await testPage.waitForFunction(()=>document.querySelectorAll('.ai-msg.bot').length>0);
                const before=await testPage.locator('.ai-msg.bot').count();
                await testPage.locator('#aiChatInput').fill('portfolio technical skills');
                await testPage.locator('#aiChatForm').evaluate(form=>form.requestSubmit());
                await testPage.waitForFunction(count=>document.querySelectorAll('.ai-msg.bot').length>count && !document.querySelector('.typing-dots'),before);
                const reply=(await testPage.locator('.ai-msg.bot').last().textContent()).trim();
                assert.ok(reply.length>8,'error/fallback response must remain meaningful');
                assert.doesNotMatch(reply,/^(undefined|null|\[object Object\])$/);
                assert.equal(await testPage.locator('#aiChatInput').isEnabled(),true);
                return {replyLength:reply.length};
            } finally { await isolated.close(); }
        });
        await check('PDF export retries successfully after CDN network failure',async()=>{
            const isolated=await browser.newContext({acceptDownloads:true});
            try {
                const testPage=await isolated.newPage();
                let attempts=0;
                await testPage.route('**/jspdf@*/dist/jspdf.umd.min.js',route=>++attempts===1?route.abort('failed'):route.continue());
                await testPage.goto(BASE_URL,{waitUntil:'domcontentloaded'});
                await testPage.waitForFunction(()=>document.querySelector('#tab-languages'));
                const failure=testPage.waitForEvent('dialog').then(async dialog=>{await dialog.accept();return dialog.message();});
                await testPage.locator('#exportPortfolioPdf').click();
                assert.ok((await failure).length>0);
                await testPage.waitForFunction(()=>!document.querySelector('#exportPortfolioPdf').disabled);
                const [download]=await Promise.all([testPage.waitForEvent('download',{timeout:45000}),testPage.locator('#exportPortfolioPdf').click()]);
                assert.equal(await download.failure(),null);
                assert.equal(fs.readFileSync(await download.path()).subarray(0,4).toString(),'%PDF');
                assert.equal(attempts,2);
                return {attempts};
            } finally { await isolated.close(); }
        });
        await check('touch navigation and chatbot have usable nonoverlapping controls',async()=>{
            const touch=await browser.newContext({viewport:{width:390,height:844},hasTouch:true,isMobile:true});
            try {
                const testPage=await touch.newPage();await testPage.goto(BASE_URL,{waitUntil:'domcontentloaded'});
                await testPage.waitForFunction(()=>document.querySelector('#tab-languages'));
                await testPage.locator('#ham').tap();
                assert.equal(await testPage.locator('#ham').getAttribute('aria-expanded'),'true');
                await testPage.locator('.nav-link[href="#contact"]').tap();
                assert.equal(await testPage.locator('#ham').getAttribute('aria-expanded'),'false');
                await testPage.locator('#aiChatBubble').tap();
                const bounds=await testPage.locator('#aiChatWindow').evaluate(el=>{const r=el.getBoundingClientRect();return {left:r.left,right:r.right,top:r.top,bottom:r.bottom,width:innerWidth,height:innerHeight};});
                assert.ok(bounds.left>=0 && bounds.right<=bounds.width+1 && bounds.top>=0 && bounds.bottom<=bounds.height+1);
                await testPage.locator('#aiCloseBtn').tap();
                assert.equal(await testPage.locator('.cursor').evaluate(el=>getComputedStyle(el).display),'none');
                return bounds;
            } finally { await touch.close(); }
        });
        await check('no unexpected runtime console resource errors',async()=>{
            results.runtimeConsoleErrors=results.consoleErrors.filter(message=>!message.includes('status of 422'));
            assert.deepEqual(results.runtimeConsoleErrors,[]);
        });
        for(const theme of ['dark','light']) await check(`axe WCAG 2.2 AA ${theme} theme`,async()=>{
            if(await page.locator('html').getAttribute('data-theme')!==theme) await page.locator('#themeToggle').click();
            await page.emulateMedia({reducedMotion:'reduce'});
            await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
            let axePath;
            try { axePath=require.resolve(process.env.AXE_MODULE || 'axe-core/axe.min.js'); }
            catch(error) { throw new Error('Set AXE_MODULE to the installed axe-core/axe.min.js path to run accessibility checks.'); }
            await page.addScriptTag({path:axePath});
            const scan=await page.evaluate(async()=>await axe.run(document,{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21a','wcag21aa','wcag22aa']}}));
            results[theme==='dark'?'accessibility':'accessibilityLight']={theme,version:scan.testEngine.version,violations:scan.violations,incomplete:scan.incomplete,passes:scan.passes.map(rule=>rule.id)};
            assert.deepEqual(scan.violations.map(rule=>({id:rule.id,impact:rule.impact,nodes:rule.nodes.map(node=>node.target)})),[]);
            await page.emulateMedia({reducedMotion:'no-preference'});
            return {version:scan.testEngine.version,violations:scan.violations.length,incomplete:scan.incomplete.length,passes:scan.passes.length};
        });
        await check('no uncaught browser errors',async()=>assert.deepEqual(results.pageErrors,[]));
        const staticContext=await browser.newContext({javaScriptEnabled:false,viewport:{width:390,height:844}});
        const staticPage=await staticContext.newPage();await staticPage.goto(BASE_URL,{waitUntil:'domcontentloaded'});
        await check('JavaScript disabled content and navigation',async()=>{
            assert.equal(await staticPage.locator('h1').isVisible(),true);
            assert.equal(await staticPage.locator('#tw-text').textContent(),'Full Stack Developer');
            assert.equal(await staticPage.locator('.nav-link[href="#about"]').isVisible(),true);
            assert.equal(await staticPage.locator('#about').isVisible(),true);
            const hidden=await staticPage.locator('#works .project-grid-card').evaluateAll(cards=>cards.filter(el=>getComputedStyle(el).opacity==='0').length);
            assert.equal(hidden,0);
            await staticPage.screenshot({path:path.join(shots,'after-no-javascript.png')});
        });
        await staticContext.close();await context.close();
    } finally {
        await browser.close();
        results.summary={passed:results.checks.filter(x=>x.status==='passed').length,failed:results.checks.filter(x=>x.status==='failed').length};
        fs.writeFileSync(path.join(output,'browser-results.json'),JSON.stringify(results,null,2)+'\n');
        console.log(JSON.stringify(results.summary));
        if(results.summary.failed) process.exitCode=1;
    }
})().catch(error=>{
    results.checks.push({name:'browser suite execution',status:'failed',error:error.message});
    results.summary={passed:results.checks.filter(x=>x.status==='passed').length,failed:results.checks.filter(x=>x.status==='failed').length};
    fs.writeFileSync(path.join(output,'browser-results.json'),JSON.stringify(results,null,2)+'\n');
    console.error(error);process.exitCode=1;
});
