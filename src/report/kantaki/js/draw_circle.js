'use strict';

(() => {
  const CANVAS_WIDTH = 320;
  const CANVAS_HEIGHT = 320;
  const strokeColor = [255, 255, 255, 255];
  const canvas = document.getElementById('image_canvas');
  const ctx = canvas.getContext('2d');

  // Canvas縺ｮ螟ｧ縺阪＆險ｭ螳�
  canvas.width = CANVAS_WIDTH;
  canvas.height = CANVAS_HEIGHT;

  // 鮟偵〒蝪励ｊ縺､縺ｶ縺�
  ctx.fillStyle = 'rgba(0, 0, 0, 255)';
  ctx.fillRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

  // 謠冗判髢句ｧ区凾縺ｮ諠��ｱ險俶�逕ｨ
  let imageData_org = null;
  const startPos = { x: null, y: null };

  // 繝峨ャ繝医�螟ｧ縺阪＆險ｭ螳�
  let pixelSize = parseInt(document.getElementById('pixel-size').value, 10);

  // 繝峨ャ繝医�螟ｧ縺阪＆螟画峩譎�
  document.getElementById('pixel-size').addEventListener('change', (e) => {
    pixelSize = parseInt(e.target.value, 10);
  });

  // 謠冗判髢句ｧ区凾
  canvas.addEventListener('mousedown', (e) => {
    if (imageData_org !== null) {
      return;
    }
    e.preventDefault();
    const imageData = ctx.getImageData(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

    // Canvas蜀�〒縺ｮ繧､繝吶Φ繝育匱逕溷ｺｧ讓吶ｒ邂怜�
    const bounds = e.target.getBoundingClientRect();
    const x = e.clientX - bounds.left;
    const y = e.clientY - bounds.top;

    // 謠冗判髢句ｧ区凾縺ｮ蠎ｧ讓吶→繝斐け繧ｻ繝ｫ諠��ｱ繧剃ｿ晄戟縺励※縺翫￥
    startPos.x = x;
    startPos.y = y;
    imageData_org = ctx.getImageData(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

    // 繧､繝吶Φ繝亥ｺｧ讓吶°繧迂mageData縺ｫ讌募�繧呈緒逕ｻ
    putPixelEllipseByEventCoords(
      imageData,
      startPos.x,
      startPos.y,
      x,
      y,
      pixelSize
    );
    ctx.putImageData(imageData, 0, 0);
  });

  // 謠冗判髢句ｧ句ｾ後↓繧ｫ繝ｼ繧ｽ繝ｫ遘ｻ蜍墓凾
  canvas.addEventListener('mousemove', (e) => {
    if (imageData_org === null) {
      return;
    }
    e.preventDefault();
    const imageData = ctx.getImageData(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

    // 謠冗判遒ｺ螳壹＠縺ｦ縺ｪ縺�･募�繧呈ｶ医☆
    copyImageData(imageData_org, imageData);

    // Canvas蜀�〒縺ｮ繧､繝吶Φ繝育匱逕溷ｺｧ讓吶ｒ邂怜�
    const bounds = e.target.getBoundingClientRect();
    const x = e.clientX - bounds.left;
    const y = e.clientY - bounds.top;

    // 繧､繝吶Φ繝亥ｺｧ讓吶°繧迂mageData縺ｫ讌募�繧呈緒逕ｻ
    putPixelEllipseByEventCoords(
      imageData,
      startPos.x,
      startPos.y,
      x,
      y,
      pixelSize
    );
    ctx.putImageData(imageData, 0, 0);
  });

  // 謠冗判遒ｺ螳壽凾
  canvas.addEventListener('mouseup', (e) => {
    if (imageData_org === null) {
      return;
    }
    e.preventDefault();
    const imageData = ctx.getImageData(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

    // 謠冗判遒ｺ螳壹＠縺ｦ縺ｪ縺�･募�繧呈ｶ医☆
    copyImageData(imageData_org, imageData);

    // Canvas蜀�〒縺ｮ繧､繝吶Φ繝育匱逕溷ｺｧ讓吶ｒ邂怜�
    const bounds = e.target.getBoundingClientRect();
    const x = e.clientX - bounds.left;
    const y = e.clientY - bounds.top;

    // 繧､繝吶Φ繝亥ｺｧ讓吶°繧迂mageData縺ｫ讌募�繧呈緒逕ｻ
    putPixelEllipseByEventCoords(
      imageData,
      startPos.x,
      startPos.y,
      x,
      y,
      pixelSize
    );
    ctx.putImageData(imageData, 0, 0);

    // 謠冗判髢句ｧ区凾縺ｫ菫晏ｭ倥＠縺溷､繧堤�ｴ譽�
    startPos.x = startPos.y = null;
    imageData_org = null;
  });

  /**
   * ImageData縺ｮ繝斐け繧ｻ繝ｫ諠��ｱ繧偵さ繝斐�縺吶ｋ
   * @param {ImageData} src - 繧ｳ繝斐�蜈オmageData
   * @param {ImageData} dst - 繧ｳ繝斐�蜈�ImageData
   */
  const copyImageData = (src, dst) => {
    for (let i = 0, len = dst.data.length; i < len; ++i) {
      dst.data[i] = src.data[i];
    }
  };

  /**
   * 繧､繝吶Φ繝亥ｺｧ讓吶°繧峨ラ繝�ヨ縺ｮ讌募�繧呈緒逕ｻ
   * @param {ImageData} imageData
   * @param {Number} startX - 謠冗判髢句ｧ句ｺｧ讓儿
   * @param {Number} startY - 謠冗判髢句ｧ句ｺｧ讓兀
   * @param {Number} x - 繧､繝吶Φ繝亥ｺｧ讓儿
   * @param {Number} y - 繧､繝吶Φ繝亥ｺｧ讓兀
   * @param {Number} pixelSize - 繝峨ャ繝医�螟ｧ縺阪＆
   */
  const putPixelEllipseByEventCoords = (
    imageData,
    startX,
    startY,
    x,
    y,
    pixelSize
  ) => {
    // 繧ｯ繝ｪ繝�け蠎ｧ讓吶→繧ｯ繝ｪ繝�け髢句ｧ句ｺｧ讓吶°繧画ｨｪ縺ｨ繧ｿ繝��髟ｷ縺輔ｒ邂怜�
    let a = (x - startX) / 2;
    let b = (y - startY) / 2;

    // 繧ｯ繝ｪ繝�け蠎ｧ讓吶�讓ｪ繝ｻ繧ｿ繝��髟ｷ縺輔ｒ繝峨ャ繝医�螟ｧ縺阪＆縺ｮ謨ｴ謨ｰ蛟阪↓陬懈ｭ｣
    x = x - (x % pixelSize);
    y = y - (y % pixelSize);
    a = Math.floor(a - (a % pixelSize));
    b = Math.floor(b - (b % pixelSize));

    // 荳ｭ蠢�ｺｧ讓咏ｮ怜�
    const cx = Math.floor(startX + a);
    const cy = Math.floor(startY + b);

    // b霎ｺ縺ｮ髟ｷ縺輔′繝槭う繝翫せ (繧ｫ繝ｼ繧ｽ繝ｫ繧剃ｸ頑婿蜷代↓蜍輔°縺励◆譎�) 縺�縺｣縺溘ｉ陬懈ｭ｣
    b = b < 0 ? -1 * b : b;

    if (a === b) {
      // 邵ｦ讓ｪ縺ｮ髟ｷ縺輔′蜷後§縺ｪ繧牙�繧呈緒逕ｻ (縺薙▲縺｡縺ｮ譁ｹ縺檎ｶｺ鮗励□縺九ｉ)
      putPixelCircle(imageData, cx, cy, a, pixelSize);
    } else {
      // 讌募�繧呈緒逕ｻ
      putPixelEllipse(imageData, cx, cy, a, b, pixelSize);
    }
  };

  /**
   * ImageData縺ｫ繝峨ャ繝医�蜀�ｒ謠冗判縺吶ｋ
   * @param {ImageData} imageData
   * @param {Number} cx - 荳ｭ蠢ス蠎ｧ讓�
   * @param {Number} cy - 荳ｭ蠢ズ蠎ｧ讓�
   * @param {Number} radius - 蜊雁ｾ�
   * @param {Number} pixelSize - 繝峨ャ繝医�螟ｧ縺阪＆
   */
  const putPixelCircle = (imageData, cx, cy, radius, pixelSize) => {
    const diameter = radius * 2; // 逶ｴ蠕�
    let x = radius; // 蜊雁ｾ� - 邱壼ｹ�
    let y = 0;
    let dx = 1;
    let dy = 2; // 繧上°繧薙↑縺�￠縺ｩ1縺�縺ｨ蜊雁ｾ�′蟆上＆縺�凾縺ｫ蝗幄ｧ偵↓縺ｪ縺｣縺｡繧�≧縺九ｉ2縺ｫ縺励◆
    let decisionOver2 = dx - diameter;

    // 0繝ｻ90繝ｻ180繝ｻ270蠎ｦ縺ｮ蝨ｰ轤ｹ縺九ｉ荳｡蛛ｴ縺ｫ蜷代°縺｣縺ｦ邱壹ｒ莨ｸ縺ｰ縺励※縺�″縲∬ｨ�8譛ｬ縺ｮ蠑ｧ繧呈嶌縺�
    while (x >= y) {
      putPixel(imageData, cx + x, cy - y, pixelSize); //   0 ->  45
      putPixel(imageData, cx + y, cy - x, pixelSize); //  45 <-  90
      putPixel(imageData, cx - y, cy - x, pixelSize); //  90 -> 135
      putPixel(imageData, cx - x, cy - y, pixelSize); // 135 <- 180
      putPixel(imageData, cx - x, cy + y, pixelSize); // 180 -> 225
      putPixel(imageData, cx - y, cy + x, pixelSize); // 225 <- 270
      putPixel(imageData, cx + y, cy + x, pixelSize); // 270 -> 315
      putPixel(imageData, cx + x, cy + y, pixelSize); // 315 <- 360

      // 繧上°繧薙↑縺�
      if (decisionOver2 <= 0) {
        y += pixelSize;
        decisionOver2 += dy * pixelSize;
        dy += 2 * pixelSize;
      }
      if (decisionOver2 > 0) {
        x -= pixelSize;
        dx += 2 * pixelSize;
        decisionOver2 += (-diameter + dx) * pixelSize;
      }
    }
  };

  /**
   * ImageData縺ｫ繝峨ャ繝医�讌募�繧呈緒逕ｻ縺吶ｋ
   * @param {ImageData} imageData
   * @param {Number} cx - 荳ｭ蠢ス蠎ｧ讓�
   * @param {Number} cy - 荳ｭ蠢ズ蠎ｧ讓�
   * @param {Number} a - 讓ｪ縺ｮ髟ｷ縺�
   * @param {Number} b - 繧ｿ繝��髟ｷ縺�
   * @param {Number} pixelSize - 繝峨ャ繝医�螟ｧ縺阪＆
   */
  const putPixelEllipse = (imageData, cx, cy, a, b, pixelSize) => {
    const aa = a * a;
    const bb = b * b;
    const aa2 = 2 * aa;
    const bb2 = 2 * bb;
    let p;
    let x = 0;
    let y = b;
    let px = 0;
    let py = aa2 * y;

    // 譛蛻昴�轤ｹ繧呈緒逕ｻ
    putPixel(imageData, cx + x, cy + y, pixelSize);
    putPixel(imageData, cx - x, cy + y, pixelSize);
    putPixel(imageData, cx + x, cy - y, pixelSize);
    putPixel(imageData, cx - x, cy - y, pixelSize);

    // 荳贋ｸ九ｒ謠冗判 (繧上°繧薙↑縺�)
    p = bb - aa * b + 0.25 * aa;
    while (px < py) {
      x += pixelSize;
      px += bb2 * pixelSize;
      if (p < 0) {
        p += bb + px * pixelSize;
      } else {
        y -= pixelSize;
        py -= aa2 * pixelSize;
        p += (bb + px - py) * pixelSize;
      }
      putPixel(imageData, cx + x, cy + y, pixelSize);
      putPixel(imageData, cx - x, cy + y, pixelSize);
      putPixel(imageData, cx + x, cy - y, pixelSize);
      putPixel(imageData, cx - x, cy - y, pixelSize);
    }

    // 蟾ｦ蜿ｳ繧呈緒逕ｻ (繧上°繧薙↑縺�)
    p = bb * (x + 0.5) * (x + 0.5) + aa * (y - 1) * (y - 1) - aa * bb;
    while (y > 0) {
      y -= pixelSize;
      py -= aa2 * pixelSize;
      if (p > 0) {
        p += (aa - py) * pixelSize;
      } else {
        x += pixelSize;
        px += bb2 * pixelSize;
        p += (aa - py + px) * pixelSize;
      }
      putPixel(imageData, cx + x, cy + y, pixelSize);
      putPixel(imageData, cx - x, cy + y, pixelSize);
      putPixel(imageData, cx + x, cy - y, pixelSize);
      putPixel(imageData, cx - x, cy - y, pixelSize);
    }
  };

  /**
   * ImageData縺ｫ繝峨ャ繝医ｒ謠冗判縺吶ｋ
   * @param {ImageData} imageData
   * @param {Number} x - X蠎ｧ讓�
   * @param {Number} y - Y蠎ｧ讓�
   * @param {Number} pixelSize - 繝峨ャ繝医�螟ｧ縺阪＆
   */
  const putPixel = (imageData, x, y, pixelSize) => {
    // 縺ｯ縺ｿ蜃ｺ縺溘ｉ謠冗判縺励↑縺�
    if (x < 0 || CANVAS_WIDTH <= x || y < 0 || CANVAS_HEIGHT < y) {
      return;
    }
    // 謠冗判髢句ｧ句ｺｧ讓吶ｒ繝峨ャ繝医＃縺ｨ縺ｮ繝槭せ逶ｮ縺ｮ蟾ｦ荳翫↓陬懈ｭ｣
    x = x - (x % pixelSize);
    y = y - (y % pixelSize);

    // ImageData縺ｫ繝峨ャ繝域嶌縺崎ｾｼ縺ｿ
    for (let offsetY = 0; offsetY < pixelSize; ++offsetY) {
      for (let offsetX = 0; offsetX < pixelSize; ++offsetX) {
        const i = 4 * ((y + offsetY) * CANVAS_WIDTH + (x + offsetX));
        imageData.data[i] = strokeColor[0];
        imageData.data[i + 1] = strokeColor[1];
        imageData.data[i + 2] = strokeColor[2];
        imageData.data[i + 3] = strokeColor[3];
      }
    }
  };
})();
