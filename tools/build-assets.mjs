// Spojí a zminifikuje verejné CSS a JS do assets/dist/. Spustenie: node tools/build-assets.mjs
// Po každej zmene v assets/css/*.css alebo assets/js/{consent,contact-fab,main,fx}.js treba spustiť znova a commitnúť assets/dist/.
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { execFileSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const css = ['tokens', 'base', 'components', 'animations', 'pages', 'fx'].map(n => `assets/css/${n}.css`);
const js = ['consent', 'contact-fab', 'main', 'fx'].map(n => `assets/js/${n}.js`);

function bundle(files, loader, out) {
  const src = files.map(f => readFileSync(join(root, f), 'utf8')).join('\n');
  const min = execFileSync('npx', ['--yes', 'esbuild', `--loader=${loader}`, '--minify'], {
    input: src, shell: true, maxBuffer: 32 * 1024 * 1024,
  }).toString();
  writeFileSync(join(root, out), min);
  console.log(out, Math.round(src.length / 1024) + ' kB ->', Math.round(min.length / 1024), 'kB');
}

mkdirSync(join(root, 'assets/dist'), { recursive: true });
bundle(css, 'css', 'assets/dist/site.min.css');
bundle(js, 'js', 'assets/dist/site.min.js');
