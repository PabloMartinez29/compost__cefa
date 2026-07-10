# PROMPT — GitHub Actions CI (multi-repo, language-agnostic)

Copia y pega este prompt completo en Cursor al abrir **cualquier** repositorio (Laravel, Flutter, Node, Python, etc.). No asumas un lenguaje: **detecta el stack del proyecto real**.

---

## Prompt (copiar desde aquí)

```text
Tu trabajo:
1. Revisar el repositorio y detectar el stack REAL (archivos de manifiesto, lockfiles, scripts de test/build, versión de runtime). No inventes un lenguaje ni herramientas que no existan en el proyecto.
2. Crear el archivo .github/workflows/ci.yml (créalo tú; yo no lo escribo a mano).
3. No despliegues nada. Solo CI: instalar dependencias, build (si aplica) y tests.
4. Usa solo herramientas gratis (GitHub Actions en repo público/privado según el plan free). No uses SonarQube/SonarCloud de pago ni servicios de membresía.
5. Al final dime exactamente qué comandos debo correr para commit y push, y dónde ver los ✓ o ✗ en GitHub.

El archivo .github/workflows/ci.yml DEBE tener este comportamiento:

- name: CI
- on:
  - push a main y develop (si existen; si no, usa la rama principal real del repo: main/master)
  - pull_request (cualquier PR)
- jobs: varios jobs en paralelo (ideal 3 o 4) para que en GitHub se vean varios checks verdes, por ejemplo:
  1) dependencies / install (Composer, npm, pub, pip, gradle, etc. según el stack)
  2) build / analyze (build de frontend, flutter analyze, compile, etc. si aplica)
  3) unit tests
  4) feature/integration/widget tests (si el proyecto los tiene; si no, divide unit vs otro check útil: lint/format)
- Cada job debe:
  - actions/checkout
  - setup del runtime correcto (PHP, Node, Flutter, Java, Python, Dart, etc.) con la versión del proyecto
  - instalar dependencias con el comando correcto del lockfile (composer install, npm ci, flutter pub get, pip install -r, etc.)
  - correr build/tests con los scripts reales del proyecto
- Si hace falta DB para tests:
  - preferir sqlite en memoria / fake / mock según el ecosistema
  - o un service container gratis en Actions (postgres/mysql) solo si el proyecto lo exige
  - ajustes de env SOLO en el workflow; no rompas el entorno local del desarrollador
- Si faltan archivos mínimos para CI (ej. .env.example), créalos o restáuralos de forma mínima y segura (sin secretos).
- Si hay tests rotos o desactualizados que impiden un CI verde, corrígelos con el mínimo cambio necesario o skip documentado solo cuando la feature no exista en el proyecto.
- Adapta el YAML al proyecto real (no inventes scripts que no existan).
- Explica breve qué hace cada job/step.
- No menciones servicios de pago como solución principal.

Reglas extra:
- Sé language-agnostic: primero inspecciona el repo, luego elige tooling.
- Prefiere checks claros y visibles (varios jobs) sobre un solo job monolítico.
- Al terminar: lista de archivos tocados + comandos exactos de git add/commit/push + dónde ver Actions.
```

---

## Cómo usarlo en otro repo

1. Abre el otro repositorio en Cursor.
2. Pega el bloque **Prompt** en el chat.
3. Revisa el `.github/workflows/ci.yml` que genere.
4. Haz commit y push.
5. En GitHub: pestaña **Actions**, o el ✓/✗ del commit/PR.

## Qué deberías ver en GitHub (ejemplo)

Varios checks, por ejemplo:

- CI / Dependencies
- CI / Build
- CI / Unit Tests
- CI / Feature Tests

(Los nombres exactos dependen del stack detectado.)

## Notas

- Este archivo es una plantilla de instrucciones para la IA, no un workflow ejecutable.
- Cada repo tendrá su propio `.github/workflows/ci.yml` generado a medida.
- Gratis: GitHub Actions + herramientas open source del propio proyecto (tests, linters, analyzers).
