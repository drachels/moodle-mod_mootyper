# MooTyper_1185 Quick Verification Matrix

Date: 2026-03-12

## Scope

- Auto-save attempt at exercise end before Continue can be used.
- Continue progression must be click-only (no keyboard activation).
- Server-side submit guard blocks progression if attempt is not finalized.

## Evidence Anchors (Code)

- End-save request in JS: `status=3` call in `typer.js` and `typer(ETV7).js`.
- Continue remains disabled until save callback returns.
- Continue click is pointer-gated via `continuePointerDown`.
- Server-side guard in `gcnext.php` enforces finalized attempt.

## Matrix

| Mode | Client end-save before Continue | Keyboard blocked from Continue | Server blocks unfinalized submit | Current outcome |
|---|---|---|---|---|
| Practice (`mtmode === '2'`) | Yes | Yes | Yes | PASS (code-verified) |
| Lesson (`mtmode === '0'`) | Yes | Yes | Yes | PASS (code-verified) |
| Exam (`mtmode === '1'`) | Yes | Yes | Yes | PASS (code-verified) |

## Validation Performed

- PHP syntax check passed:
  - `mod/mootyper/gcnext.php`
  - `mod/mootyper/lang/en/mootyper.php`
- Pattern verification passed for:
  - `continuePointerDown` handlers in both JS variants.
  - `status=3&attemptid` end-save calls in both JS variants.
  - Continue enable only in save callback.
  - Mode labels and branches in `view.php` (`'0'`, `'1'`, `'2'`).
  - New blocking redirects in `gcnext.php`.

## Manual UI Follow-up (Recommended)

1. In each mode, finish an exercise and press Space/Enter repeatedly at end.
   - Expected: no advance unless Continue is clicked.
2. In each mode, complete exercise and watch network requests.
   - Expected: `atchk.php?status=3` completes before Continue is usable.
3. Attempt direct POST to `gcnext.php` while attempt is still in progress.
   - Expected: redirected with blocked-submission message.
