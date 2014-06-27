@echo off
:: ***********************************************************
:: Amazon SQS Queue Listener
:: @author Jordan Dalton <jordandalton@wrsgroup.com>
::
:: Launch the queue:listen command of Laravel applications
:: that utilize Amazon Web Services SQS service.
::
:: To run this script execute the following command:
:: 	start /B QueueService.bat
:: ***********************************************************
::
:: Electronic Purchase Order Sysystem
php "C:\apps\epos3\artisan" queue:listen