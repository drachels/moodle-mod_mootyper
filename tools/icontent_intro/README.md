# MooTyper Content Pages Intro Toolkit

This toolkit helps you quickly deploy a standardized 8-page intro into a Content Pages activity (`mod_icontent`) for any MooTyper demo course.

## Files

- `intro_pages_template.json`
  - Reusable 8-page skeleton with placeholders.
- `course_variables_template.json`
  - Variable list to customize by course.
- `course_variables_example_cherokee.json`
  - Filled example based on your Cherokee pilot pattern.
- `question_bank_starter.md`
  - Starter checkpoint question sets (short, medium, teacher-facing).
- `deploy_icontent_intro.php`
  - CLI script to replace existing pages for a target `icontentid`.
- `apply_general_section_baseline.php`
  - CLI script to ensure the two baseline info items are present after intro in section 0.

## Placeholder model

The page template uses tokens such as `{{COURSE_NAME}}` and `{{READINESS_CHECK_BLOCK}}`.

At deploy time, each token is replaced with values from your variables JSON.

## Dry run

```bash
php83 mod/mootyper/tools/icontent_intro/deploy_icontent_intro.php \
  --moodleroot=/var/www/moodledemo \
  --icontentid=5 \
  --cmid=8517 \
  --vars=/var/www/moodledev/public/mod/mootyper/tools/icontent_intro/course_variables_example_cherokee.json \
  --dry-run=1
```

## Apply changes

```bash
php83 mod/mootyper/tools/icontent_intro/deploy_icontent_intro.php \
  --moodleroot=/var/www/moodledemo \
  --icontentid=5 \
  --cmid=8517 \
  --vars=/var/www/moodledev/public/mod/mootyper/tools/icontent_intro/course_variables_example_cherokee.json
```

## Notes

- The script uses **replace existing pages** behavior (delete then insert 1..N).
- It updates `icontent.maxpages` to match the template page count.
- Use `--dry-run=1` first to validate substitutions before writing DB rows.

## Repeatable baseline step (General section extras)

Use this after populating a new course so section 0 has the standard item flow:

1. Announcements
2. Guided Introduction (language-specific)
3. Method of carrying out the exercises
4. Initial position of the fingers on the keyboard

Dry run:

```bash
php83 mod/mootyper/tools/icontent_intro/apply_general_section_baseline.php \
  --moodleroot=/var/www/moodledev/public \
  --sourcecourseid=1081 \
  --targetcourseids=1062,1088 \
  --dry-run=1
```

Apply:

```bash
php83 mod/mootyper/tools/icontent_intro/apply_general_section_baseline.php \
  --moodleroot=/var/www/moodledev/public \
  --sourcecourseid=1081 \
  --targetcourseids=1062,1088
```

Category-tree scope (portable across sites):

```bash
php83 mod/mootyper/tools/icontent_intro/apply_general_section_baseline.php \
  --moodleroot=/var/www/moodledev/public \
  --sourcecourseid=1081 \
  --rootcategoryid=4
```

You can combine both scopes in one run (`--targetcourseids` and `--rootcategoryid`).

Behavior:

- Idempotent for existing items by title in section 0 (does not create duplicates when they are already present).
- Reorders section 0 so the two baseline items are immediately after intro.
- Keeps the intro item language-specific in each target course.
- Target scope is dynamic per site: explicit course IDs and/or a root category with all descendants.
