import puppeteer from 'puppeteer';

const url = process.argv[2];
const outputPath = process.argv[3];

(async () => {
    const browser = await puppeteer.launch({ headless: "new" });
    const page = await browser.newPage();
    await page.goto(url);
    await page.screenshot({ path: outputPath });
    await browser.close();
})();