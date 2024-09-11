These are the git commits from my project:

6a0ba57 - Refactor FITProcessor to optimize heart rate processing
A little bit of cleanup in the code with the goal of making it more readable and extensible.

0d70da4 - Chart improvements

I learned how to properly condfiure the charting library to display the labels in the x-axis. That means i can now remove the array with the blank strings that i used to make the labels appear in the right place and just use the timestamps to create the labels.

b544cc1 - labels work
At this point, i haven't discovered the configuration options fot the display of labels in the x-axis in the charting library. So i had to creeate an array with lots of blank strings to make the labels appear in the right place.

0f202a2 - fit proccessor update
In this commit i tried create optimized arrays that are optimized to be stored directly in the database for quick access and also i nthe right format to be used by the charting library.

baf47ce - add type and date to gpx
Workout Type and Date can now also be read from fit files using the PHPFitFileAnalysis library

5b88e46 - fit file hr processing works
I wasn't able to properly read the heart rate data from the gpx file because it was embedded inside the Tag and not inbetween two tags like it is usually. After some researching i found the libaray PHPFitFileAnalysis wich allows you to read and proccess fit files using php. The fit format is the other one of the two export options on strava. It was originnally developed by Garmin and is now the most commonly used format used by Fitnesstrackers. 

1fb8c58 - gpx processor with xml-wrangler
Unfortunately, the simplexml_load_file() function was limited to reading the first few levels of the xml file. So i used this libaray to parse the xml file instead. 

fa5a548 - gpx processor
It turns out that Strava, the most popular Platform among cyclists, does not support the export of tcx files, so I added a GPX Processor. Luckily both Formats are very similar in that they are both xml based. They do however use a slightly different structure. So I had to change a few things to properly parse the files. 

b8c03fb - remove test component

91d04c7 - chart styling

3c9c221 - chart works

fc743a8 - reading tcx in vue component

ffa34a2 - show template
This template is used to show an overview of a single workout. At this point it is very basic but the Plan is to add charts and interesting metadata to it.

b859d4f - index template
Added a very basic index template that lists all workouts that the logged in user has uploaded

72facbc - add workout controller
Added a dedicated workout controller, to handle all funcionality regarding the processing of workout files. At this point it only reads some very basic metadata like the filename and mimetype and creates a database entry with this data. At this point the database does not contain any recorded data like the heart rate or speed .

27d5bf6 - upload works
It is now possible to upload files to the server. The uploaded files are stored in the /storage directory.

3d73afe - change to useForm
I implemented the Intertia Form helper to handle the File upload to make things easier. To use it it had to use the UseForm Function. 

8615f08 - add name column to workouts table

0980686 - form validation
The Form get validated and shows the error messages from the backend instead of the default browser validation hints

455db4c - create workout table and model
I created a new table in the sqlite database to store metadata and processed arrays with different types of data. I also created the corresponding Eloquent Model

4f6b866 - Form works
I created a basic form to test the database connection and the vue integration

2d3b1e1 - configure flowbite
I use flowbite as an UI library, because it features plain html components, which are easy to copy and past into any project, regardless of which frontentframework is being used

4a509e6 - Install Breeze
this is an automated commited from the laravel breeze installer

8a84cce - Set up a fresh Laravel app
this is an automated commited from the laravel breeze installer