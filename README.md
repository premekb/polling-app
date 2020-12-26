# polling-app
This is a project, which I have build as a part of the "Introduction to web development" course at my university. The app is build using MySQL, PHP, JS and the google charts library. [Link to the app](http://premekbelka.com/polls).

# Functionality
- Login system with various levels of privileges (guest, user, admin)
- Creating polls
- Voting
- Switching themes (light, dark)

# PHP code structure
- Controllers
  - index.php
  - login.php
  - new_poll.php
  - poll.php
  - register.php
- Include files
  - Template
    - header.php
  - Libraries
    - errors_include.php
    - polls_include.php
    - title_include.php
    - validation_include.php
  - Database connection
    - db_connection.php
  - Form handlers 
    - ajax_try.php
    - block_user_include.php
    - delete_poll_include.php
    - login_include.php
    - new_poll_include.php
    - register_include.php
    - vote_include.php
  - Others
    - logout_include.php
    - skin_switch_include.php
