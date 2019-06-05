# Asthma Diary
**This ist currently under development and not well tested in production!**

Asthma Diary is a Nextcloud app to track your daily asthmatical symptoms and
medication. Additionally its possible to manage your Peak-Flow measurements
and visualize their values.

## Features
Track everything related to your asthma on a daily basis:

* Main asthmatical symptoms like:
    * Breathlessness
    * Cough
    * Phlegm 
    
* Up to 3 medications with their dose 
* PRN medication puffs
* Additional symptoms and notes.
* Peak-Flow measurements

## Dev

### Building 

The app can be built by using the provided Makefile by running:


    #install dev dependencies
    make dev-setup
    
    # build js for dev and watch changes
    make build-js

### Running tests
You can use the provided Makefile to run all tests by using:

    make test-php
