The Team
* **Maya** (France) - Lead Developer
* **Jonida** (Albania) - Full-stack Dev (Module 4: Agenda)
* **Gerald** (Albania) - 
* **Koalima** (Vietnam) - Full-stack Dev (Module 4: Agenda)
* **Viacheslav**(France)  -

##  Project Structure (MVC-ish)
To keep the code clean and avoid conflicts, we use a **Front Controller** pattern:

* **`/assets`**: All static files (CSS, JavaScript, Images).
* **`/config`**: Database connection settings (`db.php`).
* **`/includes`**: Reusable PHP components (Header, Footer, Navbar).
* **`/lang`**: Translation files for Multilingual support (FR, AL, VI).
* **`/models`**: The **M** in MVC. All SQL queries and database logic.
* **`/views`**: The **V** in MVC. Pure HTML/PHP display files.
* **`/sql`**: Contains the `structure.sql` file to initialize the database.
* **`index.php`**: The unique entry point (The Controller).

modules:
1)Auth & Multilingual: Sign-up, login, and the 3-language system.
2)Profile & Search: User profiles, skills (#hashtags), and the search engine.
 3)The Map: Integrating the interactive map to locate experts (using JS/Leaflet).
 4)The Agenda: Managing Private, Shared, and Public events (The core of the project!).
5)Messaging & Social: Inbox system and "Friend/Follower" logic
