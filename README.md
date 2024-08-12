# Spring Assignment

This is a web application built with PHP, Laravel, and Vue.js for managing users. It includes features such as user creation, sorting, and more.

## Prerequisites

Ensure you have the following installed:

- [PHP](https://www.php.net/) (version 8.0 or higher recommended)
- [Composer](https://getcomposer.org/) (for PHP dependency management)
- [Node.js](https://nodejs.org/) (version 22 or higher recommended)
- [npm](https://www.npmjs.com/) (comes with Node.js)
- [Laravel](https://laravel.com/) (ensure you have the Laravel CLI if needed)
- [Vuejs](https://vuejs.org/) (ensure you have the Vuejs 3 or higher)

## Getting Started

### Backend (Laravel)

1. Clone the Repository
git clone https://github.com/iriteshkumarmishra/spring-assignment.git && cd spring-assignment

2. Set Up Environment
Copy the example environment file and update the environment variables:
cp .env.example .env

3. Install PHP Dependencies
composer install

4. Generate Application Key
php artisan key:generate

5. Run Migrations
php artisan migrate

6. Start the Laravel Development Server
   ```
   php artisan storage:link
   php artisan db:seed --class=UserDatabaseSeeder
   php artisan serve

   The backend will be available at http://localhost:8000.

8. Install Node.js Dependencies
npm install

9. Run the Development Server
npm run dev

Note : http://localhost:8000/user-lists, Open this URL to see the leader board


Features

	•	User Creation Form: A modal popup for adding new users with fields for name, age, address, state, city, and zip.
	•	User List: Displays a list of users with sorting functionality by name and points.
	•	Debounced Keyboard Event: 700 ms debounce time for keyboard click events.

Usage

	•	Create User: Click on the “Add User” button to open the user creation form modal. Fill in the details and submit to create a new user.
	•	Sort Users: Click on the column headers (Name or Points) to sort the list.

Testing
Backend (Laravel)

To run Laravel unit tests:
php artisan test


**Endpoints**

1. User Lists
GET api/users?page_size=25&page_number=1&sort_key=points&sort_order=desc&name=ce
Response :
```
[
  {
    "id": 173,
    "name": "Tessie Flatley IV",
    "age": 54,
    "points": 98,
    "address_id": 155,
    "qr_code_path": null,
    "created_at": "2024-08-11T20:45:21.000000Z",
    "updated_at": "2024-08-11T20:45:21.000000Z"
  },
  {
    "id": 185,
    "name": "Israel Schuster",
    "age": 24,
    "points": 98,
    "address_id": 167,
    "qr_code_path": null,
    "created_at": "2024-08-11T20:45:21.000000Z",
    "updated_at": "2024-08-11T20:45:21.000000Z"
  },
....
   ]
```

3. Add/Remove points form user
PATCH /api/users/<user-id>/points
```
Response : {
    "message": "Points updated successfully.",
    "user": {
        "id": 200,
        "name": "Benjamin Swaniawski",
        "age": 54,
        "points": 101,
        "address_id": 182,
        "qr_code_path": null,
        "created_at": "2024-08-11T20:45:21.000000Z",
        "updated_at": "2024-08-11T22:02:01.000000Z"
    }
}
```

5. Delete a user
DELETE api/users/200
```
Response status : 204 No Content
```

6. Get user details
GET api/users/173
```
Response : {
  "user": {
    "id": 173,
    "name": "Tessie Flatley IV",
    "age": 54,
    "points": 98,
    "address_id": 155,
    "qr_code_path": null,
    "created_at": "2024-08-11T20:45:21.000000Z",
    "updated_at": "2024-08-11T20:45:21.000000Z",
    "address": {
      "id": 155,
      "address": "64880 Gusikowski Groves Apt. 559",
      "city": "Lake Beryl",
      "state": "OH",
      "zip": "04949-3881",
      "country": "FO",
      "created_at": "2024-08-12T02:15:21.000000Z"
    }
  },
  "address": "64880 Gusikowski Groves Apt. 559, Lake Beryl, OH 04949"
}
```

8. Add a user
POST api/users
```
Payload : {
  "name": "Test",
  "age": 34,
  "address": "Lane",
  "city": "Phoenix",
  "state": "AR",
  "zip": "12345",
  "country": "USA"
}

Response : {
    "name": "Test",
    "age": 34,
    "address_id": 205,
    "updated_at": "2024-08-11T22:06:47.000000Z",
    "created_at": "2024-08-11T22:06:47.000000Z",
    "id": 224
}
```

8. Users info grouped by score
GET api/users/grouping/score
```
Response : {
    "98": {
        "names": [
            "Tessie Flatley IV",
            "Israel Schuster"
        ],
        "average_age": 39
    },
    "3": {
        "names": [
            "Miss Makayla Koss"
        ],
        "average_age": 95
    },
    "60": {
        "names": [
            "Aisha Marvin",
            "Agustin Fahey",
            "Prof. Marisa Treutel"
        ],
        "average_age": 63.67
    },
   }
```

Commands and Jobs

1. php artisan user:reset-points : To reset the score of all users.
2. DeclareWinnerJob : To declare a winner on leader board
3. GenerateQrCodeJob : To create QR code and store it in public directory and store urkl in user table.
   


   
