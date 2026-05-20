<?php
use Illuminate\Support\Facades\Schedule;

Schedule::command('uvoz:update-streaks')->daily();
Schedule::command('uvoz:update-trending')->everyFifteenMinutes();
Schedule::command('uvoz:expire-status-posts')->hourly();
Schedule::command('horizon:snapshot')->everyFiveMinutes();
