#!/usr/bin/env bash
# scrub-db.sh — Strip personal data from a WP SQL dump.
# Usage: ./demo/scrub-db.sh backups/your-dump.sql > demo/demo.sql
#
# What it does:
#   - Replaces name, email, social URLs with placeholders
#   - Replaces WP admin email / user data
#   - Replaces site URL so it works on any localhost
#
# What it does NOT touch:
#   - Post content, portfolio items, blog posts (replace those via WP Admin)
#   - Polylang language config (kept intact)

set -euo pipefail

INPUT="${1:-}"

if [[ -z "$INPUT" ]]; then
  echo "Usage: $0 path/to/dump.sql > demo/demo.sql" >&2
  exit 1
fi

if [[ ! -f "$INPUT" ]]; then
  echo "File not found: $INPUT" >&2
  exit 1
fi

sed \
  -e "s|lexsinyaev@gmail\.com|your@email.com|g" \
  -e "s|Oleksii Siniaiev|Your Name|g" \
  -e "s|Алексей Синяев|Your Name|g" \
  -e "s|Олексій Синяєв|Your Name|g" \
  -e "s|alexsinyayev|yourprofile|g" \
  -e "s|lex127|yourusername|g" \
  -e "s|Alexs127|yourusername|g" \
  -e "s|https://alexsinyaev\.com|https://demo.example.com|g" \
  -e "s|http://alexsinyaev\.com|https://demo.example.com|g" \
  -e "s|alexsinyaev\.com|demo.example.com|g" \
  -e "s|Costa Blanca, Spain|Your City, Country|g" \
  -e "s|Costa Blanca, España|Tu Ciudad, País|g" \
  -e "s|WebSpellChecker|Your Company|g" \
  -e "s|/app/uploads/2026/03/Oleksii_Siniaiev_CV_2026\.pdf|/app/uploads/cv.pdf|g" \
  -e "s|2023-06-20-16\.59\.42|profile|g" \
  "$INPUT"
