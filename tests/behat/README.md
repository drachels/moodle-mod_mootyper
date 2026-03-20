MooTyper Behat Helpers

This folder includes helper scripts to run MooTyper Behat tests in environments
where Moodle's generated Behat config points to /wd/hub but only geckodriver is
available locally.

Scripts

- behat_env_reset.sh
  - Disables and re-enables Behat mode, then runs init.
  - Regenerates behat.yml and test theme assets.

- webdriver_gecko.sh
  - start|stop|restart|status for local geckodriver on port 4444.

- run_mootyper_behat.sh
  - Patches generated behat.yml to use geckodriver root endpoint.
  - Starts geckodriver automatically.
  - Runs vendor/bin/behat with your arguments.
  - Stops geckodriver automatically unless KEEP_WEBDRIVER=1.

Usage

1) Reset environment (recommended after config/site changes):

   mod/mootyper/tests/behat/behat_env_reset.sh

2) Run one feature:

   mod/mootyper/tests/behat/run_mootyper_behat.sh mod/mootyper/tests/behat/continue_gate.feature --format=progress --colors

Quick smoke for both new regressions:

  mod/mootyper/tests/behat/run_mootyper_behat.sh mod/mootyper/tests/behat/continue_gate.feature mod/mootyper/tests/behat/progression_from_grades.feature --format=progress --colors

3) Run MooTyper features by tag:

   mod/mootyper/tests/behat/run_mootyper_behat.sh --tags=@mod_mootyper --format=progress

4) Keep webdriver running between runs:

   KEEP_WEBDRIVER=1 mod/mootyper/tests/behat/run_mootyper_behat.sh mod/mootyper/tests/behat/progression_from_grades.feature --format=progress

Notes

- Generated Behat config is expected at:
  /var/moodledata/behatmoodledatadev/behatrun/behat/behat.yml

- If your environment later uses Selenium Hub at /wd/hub, you can run Behat
  directly with vendor/bin/behat and do not need run_mootyper_behat.sh.
