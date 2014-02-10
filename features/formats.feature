Feature: Serializing Single Object
    In order to save objects
    As a developer
    I want to serialize the object in different formats

    Scenario: Using the JSON format
        Given a file called "User.php" with:
            """
            class User
            {
                public $name;
                public $age;
            }
            """
        And a file called "config/user.yml" with:
            """
            User:
                root: users
                name:
                    type: string
                    position: key
                age:
                    type: number
            """
        And a "User" object with "name = 'John Doe'" and "age = 32"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            {
                "users": {
                    "John Doe": {
                        "age": 32
                    }
                }
            }
            """
