#!/usr/bin/env node
/**
 * Simple PDF merge/overlay using pdf-lib
 * Usage: node scripts/pdf_merge.js <letterheadPath> <contentPath> <outPath> <offsetX> <offsetY> <unit>
 * unit: 'mm'|'pt'|'percent'
 */
const fs = require('fs');
const { PDFDocument } = require('pdf-lib');

async function main() {
  const args = process.argv.slice(2);
  if (args.length < 6) {
    console.error('Usage: node pdf_merge.js <letterheadPath> <contentPath> <outPath> <offsetX> <offsetY> <unit>');
    process.exit(2);
  }

  const [letterheadPath, contentPath, outPath, offsetXRaw, offsetYRaw, unit] = args;
  if (!fs.existsSync(letterheadPath)) {
    console.error('Letterhead not found:', letterheadPath);
    process.exit(3);
  }
  if (!fs.existsSync(contentPath)) {
    console.error('Content PDF not found:', contentPath);
    process.exit(4);
  }

  const letterheadBytes = fs.readFileSync(letterheadPath);
  const contentBytes = fs.readFileSync(contentPath);

  const letterheadPdf = await PDFDocument.load(letterheadBytes);
  const contentPdf = await PDFDocument.load(contentBytes);

  const outPdf = await PDFDocument.create();

  const lhPageCount = letterheadPdf.getPageCount();
  const contentPageCount = contentPdf.getPageCount();

  // We'll overlay page-by-page: for each page in letterhead, place corresponding content page (or first page)
  for (let i = 0; i < lhPageCount; i++) {
    const [lhEmbedded] = await outPdf.embedPages([letterheadPdf.getPage(i)]);
    const lhDims = lhEmbedded.size;

    // create a new page sized to the letterhead page
    const page = outPdf.addPage([lhDims.width, lhDims.height]);
    // draw the letterhead
    page.drawPage(lhEmbedded, { x: 0, y: 0, width: lhDims.width, height: lhDims.height });

    // determine which content page to use: use same index if exists, otherwise use first
    const contentIndex = Math.min(i, contentPageCount - 1);
    const [ctEmbedded] = await outPdf.embedPages([contentPdf.getPage(contentIndex)]);
    const ctDims = ctEmbedded.size;

    // compute offsets in PDF points (1 mm = 2.834645669 pts)
    let offsetX = parseFloat(offsetXRaw) || 0;
    let offsetY = parseFloat(offsetYRaw) || 0;
    if (unit === 'mm') {
      offsetX = offsetX * 2.834645669;
      offsetY = offsetY * 2.834645669;
    } else if (unit === 'percent') {
      offsetX = (offsetX / 100.0) * lhDims.width;
      offsetY = (offsetY / 100.0) * lhDims.height;
    } // else assume pts

    // If content and letterhead differ in size, scale content to fit letterhead while preserving aspect ratio
    let drawWidth = ctDims.width;
    let drawHeight = ctDims.height;
    const scaleX = lhDims.width / ctDims.width;
    const scaleY = lhDims.height / ctDims.height;
    const scale = Math.min(scaleX, scaleY);
    drawWidth = ctDims.width * scale;
    drawHeight = ctDims.height * scale;

    // place content with computed offsets; pdf-lib uses bottom-left origin, so we keep offsetY as-is
    page.drawPage(ctEmbedded, {
      x: offsetX,
      y: offsetY,
      width: drawWidth,
      height: drawHeight,
      opacity: 1.0,
    });
  }

  const outBytes = await outPdf.save();
  fs.writeFileSync(outPath, outBytes);
  console.log('Wrote merged PDF to', outPath);
}

main().catch(err => {
  console.error('pdf_merge error:', err);
  process.exit(1);
});
