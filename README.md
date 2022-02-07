<div id="top"></div>

## Getting Started

API description:

**POST /login** : Login and get JWT token

|  |                                    |
| :---         |:-----------------------------------|
| **Authorization**   | _Bearer Token_                     |
|  |                                    |
| **Request Headers** |                                    |
| _Content-Type_      | application/x-www-form-urlencodedl |
|  |                                    |
| **Request**         |                                    |
| email               | string : *first@exapmple.com*      |
| password               | string : *password*                |
|  |                                    |
| **Response**         |                                    |
| message               | string                             |
| token               | string                             |

**GET /users** : Return details about all users (for demo purposes only)

|  |                                    |
| :---         |:-----------------------------------|
| **Authorization**   | _Bearer Token_                     |

**Response:**
```json
{
  "data": 
  [
    {
        "id": <integer>,
        "name": "<string>",
        "email": "<string>"
    }, 
    {
        "id": <integer>,
        "name": "<string>",
        "email": "<string>"
    }
  ]
} 
```

**GET /users/:id** : Returns details about a particular user (for demo purposes only)

|  |                |
| :---         |:---------------|
| **Authorization**   | _Bearer Token_ |
| **Path Variables**   |                |
| id   | int            |

**Response:**
```json
{
    "id": <integer>,
    "name": "<string>",
    "email": "<string>"
} 
```

**GET /iam** : Returns details about a current logged user

|  |                |
| :---         |:---------------|
| **Authorization**   | _Bearer Token_ |

**Response:**
```json
{
    "id": <integer>,
    "name": "<string>",
    "email": "<string>"
} 
```

**GET /rnd_prize** : Return random prize

**Response:**
```json
{
  "id": <integer>, //prize Id
  "title": "<string>", //prize title
  "amount": <integer>
} 
```

## Usage

Test site - https://sg.putsko.com.ua

**Sample user:**
- id: *1*
- email: *first@exapmple.com*
- password: *password*
