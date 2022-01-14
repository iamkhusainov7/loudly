# PHP developer task

### Running and usage
* step 1: in the command line run:
```bash
docker-compose up -d
```
* step 2: set up the password you docker compose file (empty env vars).
* step 3: set up your .env file.
* step 4: set up your email configuration. It is used gmail mailing transoporter. But you can change to another one: https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
* step 5. in the command line run:
```bash
docker exec -it webtechnicalTask_php bash
```

* step 6. in the command line run:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
* step 7: open postman or any other tool for making REST API queries.
* step 8. register your first user
* step 9: check your email to verify the user
* step 10: repeat step #6 and step #7
* step 11: make your first invitation

### API endpoints

## GET
`authenticated` [/invitation/my-list]<br/> - must have header X-AUTH-TOKEN
`authenticated` [/invitation/invitation-list]<br/> - must have header X-AUTH-TOKEN

## POST
`any client` [/auth/user/registration]<br/>
**Parameters**

|                  Name | Required |  Type  | Description                 |
|----------------------:|:--------:|:------:|-----------------------------|
|       `userFirstName` | required | string | User firstname.             |
|        `userLastName` | required | string | User lastname.              |
|           `userEmail` | required | email  | User email.                 |
|        `userPassword` | required | string | User password               |
| `userPasswordConfirm` | required | string | User password confirmation. |

`any client` [/auth/user/login]<br/>

|                  Name | Required |  Type  | Description                 |
|----------------------:|:--------:|:------:|-----------------------------|
|           `userEmail` | required | email  | User email.                 |
|        `userPassword` | required | string | User password               |

`authorized only` [/auth/user/verify]<br/> - the link is generated and sent to the email

`authenticated` [/invitation/invite]<br/> - must have header X-AUTH-TOKEN

|                    Name | Required | Type  | Description                              |
|------------------------:|:--------:|:-----:|------------------------------------------|
| `invitationInvitedUser` | required | email | Email of the user who should be invited. |

## PUT
`authenticated` [/invitation/accept/{id}]<br/> - must have header X-AUTH-TOKEN
`authenticated` [/invitation/decline/{id}]<br/> - must have header X-AUTH-TOKEN
`authenticated` [/invitation/cancel/{id}]<br/> - must have header X-AUTH-TOKEN

| Name | Required | Type  | Description           |
|-----:|:--------:|:-----:|-----------------------|
| `id` | required | email | id of the invitation. |

## DELETE 
`authenticated` [/auth/user/logout]<br/> must have header X-AUTH-TOKEN
