import fs from 'node:fs';
import readline from 'node:readline';

function getArg(name, fallback = '') {
  const idx = process.argv.indexOf(`--${name}`);
  if (idx === -1) return fallback;
  const v = process.argv[idx + 1];
  if (!v || v.startsWith('--')) return fallback;
  return v;
}

function csvEscape(value) {
  const s = value == null ? '' : String(value);
  if (/[",\r\n]/.test(s)) return `"${s.replace(/"/g, '""')}"`;
  return s;
}

function unescapeMysqlStringToken(s) {
  // s ya viene sin comillas externas.
  let out = '';
  for (let i = 0; i < s.length; i++) {
    const ch = s[i];
    if (ch !== '\\') {
      out += ch;
      continue;
    }
    i++;
    if (i >= s.length) break;
    const esc = s[i];
    switch (esc) {
      case '0': out += '\0'; break;
      case 'b': out += '\b'; break;
      case 'n': out += '\n'; break;
      case 'r': out += '\r'; break;
      case 't': out += '\t'; break;
      case 'Z': out += String.fromCharCode(26); break;
      case "'": out += "'"; break;
      case '"': out += '"'; break;
      case '\\': out += '\\'; break;
      default: out += esc; break;
    }
  }
  return out;
}

function parseInsertValues(valuesPart) {
  // valuesPart ejemplo: "(1,'a',NULL),(2,'b',NULL)"
  const rows = [];
  let i = 0;
  const len = valuesPart.length;

  while (i < len) {
    while (i < len && valuesPart[i] !== '(') i++;
    if (i >= len || valuesPart[i] !== '(') break;
    i++; // consume '('

    const row = [];
    let token = '';
    let inString = false;
    let wasQuoted = false;

    while (i < len) {
      const ch = valuesPart[i];

      if (inString) {
        if (ch === '\\') {
          // mantener escape para tratarlo al final del token
          token += ch;
          i++;
          if (i < len) token += valuesPart[i];
          i++;
          continue;
        }
        if (ch === "'") {
          inString = false;
          i++;
          continue;
        }
        token += ch;
        i++;
        continue;
      }

      if (ch === "'") {
        inString = true;
        wasQuoted = true;
        i++;
        continue;
      }

      if (ch === ',') {
        row.push(castToken(token, wasQuoted));
        token = '';
        wasQuoted = false;
        i++;
        continue;
      }

      if (ch === ')') {
        row.push(castToken(token, wasQuoted));
        token = '';
        i++; // consume ')'
        break;
      }

      token += ch;
      i++;
    }

    rows.push(row);
    i++; // avanzar (coma, etc.)
  }

  return rows;
}

function castToken(token, wasQuoted) {
  if (wasQuoted) return unescapeMysqlStringToken(String(token));
  const trim = String(token).trim();
  if (trim === '') return '';
  if (trim.toUpperCase() === 'NULL') return null;
  if (/^-?\d+$/.test(trim)) return Number.parseInt(trim, 10);
  if (/^-?\d+\.\d+$/.test(trim)) return Number.parseFloat(trim);
  return trim;
}

function jsonLang(value, lang) {
  if (value == null) return '';
  if (typeof value !== 'string') return String(value);
  const v = value.trim();
  if (!v) return '';
  try {
    const obj = JSON.parse(v);
    if (obj && typeof obj === 'object') {
      if (typeof obj[lang] === 'string' && obj[lang].trim()) return obj[lang];
      for (const fb of ['es', 'en']) {
        if (typeof obj[fb] === 'string' && obj[fb].trim()) return obj[fb];
      }
      for (const k of Object.keys(obj)) {
        if (typeof obj[k] === 'string' && obj[k].trim()) return obj[k];
      }
    }
  } catch {
    // ignore
  }
  return v;
}

function extractVideoUrl(videoValue) {
  if (videoValue == null) return '';
  if (typeof videoValue !== 'string') return '';
  const v = videoValue.trim();
  if (!v) return '';
  try {
    const obj = JSON.parse(v);
    if (obj && typeof obj === 'object') {
      if (typeof obj.url === 'string') return obj.url.trim();
      if (typeof obj.URL === 'string') return obj.URL.trim();
    }
  } catch {
    // fallback regex
  }
  const m = v.match(/https?:\/\/[^\s"']+/i);
  return m ? m[0] : '';
}

const sqlPath = getArg('sql', '');
const outPath = getArg('out', '');
const lang = getArg('lang', 'es') || 'es';
const videoUrlWanted = getArg('videoUrl', '').trim(); // opcional: si está vacío, exporta TODOS los que tienen video.url

if (!sqlPath || !outPath) {
  console.error('Uso: node docu/extract-centro-de-ayuda-vimeo.mjs --sql <dump.sql> [--videoUrl <url>] --out <out.csv> [--lang es]');
  process.exit(2);
}

const ARTICLES_COLUMNS = [
  'id',
  'category_id',
  'tags',
  'status',
  'slug',
  'title',
  'text',
  'video_type',
  'video',
  'active_languages',
  'featured',
  'visits',
  'created_at',
  'updated_at',
];

const rl = readline.createInterface({
  input: fs.createReadStream(sqlPath, { encoding: 'utf8' }),
  crlfDelay: Infinity,
});

const matches = [];

for await (const line of rl) {
  const trimmed = line.trimStart();
  if (!trimmed.startsWith('INSERT INTO `articles` VALUES ')) continue;

  const valuesPos = trimmed.indexOf(' VALUES ');
  if (valuesPos === -1) continue;

  let valuesPart = trimmed.slice(valuesPos + ' VALUES '.length).trim();
  if (valuesPart.endsWith(';')) valuesPart = valuesPart.slice(0, -1);

  const rows = parseInsertValues(valuesPart);
  for (const row of rows) {
    if (row.length !== ARTICLES_COLUMNS.length) continue;
    const assoc = {};
    for (let i = 0; i < ARTICLES_COLUMNS.length; i++) assoc[ARTICLES_COLUMNS[i]] = row[i];

    const videoUrl = extractVideoUrl(assoc.video);
    if (!videoUrl) continue;
    if (videoUrlWanted && videoUrl !== videoUrlWanted) continue;

    matches.push({
      sql_id: String(assoc.id ?? ''),
      slug: jsonLang(assoc.slug, lang),
      title: jsonLang(assoc.title, lang),
      video_url: videoUrl,
    });
  }
}

matches.sort((a, b) => Number(a.sql_id) - Number(b.sql_id));

const header = ['sql_id', 'slug', 'title', 'video_url'];
const lines = [header.join(',')];
for (const m of matches) {
  lines.push([m.sql_id, m.slug, m.title, m.video_url].map(csvEscape).join(','));
}

fs.writeFileSync(outPath, lines.join('\r\n') + '\r\n', 'utf8');
console.log(`OK: ${matches.length} filas -> ${outPath}`);

