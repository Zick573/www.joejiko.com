<?php

return array(
    
    // Interval defines the time in minutes between two run method calls - in other words, the time between the cron job route will be called
    'runInterval' => 1,
    
    // Should the Laravel build in logger handle logging
    'laravelLogging' => true,
    
    // Enable or disable database logging
    'databaseLogging' => true,
    
    // Enable or disable logging error jobs only
    'logOnlyErrorJobsToDatabase' => true,
    
    // Delte old database entries after how many hours
    'deleteDatabaseEntriesAfter' => 240,
    
);