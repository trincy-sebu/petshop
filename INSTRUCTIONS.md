## How to set up the Pet Clinic Billing Software

1.  **Create the database:**
    *   Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or the command line).
    *   Create a new database named `pet_clinic`.

2.  **Create the tables:**
    *   Import the `schema.sql` file into the `pet_clinic` database. This will create all the necessary tables.

3.  **Add sample data:**
    *   Import the `sample_data.sql` file into the `pet_clinic` database. This will populate the `users` table with a sample admin and staff user.

    *   **Admin user:**
        *   Username: `admin`
        *   Password: `admin`

    *   **Staff user:**
        *   Username: `staff`
        *   Password: `staff`

4.  **Configure the database connection:**
    *   Open the `config.php` file.
    *   If your MySQL username, password, or database name are different from the defaults (`root`, ``, `pet_clinic`), update the values in this file.

5.  **Run the application:**
    *   Place all the project files in your web server's root directory (e.g., `htdocs` for XAMPP, `www` for WAMP).
    *   Open your web browser and navigate to the project's `login.php` page (e.g., `http://localhost/pet-clinic/login.php`).
