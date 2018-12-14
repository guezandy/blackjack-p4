# Project 4 Laravel BlackJack
+ By: Andrew Rodriguez
+ Production URL: <http://p4.squareinches.com>

## Explanation
This Laravel Application is meant to mimic joining BlackJack tables and playing Black Jack at those tables.

## Database
Primary tables:
  + `users`
  + `password_resets`
  + `games`
  + `histories`
  
Pivot table(s):
  + `game_history`


## CRUD
First run all migration and seeders - this will create the default accounts as well as populate some useful data to see the app running.

NOTE: For all steps below - please login first.

__init__
  + Visit <http://p4.squareinches.com>
  + Click *Login*
  + Fill out form to Login

__Create__
  + Once logged in - Click *Create game*
  + Observe a new game created under the "In progress section"
  
__Read__
  + Once logged in - Observe all the data on the UI that has been read
  
__Update__
  + Once logged in - Click end game to update the `status` field from in progress to completed of a game
  
__Delete__
  + Once logged in - Click delete game to soft delete a game

NOTE: Also encourage you to Join a game and play a couple hands of blackjack - to also observe the create/update/read methods.

## Outside resources

## Code style divergences

## Notes for instructor
The black jack game play is not 100% logically perfect but it works for the most part. 
All the logic for the game play is in BlackJack.php
There is also no graphics - just didn't have time to add images for the cards and what not.
The main landing page after logging in has instructions - as does the game play screen when you click Join Game.
