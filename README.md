# WAP-2

## 1. Introduction
Simpel PHP framework voor het vak Web Application Programming 2.

# Install
## Setup DB
1. Maak een database aan.
2. Importeer de database.sql file in de database.
3. Maak een .env file aan in de root van het project.
4. Voeg de volgende variabelen toe aan de .env file:

DB_HOST=localhost
DB_NAME=database_name
DB_USER=database_user
DB_PASS=database_password

5. Pas de variabelen aan naar de juiste waarden.

## Setup Apache
1. Zorg ervoor dat de apache rewrite module is ingeschakeld.
2. Zorg ervoor dat de apache vhost is ingesteld op de public folder van het project.

## Test Accounts 
### Admin
Email: admin@admin.nl
Password: admin1234

### Docent
Email: test@test.nl
Password: 12345678

### Student
Email:student@student.nl
Password: 12345678

### 
Je kan zelf ook registeren. Alleen kunnen admins de rollen aanpassen naar Docent of Admin.