// Builds dist/cyberage-lucide-icons.zip: the installable plugin (and the asset attached to each
// GitHub release, which Plugin Update Checker installs on client sites).
//
// The zip holds one folder, cyberage-lucide-icons/, with only what WordPress needs: the plugin
// PHP, includes/, assets/, vendor/, licences and readme. tools/, docs/ and node_modules/ stay out.
// Before zipping it checks that the header Version and the CYBERAGE_LUCIDE_VERSION constant agree.
//
// Run: cd tools && node release.mjs
import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { execFileSync } from "node:child_process";

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..");
const main = fs.readFileSync(path.join(ROOT, "cyberage-lucide-icons.php"), "utf8");
const header = (main.match(/^\s*\*\s*Version:\s*(\S+)/m) || [])[1];
const constant = (main.match(/CYBERAGE_LUCIDE_VERSION',\s*'([^']+)'/) || [])[1];
if (!header || header !== constant) { console.error(`Version mismatch: header ${header}, constant ${constant}`); process.exit(1); }
for (const f of ["assets/icons.json", "assets/aliases.json", "assets/picker.json", "assets/editor.css", "vendor/plugin-update-checker/plugin-update-checker.php"]) {
  if (!fs.existsSync(path.join(ROOT, f))) { console.error(`Missing ${f}: run node build-icons.mjs first`); process.exit(1); }
}

const INCLUDE = ["cyberage-lucide-icons.php", "includes", "assets", "vendor", "readme.txt", "LICENSE", "LICENSE-lucide.txt"];
const stage = path.join(ROOT, "dist", "stage", "cyberage-lucide-icons");
fs.rmSync(path.join(ROOT, "dist"), { recursive: true, force: true });
fs.mkdirSync(stage, { recursive: true });
for (const item of INCLUDE) fs.cpSync(path.join(ROOT, item), path.join(stage, item), { recursive: true });
fs.rmSync(path.join(stage, "assets", "manifest.json"), { force: true }); // build record, not needed at runtime

const zip = path.join(ROOT, "dist", "cyberage-lucide-icons.zip");
// Windows 10+ ships bsdtar as System32\tar.exe, which writes standard zip files with
// forward-slash paths. Call it by full path: in Git Bash plain "tar" is GNU tar, which reads
// "D:" as a remote host. The output path is relative to the staging folder for the same reason.
const tar = process.platform === "win32" ? path.join(process.env.SystemRoot || "C:\\Windows", "System32", "tar.exe") : "tar";
execFileSync(tar, ["-a", "-c", "-f", path.join("..", "cyberage-lucide-icons.zip"), "cyberage-lucide-icons"], { cwd: path.dirname(stage) });
fs.rmSync(path.join(ROOT, "dist", "stage"), { recursive: true, force: true });
console.log(`dist/cyberage-lucide-icons.zip  v${header}  ${(fs.statSync(zip).size / 1024).toFixed(0)} KB`);
