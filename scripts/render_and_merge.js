#!/usr/bin/env node
/**
 * Render overlay HTML using Puppeteer and merge with letterhead via pdf-lib
 * Usage: node scripts/render_and_merge.js <orderId> <outPath> <offsetX> <offsetY> <unit>
 */
const fs = require('fs');
const path = require('path');
// Use puppeteer-core so we can point to an existing Chrome/Chromium binary
const puppeteer = require('puppeteer-core');
const { PDFDocument } = require('pdf-lib');

async function renderOverlay(appUrl, orderId, outPdfPath) {
  // Use PUPPETEER_EXECUTABLE_PATH env var to avoid automatic Chromium download
  const execPath = process.env.PUPPETEER_EXECUTABLE_PATH || process.env.CHROME_PATH || null;
  const launchOpts = { args: ['--no-sandbox', '--disable-setuid-sandbox'] };
  if (execPath) {
    launchOpts.executablePath = execPath;
  }
  const browser = await puppeteer.launch(launchOpts);
  const page = await browser.newPage();
  // Load the receipt route which renders the overlay/template in the app
  const url = `${appUrl.replace(/\/$/, '')}/orders/${orderId}/receipt?debug=1`;
  await page.goto(url, { waitUntil: 'networkidle0' });
  // Set A4 size
  const pdfBuffer = await page.pdf({ format: 'A4', printBackground: true });
  await browser.close();
  fs.writeFileSync(outPdfPath, pdfBuffer);
  return outPdfPath;
}

async function merge(letterheadPath, overlayPath, outPath, offsetX, offsetY, unit) {
  const lhBytes = fs.readFileSync(letterheadPath);
  const ctBytes = fs.readFileSync(overlayPath);
  const lhPdf = await PDFDocument.load(lhBytes);
  const ctPdf = await PDFDocument.load(ctBytes);
  const outPdf = await PDFDocument.create();

  const lhCount = lhPdf.getPageCount();
  const ctCount = ctPdf.getPageCount();

  for (let i = 0; i < lhCount; i++) {
    const [lhEmbedded] = await outPdf.embedPages([lhPdf.getPage(i)]);
    const lhDims = lhEmbedded.size;
    const page = outPdf.addPage([lhDims.width, lhDims.height]);
    page.drawPage(lhEmbedded, { x: 0, y: 0, width: lhDims.width, height: lhDims.height });

    const contentIndex = Math.min(i, ctCount - 1);
    const [ctEmbedded] = await outPdf.embedPages([ctPdf.getPage(contentIndex)]);
    const ctDims = ctEmbedded.size;

    let ox = parseFloat(offsetX) || 0;
    let oy = parseFloat(offsetY) || 0;
    if (unit === 'mm') { ox *= 2.834645669; oy *= 2.834645669; }
    else if (unit === 'percent') { ox = (ox/100)*lhDims.width; oy = (oy/100)*lhDims.height; }

    const scaleX = lhDims.width / ctDims.width;
    const scaleY = lhDims.height / ctDims.height;
    const scale = Math.min(scaleX, scaleY);
    const drawW = ctDims.width * scale;
    const drawH = ctDims.height * scale;

    page.drawPage(ctEmbedded, { x: ox, y: oy, width: drawW, height: drawH });
  }

  const outBytes = await outPdf.save();
  fs.writeFileSync(outPath, outBytes);
  return outPath;
}

(async () => {
  const args = process.argv.slice(2);
  if (args.length < 6) {
    console.error('Usage: node scripts/render_and_merge.js <appUrl> <orderId> <letterheadPath> <outPath> <offsetX> <offsetY> <unit>');
    process.exit(2);
  }
  const [appUrl, orderId, letterheadPath, outPath, offsetX, offsetY, unit] = args;
  const overlayTmp = path.join(__dirname, 'tmp_overlay_' + orderId + '.pdf');
  await renderOverlay(appUrl, orderId, overlayTmp);
  await merge(letterheadPath, overlayTmp, outPath, offsetX, offsetY, unit);
  console.log('Rendered and merged to', outPath);
})();
